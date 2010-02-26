/***
* Trigger Function fct_clr_incrementMainCode
* Automaticaly add a incremented "main" code for a specimen
* When the collection of the specimen has the flag must_be_incremented
*/
CREATE OR REPLACE FUNCTION fct_clr_incrementMainCode() RETURNS trigger
as $$
DECLARE
	last_line codes%ROWTYPE;
	must_be_incremented collections.code_auto_increment%TYPE;
BEGIN
	SELECT collections.code_auto_increment INTO must_be_incremented FROM collections WHERE collections.id = NEW.collection_ref;
	IF must_be_incremented = true THEN
		SELECT * INTO last_line FROM codes WHERE code_category = 'main' AND referenced_relation = 'specimens' AND record_id = NEW.id;
		IF FOUND THEN
			RETURN NEW;
 		END IF;
 
 		SELECT codes.* into last_line FROM codes
				INNER JOIN specimens ON codes.record_id = specimens.id AND referenced_relation = 'specimens'
				WHERE specimens.collection_ref =  NEW.collection_ref
					AND code_category = 'main'
					ORDER BY codes.code DESC
					LIMIT 1;
		IF NOT FOUND THEN
			last_line.code := 0;
			last_line.code_category := 'main';
		END IF;
		
		last_line.code := last_line.code+1;
		INSERT INTO codes (referenced_relation, record_id, code_category, code_prefix, code, code_suffix)
			VALUES ('specimens',NEW.id, 'main', last_line.code_prefix, last_line.code, last_line.code_suffix );
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;


/***
* Trigger Function fct_cpy_specimensMainCode
* Automaticaly copy the "main" code from the specimen to the specimen parts
* When the collection of the specimen has the flag code_part_code_auto_copy
*/
CREATE OR REPLACE FUNCTION fct_cpy_specimensMainCode() RETURNS trigger
as $$
DECLARE
	spec_code codes%ROWTYPE;
	must_be_copied collections.code_part_code_auto_copy%TYPE;
BEGIN
	SELECT collections.code_part_code_auto_copy INTO must_be_copied FROM collections 
			INNER JOIN specimens ON collections.id = specimens.collection_ref
			INNER JOIN specimen_individuals ON specimen_individuals.specimen_ref=specimens.id
				WHERE specimen_individuals.id = NEW.specimen_individual_ref;
	
	IF must_be_copied = true THEN
		SELECT codes.* into spec_code FROM codes
			INNER JOIN specimens ON record_id = specimens.id
			INNER JOIN specimen_individuals ON specimen_individuals.specimen_ref=specimens.id
			WHERE 
                referenced_relation = 'specimens'
                AND  specimen_individuals.id = NEW.specimen_individual_ref
				AND code_category = 'main'
				ORDER BY codes.code DESC
					LIMIT 1;
		IF FOUND THEN
			INSERT INTO codes (referenced_relation, record_id, code_category, code_prefix, code, code_suffix)
					VALUES ('specimen_parts',NEW.id, 'main', spec_code.code_prefix, spec_code.code , spec_code.code_suffix );
		END IF;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/***
* Trigger Function fct_cpy_idToCode
* Automaticaly copy the code form the id if the code is null
*/
CREATE OR REPLACE FUNCTION fct_cpy_idToCode() RETURNS trigger
AS $$
BEGIN
	IF NEW.code is null THEN
		NEW.code := NEW.id;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/***
* Function fct_chk_one_pref_language
* Check if there is only ONE preferred language for a user
* Return false if there is already a preferred language true otherwise
*/
CREATE OR REPLACE FUNCTION fct_chk_one_pref_language(id people_languages.id%TYPE, person people_languages.people_ref%TYPE, preferred people_languages.preferred_language%TYPE, table_prefix varchar) returns boolean
as $$
DECLARE
	response boolean default false;
	prefix varchar default coalesce(table_prefix, 'people');
	tabl varchar default prefix || '_languages';
	tableExist boolean default false;
BEGIN
	select count(*)::integer::boolean into tableExist from pg_tables where schemaname not in ('pg_catalog','information_schema') and tablename = tabl;
	IF tableExist THEN
		IF preferred THEN
			EXECUTE 'select not count(*)::integer::boolean from ' || quote_ident(tabl) || ' where ' || quote_ident(prefix || '_ref') || ' = ' || $2 || ' and preferred_language = ' || $3 || ' and id <> ' || $1 INTO response;
			
		ELSE
			response := true;
		END IF;
	END IF;
	return response;
END;
$$ LANGUAGE plpgsql;

/***
* Trigger Function fct_chk_one_pref_language
* Trigger that call the fct_chk_one_pref_language fct
*/
CREATE OR REPLACE FUNCTION fct_chk_one_pref_language(id people_languages.id%TYPE, person people_languages.people_ref%TYPE, preferred people_languages.preferred_language%TYPE) returns boolean
as $$
DECLARE
        response boolean default false;
BEGIN
	response := fct_chk_one_pref_language(id, person, preferred, 'people');
	return response;
END;
$$ LANGUAGE plpgsql;


/***
* Function fullToIndex
* Remove all the accents special chars from a string
*/
CREATE OR REPLACE FUNCTION fullToIndex(to_indexed varchar) RETURNS varchar STRICT
AS $$
DECLARE
	temp_string varchar;
BEGIN
    -- Investigate https://launchpad.net/postgresql-unaccent
    temp_string := REPLACE(to_indexed, 'Œ', 'oe');
    temp_string := REPLACE(temp_string, 'Ӕ', 'ae');
    temp_string := REPLACE(temp_string, 'œ', 'oe');
    temp_string := REPLACE(temp_string, 'æ', 'ae');
    temp_string := TRANSLATE(temp_string,'Ð','d');
    temp_string := TRANSLATE(temp_string,'ó','o');
    temp_string := TRANSLATE(temp_string,'ę','e');
	temp_string := LOWER(
				public.to_ascii(
					CONVERT_TO(temp_string, 'iso-8859-15'),
					'iso-8859-15')
				);
	--Remove ALL none alphanumerical char
	temp_string := regexp_replace(temp_string,'[^[:alnum:]]','', 'g');
	return substring(temp_string from 0 for 40);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

/***
* Trigger function fct_cpy_hierarchy_from_parents
* Version of function used to check what's coming from parents and what's coming from unit passed itself
*/
CREATE OR REPLACE FUNCTION fct_get_hierarchy_from_parents(referenced_relation varchar, id integer) RETURNS RECORD
AS $$
DECLARE
	level_sys_name catalogue_levels.level_sys_name%TYPE;
	level_ref  template_classifications.level_ref%TYPE;
	name_indexed template_classifications.name_indexed%TYPE;
	parent_ref template_classifications.parent_ref%TYPE;
	result RECORD;
BEGIN

	EXECUTE 'SELECT level_ref, name_indexed, parent_ref FROM ' || quote_ident(referenced_relation) || ' WHERE id = ' || id INTO level_ref, name_indexed, parent_ref;
	SELECT cl.level_sys_name INTO level_sys_name FROM catalogue_levels as cl WHERE cl.id = level_ref;
	IF referenced_relation = 'chronostratigraphy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'eon' THEN
					id
				ELSE
					pc.eon_ref
			END AS eon_ref,
			CASE
				WHEN level_sys_name = 'eon' THEN
					name_indexed
				ELSE
					pc.eon_indexed
			END AS eon_indexed,
			CASE
				WHEN level_sys_name = 'era' THEN
					id
				ELSE
					pc.era_ref
			END AS era_ref,
			CASE
				WHEN level_sys_name = 'era' THEN
					name_indexed
				ELSE
					pc.era_indexed
			END AS era_indexed,
			CASE
				WHEN level_sys_name = 'sub_era' THEN
					id
				ELSE
					pc.sub_era_ref
			END AS sub_era_ref,
			CASE
				WHEN level_sys_name = 'sub_era' THEN
					name_indexed
				ELSE
					pc.sub_era_indexed
			END AS sub_era_indexed,
			CASE
				WHEN level_sys_name = 'system' THEN
					id
				ELSE
					pc.system_ref
			END AS system_ref,
			CASE
				WHEN level_sys_name = 'system' THEN
					name_indexed
				ELSE
					pc.system_indexed
			END AS system_indexed,
			CASE
				WHEN level_sys_name = 'serie' THEN
					id
				ELSE
					pc.serie_ref
			END AS serie_ref,
			CASE
				WHEN level_sys_name = 'serie' THEN
					name_indexed
				ELSE
					pc.serie_indexed
			END AS serie_indexed,
			CASE
				WHEN level_sys_name = 'stage' THEN
					id
				ELSE
					pc.stage_ref
			END AS stage_ref,
			CASE
				WHEN level_sys_name = 'stage' THEN
					name_indexed
				ELSE
					pc.stage_indexed
			END AS stage_indexed,
			CASE
				WHEN level_sys_name = 'sub_stage' THEN
					id
				ELSE
					pc.sub_stage_ref
			END AS sub_stage_ref,
			CASE
				WHEN level_sys_name = 'sub_stage' THEN
					name_indexed
				ELSE
					pc.sub_stage_indexed
			END AS sub_stage_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					id
				ELSE
					pc.sub_level_1_ref
			END AS sub_level_1_ref,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					name_indexed
				ELSE
					pc.sub_level_1_indexed
			END AS sub_level_1_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					id
				ELSE
					pc.sub_level_2_ref
			END AS sub_level_2_ref,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					name_indexed
				ELSE
					pc.sub_level_2_indexed
			END AS sub_level_2_indexed
		INTO 
			result
		FROM chronostratigraphy AS pc
		WHERE pc.id = parent_ref;
	ELSIF referenced_relation = 'lithostratigraphy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'group' THEN
					id
				ELSE
					pl.group_ref
			END AS group_ref,
			CASE
				WHEN level_sys_name = 'group' THEN
					name_indexed
				ELSE
					pl.group_indexed
			END AS group_indexed,
			CASE
				WHEN level_sys_name = 'formation' THEN
					id
				ELSE
					pl.formation_ref
			END AS formation_ref,
			CASE
				WHEN level_sys_name = 'formation' THEN
					name_indexed
				ELSE
					pl.formation_indexed
			END AS formation_indexed,
			CASE
				WHEN level_sys_name = 'member' THEN
					id
				ELSE
					pl.member_ref
			END AS member_ref,
			CASE
				WHEN level_sys_name = 'member' THEN
					name_indexed
				ELSE
					pl.member_indexed
			END AS member_indexed,
			CASE
				WHEN level_sys_name = 'layer' THEN
					id
				ELSE
					pl.layer_ref
			END AS layer_ref,
			CASE
				WHEN level_sys_name = 'layer' THEN
					name_indexed
				ELSE
					pl.layer_indexed
			END AS layer_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					id
				ELSE
					pl.sub_level_1_ref
			END AS sub_level_1_ref,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					name_indexed
				ELSE
					pl.sub_level_1_indexed
			END AS sub_level_1_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					id
				ELSE
					pl.sub_level_2_ref
			END AS sub_level_2_ref,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					name_indexed
				ELSE
					pl.sub_level_2_indexed
			END AS sub_level_2_indexed
		INTO 
			result
		FROM lithostratigraphy AS pl
		WHERE pl.id = parent_ref;
	ELSIF referenced_relation = 'lithology' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'unit_main_group' THEN
					id
				ELSE
					pl.unit_main_group_ref
			END AS unit_main_group_ref,
			CASE
				WHEN level_sys_name = 'unit_main_group' THEN
					id
				ELSE
					pl.unit_main_group_indexed
			END AS unit_main_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					id
				ELSE
					pl.unit_group_ref
			END AS unit_group_ref,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					id
				ELSE
					pl.unit_group_indexed
			END AS unit_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_sub_group' THEN
					id
				ELSE
					pl.unit_sub_group_ref
			END AS unit_sub_group_ref,
			CASE
				WHEN level_sys_name = 'unit_sub_group' THEN
					id
				ELSE
					pl.unit_sub_group_indexed
			END AS unit_sub_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_rock' THEN
					id
				ELSE
					pl.unit_rock_ref
			END AS unit_rock_ref,
			CASE
				WHEN level_sys_name = 'unit_rock' THEN
					id
				ELSE
					pl.unit_rock_indexed
			END AS unit_rock_indexed
		INTO 
			result
		FROM lithology AS pl
		WHERE pl.id = parent_ref;
	ELSIF referenced_relation = 'mineralogy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'unit_class' THEN
					id
				ELSE
					pm.unit_class_ref
			END AS unit_class_ref,
			CASE
				WHEN level_sys_name = 'unit_class' THEN
					name_indexed
				ELSE
					pm.unit_class_indexed
			END AS unit_class_indexed,
			CASE
				WHEN level_sys_name = 'unit_division' THEN
					id
				ELSE
					pm.unit_division_ref
			END AS unit_division_ref,
			CASE
				WHEN level_sys_name = 'unit_division' THEN
					name_indexed
				ELSE
					pm.unit_division_indexed
			END AS unit_division_indexed,
			CASE
				WHEN level_sys_name = 'unit_family' THEN
					id
				ELSE
					pm.unit_family_ref
			END AS unit_family_ref,
			CASE
				WHEN level_sys_name = 'unit_family' THEN
					name_indexed
				ELSE
					pm.unit_family_indexed
			END AS unit_family_indexed,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					id
				ELSE
					pm.unit_group_ref
			END AS unit_group_ref,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					name_indexed
				ELSE
					pm.unit_group_indexed
			END AS unit_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_variety' THEN
					id
				ELSE
					pm.unit_variety_ref
			END AS unit_variety_ref,
			CASE
				WHEN level_sys_name = 'unit_variety' THEN
					name_indexed
				ELSE
					pm.unit_variety_indexed
			END AS unit_variety_indexed
		INTO 
			result
		FROM mineralogy AS pm
		WHERE pm.id = parent_ref;
	ELSIF referenced_relation = 'taxonomy' THEN
		SELECT
			CASE
				WHEN level_sys_name = 'domain' THEN
					id
				ELSE
					pt.domain_ref
			END AS domain_ref,
			CASE
				WHEN level_sys_name = 'domain' THEN
					name_indexed
				ELSE
					pt.domain_indexed
			END AS domain_indexed,
			CASE
				WHEN level_sys_name = 'kingdom' THEN
					id
				ELSE
					pt.kingdom_ref
			END AS kingdom_ref,
			CASE
				WHEN level_sys_name = 'kingdom' THEN
					name_indexed
				ELSE
					pt.kingdom_indexed
			END AS kingdom_indexed,
			CASE
				WHEN level_sys_name = 'super_phylum' THEN
					id
				ELSE
					pt.super_phylum_ref
			END AS super_phylum_ref,
			CASE
				WHEN level_sys_name = 'super_phylum' THEN
					name_indexed
				ELSE
					pt.super_phylum_indexed
			END AS super_phylum_indexed,
			CASE
				WHEN level_sys_name = 'phylum' THEN
					id
				ELSE
					pt.phylum_ref
			END AS phylum_ref,
			CASE
				WHEN level_sys_name = 'phylum' THEN
					name_indexed
				ELSE
					pt.phylum_indexed
			END AS phylum_indexed,
			CASE
				WHEN level_sys_name = 'sub_phylum' THEN
					id
				ELSE
					pt.sub_phylum_ref
			END AS sub_phylum_ref,
			CASE
				WHEN level_sys_name = 'sub_phylum' THEN
					name_indexed
				ELSE
					pt.sub_phylum_indexed
			END AS sub_phylum_indexed,
			CASE
				WHEN level_sys_name = 'infra_phylum' THEN
					id
				ELSE
					pt.infra_phylum_ref
			END AS infra_phylum_ref,
			CASE
				WHEN level_sys_name = 'infra_phylum' THEN
					name_indexed
				ELSE
					pt.infra_phylum_indexed
			END AS infra_phylum_indexed,
			CASE
				WHEN level_sys_name = 'super_cohort_botany' THEN
					id
				ELSE
					pt.super_cohort_botany_ref
			END AS super_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'super_cohort_botany' THEN
					name_indexed
				ELSE
					pt.super_cohort_botany_indexed
			END AS super_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'cohort_botany' THEN
					id
				ELSE
					pt.cohort_botany_ref
			END AS cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'cohort_botany' THEN
					name_indexed
				ELSE
					pt.cohort_botany_indexed
			END AS cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'sub_cohort_botany' THEN
					id
				ELSE
					pt.sub_cohort_botany_ref
			END AS sub_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'sub_cohort_botany' THEN
					name_indexed
				ELSE
					pt.sub_cohort_botany_indexed
			END AS sub_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'infra_cohort_botany' THEN
					id
				ELSE
					pt.infra_cohort_botany_ref
			END AS infra_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'infra_cohort_botany' THEN
					name_indexed
				ELSE
					pt.infra_cohort_botany_indexed
			END AS infra_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'super_class' THEN
					id
				ELSE
					pt.super_class_ref
			END AS super_class_ref,
			CASE
				WHEN level_sys_name = 'super_class' THEN
					name_indexed
				ELSE
					pt.super_class_indexed
			END AS super_class_indexed,
			CASE
				WHEN level_sys_name = 'class' THEN
					id
				ELSE
					pt.class_ref
			END AS class_ref,
			CASE
				WHEN level_sys_name = 'class' THEN
					name_indexed
				ELSE
					pt.class_indexed
			END AS class_indexed,
			CASE
				WHEN level_sys_name = 'sub_class' THEN
					id
				ELSE
					pt.sub_class_ref
			END AS sub_class_ref,
			CASE
				WHEN level_sys_name = 'sub_class' THEN
					name_indexed
				ELSE
					pt.sub_class_indexed
			END AS sub_class_indexed,
			CASE
				WHEN level_sys_name = 'infra_class' THEN
					id
				ELSE
					pt.infra_class_ref
			END AS infra_class_ref,
			CASE
				WHEN level_sys_name = 'infra_class' THEN
					name_indexed
				ELSE
					pt.infra_class_indexed
			END AS infra_class_indexed,
			CASE
				WHEN level_sys_name = 'super_division' THEN
					id
				ELSE
					pt.super_division_ref
			END AS super_division_ref,
			CASE
				WHEN level_sys_name = 'super_division' THEN
					name_indexed
				ELSE
					pt.super_division_indexed
			END AS super_division_indexed,
			CASE
				WHEN level_sys_name = 'division' THEN
					id
				ELSE
					pt.division_ref
			END AS division_ref,
			CASE
				WHEN level_sys_name = 'division' THEN
					name_indexed
				ELSE
					pt.division_indexed
			END AS division_indexed,
			CASE
				WHEN level_sys_name = 'sub_division' THEN
					id
				ELSE
					pt.sub_division_ref
			END AS sub_division_ref,
			CASE
				WHEN level_sys_name = 'sub_division' THEN
					name_indexed
				ELSE
					pt.sub_division_indexed
			END AS sub_division_indexed,
			CASE
				WHEN level_sys_name = 'infra_division' THEN
					id
				ELSE
					pt.infra_division_ref
			END AS infra_division_ref,
			CASE
				WHEN level_sys_name = 'infra_division' THEN
					name_indexed
				ELSE
					pt.infra_division_indexed
			END AS infra_division_indexed,
			CASE
				WHEN level_sys_name = 'super_legion' THEN
					id
				ELSE
					pt.super_legion_ref
			END AS super_legion_ref,
			CASE
				WHEN level_sys_name = 'super_legion' THEN
					name_indexed
				ELSE
					pt.super_legion_indexed
			END AS super_legion_indexed,
			CASE
				WHEN level_sys_name = 'legion' THEN
					id
				ELSE
					pt.legion_ref
			END AS legion_ref,
			CASE
				WHEN level_sys_name = 'legion' THEN
					name_indexed
				ELSE
					pt.legion_indexed
			END AS legion_indexed,
			CASE
				WHEN level_sys_name = 'sub_legion' THEN
					id
				ELSE
					pt.sub_legion_ref
			END AS sub_legion_ref,
			CASE
				WHEN level_sys_name = 'sub_legion' THEN
					name_indexed
				ELSE
					pt.sub_legion_indexed
			END AS sub_legion_indexed,
			CASE
				WHEN level_sys_name = 'infra_legion' THEN
					id
				ELSE
					pt.infra_legion_ref
			END AS infra_legion_ref,
			CASE
				WHEN level_sys_name = 'infra_legion' THEN
					name_indexed
				ELSE
					pt.infra_legion_indexed
			END AS infra_legion_indexed,
			CASE
				WHEN level_sys_name = 'super_cohort_zoology' THEN
					id
				ELSE
					pt.super_cohort_zoology_ref
			END AS super_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'super_cohort_zoology' THEN
					name_indexed
				ELSE
					pt.super_cohort_zoology_indexed
			END AS super_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'cohort_zoology' THEN
					id
				ELSE
					pt.cohort_zoology_ref
			END AS cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'cohort_zoology' THEN
					name_indexed
				ELSE
					pt.cohort_zoology_indexed
			END AS cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'sub_cohort_zoology' THEN
					id
				ELSE
					pt.sub_cohort_zoology_ref
			END AS sub_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'sub_cohort_zoology' THEN
					name_indexed
				ELSE
					pt.sub_cohort_zoology_indexed
			END AS sub_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'infra_cohort_zoology' THEN
					id
				ELSE
					pt.infra_cohort_zoology_ref
			END AS infra_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'infra_cohort_zoology' THEN
					name_indexed
				ELSE
					pt.infra_cohort_zoology_indexed
			END AS infra_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'super_order' THEN
					id
				ELSE
					pt.super_order_ref
			END AS super_order_ref,
			CASE
				WHEN level_sys_name = 'super_order' THEN
					name_indexed
				ELSE
					pt.super_order_indexed
			END AS super_order_indexed,
			CASE
				WHEN level_sys_name = 'order' THEN
					id
				ELSE
					pt.order_ref
			END AS order_ref,
			CASE
				WHEN level_sys_name = 'order' THEN
					name_indexed
				ELSE
					pt.order_indexed
			END AS order_indexed,
			CASE
				WHEN level_sys_name = 'sub_order' THEN
					id
				ELSE
					pt.sub_order_ref
			END AS sub_order_ref,
			CASE
				WHEN level_sys_name = 'sub_order' THEN
					name_indexed
				ELSE
					pt.sub_order_indexed
			END AS sub_order_indexed,
			CASE
				WHEN level_sys_name = 'infra_order' THEN
					id
				ELSE
					pt.infra_order_ref
			END AS infra_order_ref,
			CASE
				WHEN level_sys_name = 'infra_order' THEN
					name_indexed
				ELSE
					pt.infra_order_indexed
			END AS infra_order_indexed,
			CASE
				WHEN level_sys_name = 'section_zoology' THEN
					id
				ELSE
					pt.section_zoology_ref
			END AS section_zoology_ref,
			CASE
				WHEN level_sys_name = 'section_zoology' THEN
					name_indexed
				ELSE
					pt.section_zoology_indexed
			END AS section_zoology_indexed,
			CASE
				WHEN level_sys_name = 'sub_section_zoology' THEN
					id
				ELSE
					pt.sub_section_zoology_ref
			END AS sub_section_zoology_ref,
			CASE
				WHEN level_sys_name = 'sub_section_zoology' THEN
					name_indexed
				ELSE
					pt.sub_section_zoology_indexed
			END AS sub_section_zoology_indexed,
			CASE
				WHEN level_sys_name = 'super_family' THEN
					id
				ELSE
					pt.super_family_ref
			END AS super_family_ref,
			CASE
				WHEN level_sys_name = 'super_family' THEN
					name_indexed
				ELSE
					pt.super_family_indexed
			END AS super_family_indexed,
			CASE
				WHEN level_sys_name = 'family' THEN
					id
				ELSE
					pt.family_ref
			END AS family_ref,
			CASE
				WHEN level_sys_name = 'family' THEN
					name_indexed
				ELSE
					pt.family_indexed
			END AS family_indexed,
			CASE
				WHEN level_sys_name = 'sub_family' THEN
					id
				ELSE
					pt.sub_family_ref
			END AS sub_family_ref,
			CASE
				WHEN level_sys_name = 'sub_family' THEN
					name_indexed
				ELSE
					pt.sub_family_indexed
			END AS sub_family_indexed,
			CASE
				WHEN level_sys_name = 'infra_family' THEN
					id
				ELSE
					pt.infra_family_ref
			END AS infra_family_ref,
			CASE
				WHEN level_sys_name = 'infra_family' THEN
					name_indexed
				ELSE
					pt.infra_family_indexed
			END AS infra_family_indexed,
			CASE
				WHEN level_sys_name = 'super_tribe' THEN
					id
				ELSE
					pt.super_tribe_ref
			END AS super_tribe_ref,
			CASE
				WHEN level_sys_name = 'super_tribe' THEN
					name_indexed
				ELSE
					pt.super_tribe_indexed
			END AS super_tribe_indexed,
			CASE
				WHEN level_sys_name = 'tribe' THEN
					id
				ELSE
					pt.tribe_ref
			END AS tribe_ref,
			CASE
				WHEN level_sys_name = 'tribe' THEN
					name_indexed
				ELSE
					pt.tribe_indexed
			END AS tribe_indexed,
			CASE
				WHEN level_sys_name = 'sub_tribe' THEN
					id
				ELSE
					pt.sub_tribe_ref
			END AS sub_tribe_ref,
			CASE
				WHEN level_sys_name = 'sub_tribe' THEN
					name_indexed
				ELSE
					pt.sub_tribe_indexed
			END AS sub_tribe_indexed,
			CASE
				WHEN level_sys_name = 'infra_tribe' THEN
					id
				ELSE
					pt.infra_tribe_ref
			END AS infra_tribe_ref,
			CASE
				WHEN level_sys_name = 'infra_tribe' THEN
					name_indexed
				ELSE
					pt.infra_tribe_indexed
			END AS infra_tribe_indexed,
			CASE
				WHEN level_sys_name = 'genus' THEN
					id
				ELSE
					pt.genus_ref
			END AS genus_ref,
			CASE
				WHEN level_sys_name = 'genus' THEN
					name_indexed
				ELSE
					pt.genus_indexed
			END AS genus_indexed,
			CASE
				WHEN level_sys_name = 'sub_genus' THEN
					id
				ELSE
					pt.sub_genus_ref
			END AS sub_genus_ref,
			CASE
				WHEN level_sys_name = 'sub_genus' THEN
					name_indexed
				ELSE
					pt.sub_genus_indexed
			END AS sub_genus_indexed,
			CASE
				WHEN level_sys_name = 'section_botany' THEN
					id
				ELSE
					pt.section_botany_ref
			END AS section_botany_ref,
			CASE
				WHEN level_sys_name = 'section_botany' THEN
					name_indexed
				ELSE
					pt.section_botany_indexed
			END AS section_botany_indexed,
			CASE
				WHEN level_sys_name = 'sub_section_botany' THEN
					id
				ELSE
					pt.sub_section_botany_ref
			END AS sub_section_botany_ref,
			CASE
				WHEN level_sys_name = 'sub_section_botany' THEN
					name_indexed
				ELSE
					pt.sub_section_botany_indexed
			END AS sub_section_botany_indexed,
			CASE
				WHEN level_sys_name = 'serie' THEN
					id
				ELSE
					pt.serie_ref
			END AS serie_ref,
			CASE
				WHEN level_sys_name = 'serie' THEN
					name_indexed
				ELSE
					pt.serie_indexed
			END AS serie_indexed,
			CASE
				WHEN level_sys_name = 'sub_serie' THEN
					id
				ELSE
					pt.sub_serie_ref
			END AS sub_serie_ref,
			CASE
				WHEN level_sys_name = 'sub_serie' THEN
					name_indexed
				ELSE
					pt.sub_serie_indexed
			END AS sub_serie_indexed,
			CASE
				WHEN level_sys_name = 'super_species' THEN
					id
				ELSE
					pt.super_species_ref
			END AS super_species_ref,
			CASE
				WHEN level_sys_name = 'super_species' THEN
					name_indexed
				ELSE
					pt.super_species_indexed
			END AS super_species_indexed,
			CASE
				WHEN level_sys_name = 'species' THEN
					id
				ELSE
					pt.species_ref
			END AS species_ref,
			CASE
				WHEN level_sys_name = 'species' THEN
					name_indexed
				ELSE
					pt.species_indexed
			END AS species_indexed,
			CASE
				WHEN level_sys_name = 'sub_species' THEN
					id
				ELSE
					pt.sub_species_ref
			END AS sub_species_ref,
			CASE
				WHEN level_sys_name = 'sub_species' THEN
					name_indexed
				ELSE
					pt.sub_species_indexed
			END AS sub_species_indexed,
			CASE
				WHEN level_sys_name = 'variety' THEN
					id
				ELSE
					pt.variety_ref
			END AS variety_ref,
			CASE
				WHEN level_sys_name = 'variety' THEN
					name_indexed
				ELSE
					pt.variety_indexed
			END AS variety_indexed,
			CASE
				WHEN level_sys_name = 'sub_variety' THEN
					id
				ELSE
					pt.sub_variety_ref
			END AS sub_variety_ref,
			CASE
				WHEN level_sys_name = 'sub_variety' THEN
					name_indexed
				ELSE
					pt.sub_variety_indexed
			END AS sub_variety_indexed,
			CASE
				WHEN level_sys_name = 'form' THEN
					id
				ELSE
					pt.form_ref
			END AS form_ref,
			CASE
				WHEN level_sys_name = 'form' THEN
					name_indexed
				ELSE
					pt.form_indexed
			END AS form_indexed,
			CASE
				WHEN level_sys_name = 'sub_form' THEN
					id
				ELSE
					pt.sub_form_ref
			END AS sub_form_ref,
			CASE
				WHEN level_sys_name = 'sub_form' THEN
					name_indexed
				ELSE
					pt.sub_form_indexed
			END AS sub_form_indexed,
			CASE
				WHEN level_sys_name = 'abberans' THEN
					id
				ELSE
					pt.abberans_ref
			END AS abberans_ref,
			CASE
				WHEN level_sys_name = 'abberans' THEN
					name_indexed
				ELSE
					pt.abberans_indexed
			END AS abberans_indexed
		INTO 
			result
		FROM taxonomy AS pt
		WHERE pt.id = parent_ref;
	END IF;
	RETURN result;
END;
$$ LANGUAGE plpgsql;

/***
* Trigger function fct_cpy_hierarchy_from_parents
* Version of function used to check what's coming from parents and what's coming from unit passed itself
*/
CREATE OR REPLACE FUNCTION fct_cpy_hierarchy_from_parents() RETURNS trigger
AS $$
DECLARE
	level_sys_name catalogue_levels.level_sys_name%TYPE;
BEGIN
	SELECT cl.level_sys_name INTO level_sys_name FROM catalogue_levels as cl WHERE cl.id = NEW.level_ref;
	IF NEW.id = 0 THEN
		RETURN NEW;
	END IF;
	IF TG_TABLE_NAME = 'chronostratigraphy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'eon' THEN
					NEW.id
				ELSE
					pc.eon_ref
			END AS eon_ref,
			CASE
				WHEN level_sys_name = 'eon' THEN
					NEW.name_indexed
				ELSE
					pc.eon_indexed
			END AS eon_indexed,
			CASE
				WHEN level_sys_name = 'era' THEN
					NEW.id
				ELSE
					pc.era_ref
			END AS era_ref,
			CASE
				WHEN level_sys_name = 'era' THEN
					NEW.name_indexed
				ELSE
					pc.era_indexed
			END AS era_indexed,
			CASE
				WHEN level_sys_name = 'sub_era' THEN
					NEW.id
				ELSE
					pc.sub_era_ref
			END AS sub_era_ref,
			CASE
				WHEN level_sys_name = 'sub_era' THEN
					NEW.name_indexed
				ELSE
					pc.sub_era_indexed
			END AS sub_era_indexed,
			CASE
				WHEN level_sys_name = 'system' THEN
					NEW.id
				ELSE
					pc.system_ref
			END AS system_ref,
			CASE
				WHEN level_sys_name = 'system' THEN
					NEW.name_indexed
				ELSE
					pc.system_indexed
			END AS system_indexed,
			CASE
				WHEN level_sys_name = 'serie' THEN
					NEW.id
				ELSE
					pc.serie_ref
			END AS serie_ref,
			CASE
				WHEN level_sys_name = 'serie' THEN
					NEW.name_indexed
				ELSE
					pc.serie_indexed
			END AS serie_indexed,
			CASE
				WHEN level_sys_name = 'stage' THEN
					NEW.id
				ELSE
					pc.stage_ref
			END AS stage_ref,
			CASE
				WHEN level_sys_name = 'stage' THEN
					NEW.name_indexed
				ELSE
					pc.stage_indexed
			END AS stage_indexed,
			CASE
				WHEN level_sys_name = 'sub_stage' THEN
					NEW.id
				ELSE
					pc.sub_stage_ref
			END AS sub_stage_ref,
			CASE
				WHEN level_sys_name = 'sub_stage' THEN
					NEW.name_indexed
				ELSE
					pc.sub_stage_indexed
			END AS sub_stage_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					NEW.id
				ELSE
					pc.sub_level_1_ref
			END AS sub_level_1_ref,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					NEW.name_indexed
				ELSE
					pc.sub_level_1_indexed
			END AS sub_level_1_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					NEW.id
				ELSE
					pc.sub_level_2_ref
			END AS sub_level_2_ref,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					NEW.name_indexed
				ELSE
					pc.sub_level_2_indexed
			END AS sub_level_2_indexed
		INTO 
			NEW.eon_ref,
			NEW.eon_indexed,
			NEW.era_ref,
			NEW.era_indexed,
			NEW.sub_era_ref,
			NEW.sub_era_indexed,
			NEW.system_ref,
			NEW.system_indexed,
			NEW.serie_ref,
			NEW.serie_indexed,
			NEW.stage_ref,
			NEW.stage_indexed,
			NEW.sub_stage_ref,
			NEW.sub_stage_indexed,
			NEW.sub_level_1_ref,
			NEW.sub_level_1_indexed,
			NEW.sub_level_2_ref,
			NEW.sub_level_2_indexed
		FROM chronostratigraphy AS pc
		WHERE pc.id = NEW.parent_ref;
	ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'group' THEN
					NEW.id
				ELSE
					pl.group_ref
			END AS group_ref,
			CASE
				WHEN level_sys_name = 'group' THEN
					NEW.name_indexed
				ELSE
					pl.group_indexed
			END AS group_indexed,
			CASE
				WHEN level_sys_name = 'formation' THEN
					NEW.id
				ELSE
					pl.formation_ref
			END AS formation_ref,
			CASE
				WHEN level_sys_name = 'formation' THEN
					NEW.name_indexed
				ELSE
					pl.formation_indexed
			END AS formation_indexed,
			CASE
				WHEN level_sys_name = 'member' THEN
					NEW.id
				ELSE
					pl.member_ref
			END AS member_ref,
			CASE
				WHEN level_sys_name = 'member' THEN
					NEW.name_indexed
				ELSE
					pl.member_indexed
			END AS member_indexed,
			CASE
				WHEN level_sys_name = 'layer' THEN
					NEW.id
				ELSE
					pl.layer_ref
			END AS layer_ref,
			CASE
				WHEN level_sys_name = 'layer' THEN
					NEW.name_indexed
				ELSE
					pl.layer_indexed
			END AS layer_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					NEW.id
				ELSE
					pl.sub_level_1_ref
			END AS sub_level_1_ref,
			CASE
				WHEN level_sys_name = 'sub_level_1' THEN
					NEW.name_indexed
				ELSE
					pl.sub_level_1_indexed
			END AS sub_level_1_indexed,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					NEW.id
				ELSE
					pl.sub_level_2_ref
			END AS sub_level_2_ref,
			CASE
				WHEN level_sys_name = 'sub_level_2' THEN
					NEW.name_indexed
				ELSE
					pl.sub_level_2_indexed
			END AS sub_level_2_indexed
		INTO 
			NEW.group_ref,
			NEW.group_indexed,
			NEW.formation_ref,
			NEW.formation_indexed,
			NEW.member_ref,
			NEW.member_indexed,
			NEW.layer_ref,
			NEW.layer_indexed,
			NEW.sub_level_1_ref,
			NEW.sub_level_1_indexed,
			NEW.sub_level_2_ref,
			NEW.sub_level_2_indexed
		FROM lithostratigraphy AS pl
		WHERE pl.id = NEW.parent_ref;
	ELSIF TG_TABLE_NAME = 'lithology' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'unit_main_group' THEN
					NEW.id
				ELSE
					pl.unit_main_group_ref
			END AS unit_main_group_ref,
			CASE
				WHEN level_sys_name = 'unit_main_group' THEN
					NEW.name_indexed
				ELSE
					pl.unit_main_group_indexed
			END AS unit_main_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					NEW.id
				ELSE
					pl.unit_group_ref
			END AS unit_group_ref,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					NEW.name_indexed
				ELSE
					pl.unit_group_indexed
			END AS unit_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_sub_group' THEN
					NEW.id
				ELSE
					pl.unit_sub_group_ref
			END AS unit_sub_group_ref,
			CASE
				WHEN level_sys_name = 'unit_sub_group' THEN
					NEW.name_indexed
				ELSE
					pl.unit_sub_group_indexed
			END AS unit_sub_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_rock' THEN
					NEW.id
				ELSE
					pl.unit_rock_ref
			END AS unit_rock_ref,
			CASE
				WHEN level_sys_name = 'unit_rock' THEN
					NEW.name_indexed
				ELSE
					pl.unit_rock_indexed
			END AS unit_rock_indexed
		INTO 
			NEW.unit_main_group_ref,
			NEW.unit_main_group_indexed,
			NEW.unit_group_ref,
			NEW.unit_group_indexed,
			NEW.unit_sub_group_ref,
			NEW.unit_sub_group_indexed,
			NEW.unit_rock_ref,
			NEW.unit_rock_indexed
		FROM lithology AS pl
		WHERE pl.id = NEW.parent_ref;
	ELSIF TG_TABLE_NAME = 'mineralogy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'unit_class' THEN
					NEW.id
				ELSE
					pm.unit_class_ref
			END AS unit_class_ref,
			CASE
				WHEN level_sys_name = 'unit_class' THEN
					NEW.name_indexed
				ELSE
					pm.unit_class_indexed
			END AS unit_class_indexed,
			CASE
				WHEN level_sys_name = 'unit_division' THEN
					NEW.id
				ELSE
					pm.unit_division_ref
			END AS unit_division_ref,
			CASE
				WHEN level_sys_name = 'unit_division' THEN
					NEW.name_indexed
				ELSE
					pm.unit_division_indexed
			END AS unit_division_indexed,
			CASE
				WHEN level_sys_name = 'unit_family' THEN
					NEW.id
				ELSE
					pm.unit_family_ref
			END AS unit_family_ref,
			CASE
				WHEN level_sys_name = 'unit_family' THEN
					NEW.name_indexed
				ELSE
					pm.unit_family_indexed
			END AS unit_family_indexed,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					NEW.id
				ELSE
					pm.unit_group_ref
			END AS unit_group_ref,
			CASE
				WHEN level_sys_name = 'unit_group' THEN
					NEW.name_indexed
				ELSE
					pm.unit_group_indexed
			END AS unit_group_indexed,
			CASE
				WHEN level_sys_name = 'unit_variety' THEN
					NEW.id
				ELSE
					pm.unit_variety_ref
			END AS unit_variety_ref,
			CASE
				WHEN level_sys_name = 'unit_variety' THEN
					NEW.name_indexed
				ELSE
					pm.unit_variety_indexed
			END AS unit_variety_indexed
		INTO 
			NEW.unit_class_ref,
			NEW.unit_class_indexed,
			NEW.unit_division_ref,
			NEW.unit_division_indexed,
			NEW.unit_family_ref,
			NEW.unit_family_indexed,
			NEW.unit_group_ref,
			NEW.unit_group_indexed,
			NEW.unit_variety_ref,
			NEW.unit_variety_indexed
		FROM mineralogy AS pm
		WHERE pm.id = NEW.parent_ref;
	ELSIF TG_TABLE_NAME = 'taxonomy' THEN
		SELECT
			CASE
				WHEN level_sys_name = 'domain' THEN
					NEW.id
				ELSE
					pt.domain_ref
			END AS domain_ref,
			CASE
				WHEN level_sys_name = 'domain' THEN
					NEW.name_indexed
				ELSE
					pt.domain_indexed
			END AS domain_indexed,
			CASE
				WHEN level_sys_name = 'kingdom' THEN
					NEW.id
				ELSE
					pt.kingdom_ref
			END AS kingdom_ref,
			CASE
				WHEN level_sys_name = 'kingdom' THEN
					NEW.name_indexed
				ELSE
					pt.kingdom_indexed
			END AS kingdom_indexed,
			CASE
				WHEN level_sys_name = 'super_phylum' THEN
					NEW.id
				ELSE
					pt.super_phylum_ref
			END AS super_phylum_ref,
			CASE
				WHEN level_sys_name = 'super_phylum' THEN
					NEW.name_indexed
				ELSE
					pt.super_phylum_indexed
			END AS super_phylum_indexed,
			CASE
				WHEN level_sys_name = 'phylum' THEN
					NEW.id
				ELSE
					pt.phylum_ref
			END AS phylum_ref,
			CASE
				WHEN level_sys_name = 'phylum' THEN
					NEW.name_indexed
				ELSE
					pt.phylum_indexed
			END AS phylum_indexed,
			CASE
				WHEN level_sys_name = 'sub_phylum' THEN
					NEW.id
				ELSE
					pt.sub_phylum_ref
			END AS sub_phylum_ref,
			CASE
				WHEN level_sys_name = 'sub_phylum' THEN
					NEW.name_indexed
				ELSE
					pt.sub_phylum_indexed
			END AS sub_phylum_indexed,
			CASE
				WHEN level_sys_name = 'infra_phylum' THEN
					NEW.id
				ELSE
					pt.infra_phylum_ref
			END AS infra_phylum_ref,
			CASE
				WHEN level_sys_name = 'infra_phylum' THEN
					NEW.name_indexed
				ELSE
					pt.infra_phylum_indexed
			END AS infra_phylum_indexed,
			CASE
				WHEN level_sys_name = 'super_cohort_botany' THEN
					NEW.id
				ELSE
					pt.super_cohort_botany_ref
			END AS super_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'super_cohort_botany' THEN
					NEW.name_indexed
				ELSE
					pt.super_cohort_botany_indexed
			END AS super_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'cohort_botany' THEN
					NEW.id
				ELSE
					pt.cohort_botany_ref
			END AS cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'cohort_botany' THEN
					NEW.name_indexed
				ELSE
					pt.cohort_botany_indexed
			END AS cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'sub_cohort_botany' THEN
					NEW.id
				ELSE
					pt.sub_cohort_botany_ref
			END AS sub_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'sub_cohort_botany' THEN
					NEW.name_indexed
				ELSE
					pt.sub_cohort_botany_indexed
			END AS sub_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'infra_cohort_botany' THEN
					NEW.id
				ELSE
					pt.infra_cohort_botany_ref
			END AS infra_cohort_botany_ref,
			CASE
				WHEN level_sys_name = 'infra_cohort_botany' THEN
					NEW.name_indexed
				ELSE
					pt.infra_cohort_botany_indexed
			END AS infra_cohort_botany_indexed,
			CASE
				WHEN level_sys_name = 'super_class' THEN
					NEW.id
				ELSE
					pt.super_class_ref
			END AS super_class_ref,
			CASE
				WHEN level_sys_name = 'super_class' THEN
					NEW.name_indexed
				ELSE
					pt.super_class_indexed
			END AS super_class_indexed,
			CASE
				WHEN level_sys_name = 'class' THEN
					NEW.id
				ELSE
					pt.class_ref
			END AS class_ref,
			CASE
				WHEN level_sys_name = 'class' THEN
					NEW.name_indexed
				ELSE
					pt.class_indexed
			END AS class_indexed,
			CASE
				WHEN level_sys_name = 'sub_class' THEN
					NEW.id
				ELSE
					pt.sub_class_ref
			END AS sub_class_ref,
			CASE
				WHEN level_sys_name = 'sub_class' THEN
					NEW.name_indexed
				ELSE
					pt.sub_class_indexed
			END AS sub_class_indexed,
			CASE
				WHEN level_sys_name = 'infra_class' THEN
					NEW.id
				ELSE
					pt.infra_class_ref
			END AS infra_class_ref,
			CASE
				WHEN level_sys_name = 'infra_class' THEN
					NEW.name_indexed
				ELSE
					pt.infra_class_indexed
			END AS infra_class_indexed,
			CASE
				WHEN level_sys_name = 'super_division' THEN
					NEW.id
				ELSE
					pt.super_division_ref
			END AS super_division_ref,
			CASE
				WHEN level_sys_name = 'super_division' THEN
					NEW.name_indexed
				ELSE
					pt.super_division_indexed
			END AS super_division_indexed,
			CASE
				WHEN level_sys_name = 'division' THEN
					NEW.id
				ELSE
					pt.division_ref
			END AS division_ref,
			CASE
				WHEN level_sys_name = 'division' THEN
					NEW.name_indexed
				ELSE
					pt.division_indexed
			END AS division_indexed,
			CASE
				WHEN level_sys_name = 'sub_division' THEN
					NEW.id
				ELSE
					pt.sub_division_ref
			END AS sub_division_ref,
			CASE
				WHEN level_sys_name = 'sub_division' THEN
					NEW.name_indexed
				ELSE
					pt.sub_division_indexed
			END AS sub_division_indexed,
			CASE
				WHEN level_sys_name = 'infra_division' THEN
					NEW.id
				ELSE
					pt.infra_division_ref
			END AS infra_division_ref,
			CASE
				WHEN level_sys_name = 'infra_division' THEN
					NEW.name_indexed
				ELSE
					pt.infra_division_indexed
			END AS infra_division_indexed,
			CASE
				WHEN level_sys_name = 'super_legion' THEN
					NEW.id
				ELSE
					pt.super_legion_ref
			END AS super_legion_ref,
			CASE
				WHEN level_sys_name = 'super_legion' THEN
					NEW.name_indexed
				ELSE
					pt.super_legion_indexed
			END AS super_legion_indexed,
			CASE
				WHEN level_sys_name = 'legion' THEN
					NEW.id
				ELSE
					pt.legion_ref
			END AS legion_ref,
			CASE
				WHEN level_sys_name = 'legion' THEN
					NEW.name_indexed
				ELSE
					pt.legion_indexed
			END AS legion_indexed,
			CASE
				WHEN level_sys_name = 'sub_legion' THEN
					NEW.id
				ELSE
					pt.sub_legion_ref
			END AS sub_legion_ref,
			CASE
				WHEN level_sys_name = 'sub_legion' THEN
					NEW.name_indexed
				ELSE
					pt.sub_legion_indexed
			END AS sub_legion_indexed,
			CASE
				WHEN level_sys_name = 'infra_legion' THEN
					NEW.id
				ELSE
					pt.infra_legion_ref
			END AS infra_legion_ref,
			CASE
				WHEN level_sys_name = 'infra_legion' THEN
					NEW.name_indexed
				ELSE
					pt.infra_legion_indexed
			END AS infra_legion_indexed,
			CASE
				WHEN level_sys_name = 'super_cohort_zoology' THEN
					NEW.id
				ELSE
					pt.super_cohort_zoology_ref
			END AS super_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'super_cohort_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.super_cohort_zoology_indexed
			END AS super_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'cohort_zoology' THEN
					NEW.id
				ELSE
					pt.cohort_zoology_ref
			END AS cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'cohort_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.cohort_zoology_indexed
			END AS cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'sub_cohort_zoology' THEN
					NEW.id
				ELSE
					pt.sub_cohort_zoology_ref
			END AS sub_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'sub_cohort_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.sub_cohort_zoology_indexed
			END AS sub_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'infra_cohort_zoology' THEN
					NEW.id
				ELSE
					pt.infra_cohort_zoology_ref
			END AS infra_cohort_zoology_ref,
			CASE
				WHEN level_sys_name = 'infra_cohort_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.infra_cohort_zoology_indexed
			END AS infra_cohort_zoology_indexed,
			CASE
				WHEN level_sys_name = 'super_order' THEN
					NEW.id
				ELSE
					pt.super_order_ref
			END AS super_order_ref,
			CASE
				WHEN level_sys_name = 'super_order' THEN
					NEW.name_indexed
				ELSE
					pt.super_order_indexed
			END AS super_order_indexed,
			CASE
				WHEN level_sys_name = 'order' THEN
					NEW.id
				ELSE
					pt.order_ref
			END AS order_ref,
			CASE
				WHEN level_sys_name = 'order' THEN
					NEW.name_indexed
				ELSE
					pt.order_indexed
			END AS order_indexed,
			CASE
				WHEN level_sys_name = 'sub_order' THEN
					NEW.id
				ELSE
					pt.sub_order_ref
			END AS sub_order_ref,
			CASE
				WHEN level_sys_name = 'sub_order' THEN
					NEW.name_indexed
				ELSE
					pt.sub_order_indexed
			END AS sub_order_indexed,
			CASE
				WHEN level_sys_name = 'infra_order' THEN
					NEW.id
				ELSE
					pt.infra_order_ref
			END AS infra_order_ref,
			CASE
				WHEN level_sys_name = 'infra_order' THEN
					NEW.name_indexed
				ELSE
					pt.infra_order_indexed
			END AS infra_order_indexed,
			CASE
				WHEN level_sys_name = 'section_zoology' THEN
					NEW.id
				ELSE
					pt.section_zoology_ref
			END AS section_zoology_ref,
			CASE
				WHEN level_sys_name = 'section_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.section_zoology_indexed
			END AS section_zoology_indexed,
			CASE
				WHEN level_sys_name = 'sub_section_zoology' THEN
					NEW.id
				ELSE
					pt.sub_section_zoology_ref
			END AS sub_section_zoology_ref,
			CASE
				WHEN level_sys_name = 'sub_section_zoology' THEN
					NEW.name_indexed
				ELSE
					pt.sub_section_zoology_indexed
			END AS sub_section_zoology_indexed,
			CASE
				WHEN level_sys_name = 'super_family' THEN
					NEW.id
				ELSE
					pt.super_family_ref
			END AS super_family_ref,
			CASE
				WHEN level_sys_name = 'super_family' THEN
					NEW.name_indexed
				ELSE
					pt.super_family_indexed
			END AS super_family_indexed,
			CASE
				WHEN level_sys_name = 'family' THEN
					NEW.id
				ELSE
					pt.family_ref
			END AS family_ref,
			CASE
				WHEN level_sys_name = 'family' THEN
					NEW.name_indexed
				ELSE
					pt.family_indexed
			END AS family_indexed,
			CASE
				WHEN level_sys_name = 'sub_family' THEN
					NEW.id
				ELSE
					pt.sub_family_ref
			END AS sub_family_ref,
			CASE
				WHEN level_sys_name = 'sub_family' THEN
					NEW.name_indexed
				ELSE
					pt.sub_family_indexed
			END AS sub_family_indexed,
			CASE
				WHEN level_sys_name = 'infra_family' THEN
					NEW.id
				ELSE
					pt.infra_family_ref
			END AS infra_family_ref,
			CASE
				WHEN level_sys_name = 'infra_family' THEN
					NEW.name_indexed
				ELSE
					pt.infra_family_indexed
			END AS infra_family_indexed,
			CASE
				WHEN level_sys_name = 'super_tribe' THEN
					NEW.id
				ELSE
					pt.super_tribe_ref
			END AS super_tribe_ref,
			CASE
				WHEN level_sys_name = 'super_tribe' THEN
					NEW.name_indexed
				ELSE
					pt.super_tribe_indexed
			END AS super_tribe_indexed,
			CASE
				WHEN level_sys_name = 'tribe' THEN
					NEW.id
				ELSE
					pt.tribe_ref
			END AS tribe_ref,
			CASE
				WHEN level_sys_name = 'tribe' THEN
					NEW.name_indexed
				ELSE
					pt.tribe_indexed
			END AS tribe_indexed,
			CASE
				WHEN level_sys_name = 'sub_tribe' THEN
					NEW.id
				ELSE
					pt.sub_tribe_ref
			END AS sub_tribe_ref,
			CASE
				WHEN level_sys_name = 'sub_tribe' THEN
					NEW.name_indexed
				ELSE
					pt.sub_tribe_indexed
			END AS sub_tribe_indexed,
			CASE
				WHEN level_sys_name = 'infra_tribe' THEN
					NEW.id
				ELSE
					pt.infra_tribe_ref
			END AS infra_tribe_ref,
			CASE
				WHEN level_sys_name = 'infra_tribe' THEN
					NEW.name_indexed
				ELSE
					pt.infra_tribe_indexed
			END AS infra_tribe_indexed,
			CASE
				WHEN level_sys_name = 'genus' THEN
					NEW.id
				ELSE
					pt.genus_ref
			END AS genus_ref,
			CASE
				WHEN level_sys_name = 'genus' THEN
					NEW.name_indexed
				ELSE
					pt.genus_indexed
			END AS genus_indexed,
			CASE
				WHEN level_sys_name = 'sub_genus' THEN
					NEW.id
				ELSE
					pt.sub_genus_ref
			END AS sub_genus_ref,
			CASE
				WHEN level_sys_name = 'sub_genus' THEN
					NEW.name_indexed
				ELSE
					pt.sub_genus_indexed
			END AS sub_genus_indexed,
			CASE
				WHEN level_sys_name = 'section_botany' THEN
					NEW.id
				ELSE
					pt.section_botany_ref
			END AS section_botany_ref,
			CASE
				WHEN level_sys_name = 'section_botany' THEN
					NEW.name_indexed
				ELSE
					pt.section_botany_indexed
			END AS section_botany_indexed,
			CASE
				WHEN level_sys_name = 'sub_section_botany' THEN
					NEW.id
				ELSE
					pt.sub_section_botany_ref
			END AS sub_section_botany_ref,
			CASE
				WHEN level_sys_name = 'sub_section_botany' THEN
					NEW.name_indexed
				ELSE
					pt.sub_section_botany_indexed
			END AS sub_section_botany_indexed,
			CASE
				WHEN level_sys_name = 'serie' THEN
					NEW.id
				ELSE
					pt.serie_ref
			END AS serie_ref,
			CASE
				WHEN level_sys_name = 'serie' THEN
					NEW.name_indexed
				ELSE
					pt.serie_indexed
			END AS serie_indexed,
			CASE
				WHEN level_sys_name = 'sub_serie' THEN
					NEW.id
				ELSE
					pt.sub_serie_ref
			END AS sub_serie_ref,
			CASE
				WHEN level_sys_name = 'sub_serie' THEN
					NEW.name_indexed
				ELSE
					pt.sub_serie_indexed
			END AS sub_serie_indexed,
			CASE
				WHEN level_sys_name = 'super_species' THEN
					NEW.id
				ELSE
					pt.super_species_ref
			END AS super_species_ref,
			CASE
				WHEN level_sys_name = 'super_species' THEN
					NEW.name_indexed
				ELSE
					pt.super_species_indexed
			END AS super_species_indexed,
			CASE
				WHEN level_sys_name = 'species' THEN
					NEW.id
				ELSE
					pt.species_ref
			END AS species_ref,
			CASE
				WHEN level_sys_name = 'species' THEN
					NEW.name_indexed
				ELSE
					pt.species_indexed
			END AS species_indexed,
			CASE
				WHEN level_sys_name = 'sub_species' THEN
					NEW.id
				ELSE
					pt.sub_species_ref
			END AS sub_species_ref,
			CASE
				WHEN level_sys_name = 'sub_species' THEN
					NEW.name_indexed
				ELSE
					pt.sub_species_indexed
			END AS sub_species_indexed,
			CASE
				WHEN level_sys_name = 'variety' THEN
					NEW.id
				ELSE
					pt.variety_ref
			END AS variety_ref,
			CASE
				WHEN level_sys_name = 'variety' THEN
					NEW.name_indexed
				ELSE
					pt.variety_indexed
			END AS variety_indexed,
			CASE
				WHEN level_sys_name = 'sub_variety' THEN
					NEW.id
				ELSE
					pt.sub_variety_ref
			END AS sub_variety_ref,
			CASE
				WHEN level_sys_name = 'sub_variety' THEN
					NEW.name_indexed
				ELSE
					pt.sub_variety_indexed
			END AS sub_variety_indexed,
			CASE
				WHEN level_sys_name = 'form' THEN
					NEW.id
				ELSE
					pt.form_ref
			END AS form_ref,
			CASE
				WHEN level_sys_name = 'form' THEN
					NEW.name_indexed
				ELSE
					pt.form_indexed
			END AS form_indexed,
			CASE
				WHEN level_sys_name = 'sub_form' THEN
					NEW.id
				ELSE
					pt.sub_form_ref
			END AS sub_form_ref,
			CASE
				WHEN level_sys_name = 'sub_form' THEN
					NEW.name_indexed
				ELSE
					pt.sub_form_indexed
			END AS sub_form_indexed,
			CASE
				WHEN level_sys_name = 'abberans' THEN
					NEW.id
				ELSE
					pt.abberans_ref
			END AS abberans_ref,
			CASE
				WHEN level_sys_name = 'abberans' THEN
					NEW.name_indexed
				ELSE
					pt.abberans_indexed
			END AS abberans_indexed
		INTO 
			NEW.domain_ref,
			NEW.domain_indexed,
			NEW.kingdom_ref,
			NEW.kingdom_indexed,
			NEW.super_phylum_ref,
			NEW.super_phylum_indexed,
			NEW.phylum_ref,
			NEW.phylum_indexed,
			NEW.sub_phylum_ref,
			NEW.sub_phylum_indexed,
			NEW.infra_phylum_ref,
			NEW.infra_phylum_indexed,
			NEW.super_cohort_botany_ref,
			NEW.super_cohort_botany_indexed,
			NEW.cohort_botany_ref,
			NEW.cohort_botany_indexed,
			NEW.sub_cohort_botany_ref,
			NEW.sub_cohort_botany_indexed,
			NEW.infra_cohort_botany_ref,
			NEW.infra_cohort_botany_indexed,
			NEW.super_class_ref,
			NEW.super_class_indexed,
			NEW.class_ref,
			NEW.class_indexed,
			NEW.sub_class_ref,
			NEW.sub_class_indexed,
			NEW.infra_class_ref,
			NEW.infra_class_indexed,
			NEW.super_division_ref,
			NEW.super_division_indexed,
			NEW.division_ref,
			NEW.division_indexed,
			NEW.sub_division_ref,
			NEW.sub_division_indexed,
			NEW.infra_division_ref,
			NEW.infra_division_indexed,
			NEW.super_legion_ref,
			NEW.super_legion_indexed,
			NEW.legion_ref,
			NEW.legion_indexed,
			NEW.sub_legion_ref,
			NEW.sub_legion_indexed,
			NEW.infra_legion_ref,
			NEW.infra_legion_indexed,
			NEW.super_cohort_zoology_ref,
			NEW.super_cohort_zoology_indexed,
			NEW.cohort_zoology_ref,
			NEW.cohort_zoology_indexed,
			NEW.sub_cohort_zoology_ref,
			NEW.sub_cohort_zoology_indexed,
			NEW.infra_cohort_zoology_ref,
			NEW.infra_cohort_zoology_indexed,
			NEW.super_order_ref,
			NEW.super_order_indexed,
			NEW.order_ref,
			NEW.order_indexed,
			NEW.sub_order_ref,
			NEW.sub_order_indexed,
			NEW.infra_order_ref,
			NEW.infra_order_indexed,
			NEW.section_zoology_ref,
			NEW.section_zoology_indexed,
			NEW.sub_section_zoology_ref,
			NEW.sub_section_zoology_indexed,
			NEW.super_family_ref,
			NEW.super_family_indexed,
			NEW.family_ref,
			NEW.family_indexed,
			NEW.sub_family_ref,
			NEW.sub_family_indexed,
			NEW.infra_family_ref,
			NEW.infra_family_indexed,
			NEW.super_tribe_ref,
			NEW.super_tribe_indexed,
			NEW.tribe_ref,
			NEW.tribe_indexed,
			NEW.sub_tribe_ref,
			NEW.sub_tribe_indexed,
			NEW.infra_tribe_ref,
			NEW.infra_tribe_indexed,
			NEW.genus_ref,
			NEW.genus_indexed,
			NEW.sub_genus_ref,
			NEW.sub_genus_indexed,
			NEW.section_botany_ref,
			NEW.section_botany_indexed,
			NEW.sub_section_botany_ref,
			NEW.sub_section_botany_indexed,
			NEW.serie_ref,
			NEW.serie_indexed,
			NEW.sub_serie_ref,
			NEW.sub_serie_indexed,
			NEW.super_species_ref,
			NEW.super_species_indexed,
			NEW.species_ref,
			NEW.species_indexed,
			NEW.sub_species_ref,
			NEW.sub_species_indexed,
			NEW.variety_ref,
			NEW.variety_indexed,
			NEW.sub_variety_ref,
			NEW.sub_variety_indexed,
			NEW.form_ref,
			NEW.form_indexed,
			NEW.sub_form_ref,
			NEW.sub_form_indexed,
			NEW.abberans_ref,
			NEW.abberans_indexed
		FROM taxonomy AS pt
		WHERE pt.id = NEW.parent_ref;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/***
* Trigger function fct_cpy_cascade_children_indexed_names
* Update the corresponding givenlevel_indexed and givenlevel_ref of related children when name of a catalogue unit have been updated
*/
CREATE OR REPLACE FUNCTION fct_cpy_cascade_children_indexed_names (referenced_relation varchar, new_level_ref template_classifications.level_ref%TYPE, new_name_indexed template_classifications.name_indexed%TYPE, new_id integer) RETURNS boolean
AS $$
DECLARE
	level_prefix catalogue_levels.level_sys_name%TYPE;
	response boolean default false;
BEGIN
	SELECT level_sys_name INTO level_prefix FROM catalogue_levels WHERE id = new_level_ref;
	IF level_prefix IS NOT NULL THEN
		EXECUTE 'UPDATE ' || 
			quote_ident(referenced_relation) || 
			' SET ' || quote_ident(level_prefix || '_indexed') || ' = ' || quote_literal(new_name_indexed) || 
			' WHERE ' || quote_ident(level_prefix || '_ref') || ' = ' || new_id || 
			'   AND ' || quote_ident('id') || ' <> ' || new_id ;
		response := true;
	END IF;
	return response;
EXCEPTION
	WHEN OTHERS THEN
		return response;
END;
$$ LANGUAGE plpgsql;

/***
* Trigger function fct_cpy_fullToIndex
* Call the fulltoIndex function for different tables
*/
CREATE OR REPLACE FUNCTION fct_cpy_fullToIndex() RETURNS trigger
AS $$
DECLARE
	oldValue varchar;
	oldCodePrefix varchar;
	oldCode varchar;
	oldCodeSuffix varchar;
BEGIN
	IF TG_OP = 'UPDATE' THEN
		IF TG_TABLE_NAME = 'catalogue_properties' THEN
			NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
			NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
			NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
			NEW.property_qualifier_indexed := COALESCE(fullToIndex(NEW.property_qualifier),'');
		ELSIF TG_TABLE_NAME = 'chronostratigraphy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'expeditions' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'habitats' THEN
			NEW.code_indexed := fullToIndex(NEW.code);
		ELSIF TG_TABLE_NAME = 'identifications' THEN
			NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
			NEW.formule_indexed := fullToIndex(NEW.formule);
		ELSIF TG_TABLE_NAME = 'multimedia' THEN
			NEW.title_indexed := fullToIndex(NEW.title);
		ELSIF TG_TABLE_NAME = 'multimedia_keywords' THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		ELSIF TG_TABLE_NAME = 'people' THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		ELSIF TG_TABLE_NAME = 'codes' THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		ELSIF TG_TABLE_NAME = 'tag_groups' THEN
			NEW.group_name_indexed := fullToIndex(NEW.group_name);
			NEW.sub_group_name_indexed := fullToIndex(NEW.sub_group_name);
		ELSIF TG_TABLE_NAME = 'tags' THEN
			NEW.label_indexed := fullToIndex(NEW.label);
		ELSIF TG_TABLE_NAME = 'taxonomy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'classification_keywords' THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		ELSIF TG_TABLE_NAME = 'users' THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		ELSIF TG_TABLE_NAME = 'class_vernacular_names' THEN
			NEW.community_indexed := fullToIndex(NEW.community);
		ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'igs' THEN
			NEW.ig_num_indexed := fullToIndex(NEW.ig_num);
		END IF;	
	ELSIF TG_OP = 'INSERT' THEN
		IF TG_TABLE_NAME = 'catalogue_properties' THEN
			NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
			NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
			NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
			NEW.property_qualifier_indexed := COALESCE(fullToIndex(NEW.property_qualifier),'');
		ELSIF TG_TABLE_NAME = 'chronostratigraphy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'expeditions' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'habitats' THEN
			NEW.code_indexed := fullToIndex(NEW.code);
		ELSIF TG_TABLE_NAME = 'identifications' THEN
			NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
			NEW.formule_indexed := fullToIndex(NEW.formule);
		ELSIF TG_TABLE_NAME = 'multimedia' THEN
			NEW.title_indexed := fullToIndex(NEW.title);
		ELSIF TG_TABLE_NAME = 'multimedia_keywords' THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		ELSIF TG_TABLE_NAME = 'people' THEN
			NEW.formated_name_indexed := COALESCE(fullToIndex(NEW.formated_name),'');
		ELSIF TG_TABLE_NAME = 'codes' THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		ELSIF TG_TABLE_NAME = 'tag_groups' THEN
			NEW.group_name_indexed := fullToIndex(NEW.group_name);
			NEW.sub_group_name_indexed := fullToIndex(NEW.sub_group_name);
		ELSIF TG_TABLE_NAME = 'tags' THEN
			NEW.label_indexed := fullToIndex(NEW.label);
		ELSIF TG_TABLE_NAME = 'taxonomy' THEN
			NEW.name_indexed := to_tsvector('simple', NEW.name);
			NEW.name_order_by := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'classification_keywords' THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		ELSIF TG_TABLE_NAME = 'users' THEN
			NEW.formated_name_indexed := COALESCE(fullToIndex(NEW.formated_name),'');
		ELSIF TG_TABLE_NAME = 'class_vernacular_names' THEN
			NEW.community_indexed := fullToIndex(NEW.community);
		ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'igs' THEN
			NEW.ig_num_indexed := fullToIndex(NEW.ig_num);
		END IF;	
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/***
* function fct_chk_collectionsInstitutionIsMoral
* Check if an institution referenced in collections is moral
* return Boolean
*/
CREATE OR REPLACE FUNCTION fct_chk_PeopleIsMoral(people_ref people.id%TYPE) RETURNS boolean
AS $$
DECLARE
	is_physical boolean;
BEGIN
	SELECT NOT people.is_physical INTO is_physical FROM people WHERE people.id=people_ref;
	return is_physical;
END;
$$ LANGUAGE plpgsql;

/***
* fct_clr_specialstatus
* Check the type(special status) on specimen_individuals and update the search and group type
* to be conform to the std
*/
CREATE OR REPLACE FUNCTION fct_clr_specialstatus() RETURNS TRIGGER
AS $$
BEGIN

	-- IF Type not changed
	IF TG_OP = 'UPDATE' THEN
		IF OLD.type = NEW.type THEN
			RETURN NEW;
		END IF;
	END IF;
	
	IF NEW.type = 'specimen' THEN
		NEW.type_search := '';
		NEW.type_group := '';
	END IF;
	
	IF NEW.type = 'type' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	END IF;	
	
	IF NEW.type = 'subtype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	END IF;

	IF NEW.type = 'allotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'allotype';
	END IF;

	IF NEW.type = 'cotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'syntype';
	END IF;

	IF NEW.type = 'genotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	END IF;

	IF NEW.type = 'holotype' THEN
		NEW.type_search := 'holotype';
		NEW.type_group := 'holotype';
	END IF;

	IF NEW.type = 'hypotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'hypotype';
	END IF;

	IF NEW.type = 'lectotype' THEN
		NEW.type_search := 'lectotype';
		NEW.type_group := 'lectotype';
	END IF;

	IF NEW.type = 'locotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'locotype';
	END IF;

	IF NEW.type = 'neallotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	END IF;

	IF NEW.type = 'neotype' THEN
		NEW.type_search := 'neotype';
		NEW.type_group := 'neotype';
	END IF;
	
	IF NEW.type = 'paralectotype' THEN
		NEW.type_search := 'paralectotype';
		NEW.type_group := 'paralectotype';
	END IF;

	IF NEW.type = 'paratype' THEN
		NEW.type_search := 'paratype';
		NEW.type_group := 'paratype';
	END IF;

	IF NEW.type = 'plastotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'plastotype';
	END IF;
	
	IF NEW.type = 'plesiotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'plesiotype';
	END IF;

	IF NEW.type = 'syntype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'syntype';
	END IF;
		
	IF NEW.type = 'topotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'topotype';
	END IF;
	
	IF NEW.type = 'type in litteris' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type in litteris';
	END IF;
	
	RETURN NEW;
EXCEPTION
	WHEN RAISE_EXCEPTION THEN
		return NULL;
END;
$$ LANGUAGE plpgsql;

/**
fct_compose_timestamp
Compose A timestamp with default value
-1 or null for hour, minute or second will take 0
0 or null for day, month or year will take 1
*/
CREATE OR REPLACE FUNCTION fct_compose_timestamp(day integer, month integer, year integer, hour integer, minute integer, second integer) RETURNS timestamp
AS $$
DECLARE
	nday integer;
	nmonth integer;
	nyear integer;
	nhour integer;
	nminute integer;
	nsecond integer;
	stamp_string varchar default '';
BEGIN

	IF day = 0 OR day IS NULL THEN
		nday := 1;
	ELSE
		nday := day;
	END IF;

	IF month = 0 OR month IS NULL THEN
		nmonth := 1;
	ELSE
		nmonth := month;
	END IF;
	
	IF year = 0 OR year IS NULL THEN
		nyear := 1;
	ELSE
		nyear := year;
	END IF;
			
	IF hour = -1 OR hour IS NULL THEN
		nhour := 0;
	ELSE
		nhour := hour;
	END IF;

	IF minute = -1 OR minute IS NULL THEN
		nminute := 0;
	ELSE
		nminute := day;
	END IF;

	IF second = -1 OR second IS NULL THEN
		nsecond := 0;
	ELSE
		nsecond := second;
	END IF;
	
	stamp_string := ''|| to_char(nyear,'0000') ||'-'|| nmonth ||'-'|| nday || ' ' ||
			to_char(nhour,'FM00') ||':'|| to_char(nminute,'FM00') ||':'|| to_char(nsecond,'FM00');
	
	RETURN stamp_string::TIMESTAMP;
END;
$$ LANGUAGE plpgsql;

/**
*fct_cpy_name_updt_impact_children
*When name of a unit is updated, impact the <given-level>_indexed field of related children.
*/

CREATE OR REPLACE FUNCTION fct_cpy_name_updt_impact_children() RETURNS trigger
AS $$
DECLARE
	level_prefix catalogue_levels.level_sys_name%TYPE;
BEGIN
	IF NEW.name_indexed <> OLD.name_indexed THEN
		IF TG_TABLE_NAME = 'chronostratigraphy' THEN
			SELECT
				CASE
					WHEN level_sys_name = 'eon' THEN
						NEW.name_indexed
					ELSE
						NEW.eon_indexed
				END as eon_indexed,
				CASE
                                        WHEN level_sys_name = 'era' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.era_indexed
                                END as era_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_era' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_era_indexed
                                END as sub_era_indexed,
				CASE
                                        WHEN level_sys_name = 'system' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.system_indexed
                                END as system_indexed,
				CASE
                                        WHEN level_sys_name = 'serie' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.serie_indexed
                                END as serie_indexed,
				CASE
                                        WHEN level_sys_name = 'stage' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.stage_indexed
                                END as stage_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_stage' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_stage_indexed
                                END as sub_stage_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_level_1' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_level_1_indexed
                                END as sub_level_1_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_level_2' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_level_2_indexed
                                END as sub_level_2_indexed
			INTO
				NEW.eon_indexed,
				NEW.era_indexed,
				NEW.sub_era_indexed,
				NEW.system_indexed,
				NEW.serie_indexed,
				NEW.stage_indexed,
				NEW.sub_stage_indexed,
				NEW.sub_level_1_indexed,
				NEW.sub_level_2_indexed
			FROM catalogue_levels as cl 
			WHERE cl.id = NEW.level_ref;
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			SELECT
				CASE
					WHEN level_sys_name = 'group' THEN
						NEW.name_indexed
					ELSE
						NEW.group_indexed
				END as group_indexed,
				CASE
                                        WHEN level_sys_name = 'formation' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.formation_indexed
                                END as formation_indexed,
				CASE
                                        WHEN level_sys_name = 'member' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.member_indexed
                                END as member_indexed,
				CASE
                                        WHEN level_sys_name = 'layer' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.layer_indexed
                                END as layer_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_level_1' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_level_1_indexed
                                END as sub_level_1_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_level_2' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_level_2_indexed
                                END as sub_level_2_indexed
			INTO
				NEW.group_indexed,
				NEW.formation_indexed,
				NEW.member_indexed,
				NEW.layer_indexed,
				NEW.sub_level_1_indexed,
				NEW.sub_level_2_indexed
			FROM catalogue_levels
			WHERE id = NEW.level_ref;
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			SELECT
				CASE
					WHEN level_sys_name = 'unit_class' THEN
						NEW.name_indexed
					ELSE
						NEW.unit_class_indexed
				END as unit_class_indexed,
				CASE
                                        WHEN level_sys_name = 'unit_division' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.unit_division_indexed
                                END as unit_division_indexed,
				CASE
                                        WHEN level_sys_name = 'unit_family' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.unit_family_indexed
                                END as unit_family_indexed,
				CASE
                                        WHEN level_sys_name = 'unit_group' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.unit_group_indexed
                                END as unit_group_indexed,
				CASE
                                        WHEN level_sys_name = 'unit_variety' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.unit_variety_indexed
                                END as unit_variety_indexed
			INTO
				NEW.unit_class_indexed,
				NEW.unit_division_indexed,
				NEW.unit_family_indexed,
				NEW.unit_group_indexed,
				NEW.unit_variety_indexed
			FROM catalogue_levels
			WHERE id = NEW.level_ref;
		ELSIF TG_TABLE_NAME = 'taxonomy' THEN
			SELECT
				CASE
					WHEN level_sys_name = 'domain' THEN
						NEW.name_indexed
					ELSE
						NEW.domain_indexed
				END as domain_indexed,
				CASE
                                        WHEN level_sys_name = 'kingdom' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.kingdom_indexed
                                END as kingdom_indexed,
				CASE
                                        WHEN level_sys_name = 'super_phylum' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_phylum_indexed
                                END as super_phylum_indexed,
				CASE
                                        WHEN level_sys_name = 'phylum' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.phylum_indexed
                                END as phylum_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_phylum' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_phylum_indexed
                                END as sub_phylum_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_phylum' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_phylum_indexed
                                END as infra_phylum_indexed,
				CASE
                                        WHEN level_sys_name = 'super_cohort_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_cohort_botany_indexed
                                END as super_cohort_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'cohort_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.cohort_botany_indexed
                                END as cohort_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_cohort_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_cohort_botany_indexed
                                END as sub_cohort_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_cohort_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_cohort_botany_indexed
                                END as infra_cohort_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'super_class' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_class_indexed
                                END as super_class_indexed,
				CASE
                                        WHEN level_sys_name = 'class' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.class_indexed
                                END as class_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_class' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_class_indexed
                                END as sub_class_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_class' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_class_indexed
                                END as infra_class_indexed,
				CASE
                                        WHEN level_sys_name = 'super_division' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_division_indexed
                                END as super_division_indexed,
				CASE
                                        WHEN level_sys_name = 'division' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.division_indexed
                                END as division_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_division' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_division_indexed
                                END as sub_division_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_division' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_division_indexed
                                END as infra_division_indexed,
				CASE
                                        WHEN level_sys_name = 'super_legion' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_legion_indexed
                                END as super_legion_indexed,
				CASE
                                        WHEN level_sys_name = 'legion' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.legion_indexed
                                END as legion_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_legion' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_legion_indexed
                                END as sub_legion_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_legion' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_legion_indexed
                                END as infra_legion_indexed,
				CASE
                                        WHEN level_sys_name = 'super_cohort_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_cohort_zoology_indexed
                                END as super_cohort_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'cohort_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.cohort_zoology_indexed
                                END as cohort_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_cohort_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_cohort_zoology_indexed
                                END as sub_cohort_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_cohort_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_cohort_zoology_indexed
                                END as infra_cohort_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'super_order' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_order_indexed
                                END as super_order_indexed,
				CASE
                                        WHEN level_sys_name = 'order' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.order_indexed
                                END as order_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_order' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_order_indexed
                                END as sub_order_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_order' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_order_indexed
                                END as infra_order_indexed,
				CASE
                                        WHEN level_sys_name = 'section_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.section_zoology_indexed
                                END as section_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_section_zoology' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_section_zoology_indexed
                                END as sub_section_zoology_indexed,
				CASE
                                        WHEN level_sys_name = 'super_family' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_family_indexed
                                END as super_family_indexed,
				CASE
                                        WHEN level_sys_name = 'family' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.family_indexed
                                END as family_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_family' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_family_indexed
                                END as sub_family_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_family' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_family_indexed
                                END as infra_family_indexed,
				CASE
                                        WHEN level_sys_name = 'super_tribe' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_tribe_indexed
                                END as super_tribe_indexed,
				CASE
                                        WHEN level_sys_name = 'tribe' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.tribe_indexed
                                END as tribe_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_tribe' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_tribe_indexed
                                END as sub_tribe_indexed,
				CASE
                                        WHEN level_sys_name = 'infra_tribe' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.infra_tribe_indexed
                                END as infra_tribe_indexed,
				CASE
                                        WHEN level_sys_name = 'genus' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.genus_indexed
                                END as genus_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_genus' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_genus_indexed
                                END as sub_genus_indexed,
				CASE
                                        WHEN level_sys_name = 'section_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.section_botany_indexed
                                END as section_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_section_botany' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_section_botany_indexed
                                END as sub_section_botany_indexed,
				CASE
                                        WHEN level_sys_name = 'serie' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.serie_indexed
                                END as serie_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_serie' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_serie_indexed
                                END as sub_serie_indexed,
				CASE
                                        WHEN level_sys_name = 'super_species' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.super_species_indexed
                                END as super_species_indexed,
				CASE
                                        WHEN level_sys_name = 'species' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.species_indexed
                                END as species_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_species' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_species_indexed
                                END as sub_species_indexed,
				CASE
                                        WHEN level_sys_name = 'variety' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.variety_indexed
                                END as variety_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_variety' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_variety_indexed
                                END as sub_variety_indexed,
				CASE
                                        WHEN level_sys_name = 'form' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.form_indexed
                                END as form_indexed,
				CASE
                                        WHEN level_sys_name = 'sub_form' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.sub_form_indexed
                                END as sub_form_indexed,
				CASE
                                        WHEN level_sys_name = 'abberans' THEN
                                                NEW.name_indexed
                                        ELSE
                                                NEW.abberans_indexed
                                END as abberans_indexed
			INTO
				NEW.domain_indexed,
				NEW.kingdom_indexed,
				NEW.super_phylum_indexed,
				NEW.phylum_indexed,
				NEW.sub_phylum_indexed,
				NEW.infra_phylum_indexed,
				NEW.super_cohort_botany_indexed,
				NEW.cohort_botany_indexed,
				NEW.sub_cohort_botany_indexed,
				NEW.infra_cohort_botany_indexed,
				NEW.super_class_indexed,
				NEW.class_indexed,
				NEW.sub_class_indexed,
				NEW.infra_class_indexed,
				NEW.super_division_indexed,
				NEW.division_indexed,
				NEW.sub_division_indexed,
				NEW.infra_division_indexed,
				NEW.super_legion_indexed,
				NEW.legion_indexed,
				NEW.sub_legion_indexed,
				NEW.infra_legion_indexed,
				NEW.super_cohort_zoology_indexed,
				NEW.cohort_zoology_indexed,
				NEW.sub_cohort_zoology_indexed,
				NEW.infra_cohort_zoology_indexed,
				NEW.super_order_indexed,
				NEW.order_indexed,
				NEW.sub_order_indexed,
				NEW.infra_order_indexed,
				NEW.section_zoology_indexed,
				NEW.sub_section_zoology_indexed,
				NEW.super_family_indexed,
				NEW.family_indexed,
				NEW.sub_family_indexed,
				NEW.infra_family_indexed,
				NEW.super_tribe_indexed,
				NEW.tribe_indexed,
				NEW.sub_tribe_indexed,
				NEW.infra_tribe_indexed,
				NEW.genus_indexed,
				NEW.sub_genus_indexed,
				NEW.section_botany_indexed,
				NEW.sub_section_botany_indexed,
				NEW.serie_indexed,
				NEW.sub_serie_indexed,
				NEW.super_species_indexed,
				NEW.species_indexed,
				NEW.sub_species_indexed,
				NEW.variety_indexed,
				NEW.sub_variety_indexed,
				NEW.form_indexed,
				NEW.sub_form_indexed,
				NEW.abberans_indexed
			FROM catalogue_levels
			WHERE id = NEW.level_ref;
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			SELECT
				CASE
					WHEN level_sys_name = 'unit_main_group' THEN
						NEW.name_indexed
					ELSE
						NEW.unit_main_group_indexed
				END as unit_main_group_indexed,
				CASE
					WHEN level_sys_name = 'unit_group' THEN
						NEW.name_indexed
					ELSE
						NEW.unit_group_indexed
				END as unit_group_indexed,
				CASE
					WHEN level_sys_name = 'unit_sub_group' THEN
						NEW.name_indexed
					ELSE
						NEW.unit_sub_group_indexed
				END as unit_sub_group_indexed,
				CASE
					WHEN level_sys_name = 'unit_rock' THEN
						NEW.name_indexed
					ELSE
						NEW.unit_rock_indexed
				END as unit_rock_indexed
			INTO
				NEW.unit_main_group_indexed,
				NEW.unit_group_indexed,
				NEW.unit_sub_group_indexed,
				NEW.unit_rock_indexed
			FROM catalogue_levels
			WHERE id = NEW.level_ref;
		END IF;
		IF NOT fct_cpy_cascade_children_indexed_names (TG_TABLE_NAME::varchar, NEW.level_ref::integer, NEW.name_indexed::tsvector, NEW.id::integer) THEN
			RAISE EXCEPTION 'Impossible to impact children names';
		END IF;
	END IF;
	RETURN NEW;
/*EXCEPTION
	WHEN OTHERS THEN
		RETURN OLD;
*/
END;
$$ LANGUAGE plpgsql;

/* 
fct_compose_date
Compose a date with default value call compose_timestamp
*/
CREATE OR REPLACE FUNCTION fct_compose_date(day integer, month integer, year integer) RETURNS date
AS $$
BEGIN
	RETURN fct_compose_timestamp(day, month, year, null, null, null)::date;
END;
$$ LANGUAGE plpgsql;

/**
* fct_clear_referencedRecord
* Clear referenced record id for a table on delete record
*/
CREATE OR REPLACE FUNCTION fct_clear_referencedRecord() RETURNS TRIGGER
AS $$
BEGIN
	DELETE FROM catalogue_people WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM comments WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM catalogue_properties WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM identifications WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM class_vernacular_names WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM classification_synonymies WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM classification_keywords WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM record_visibilities WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM users_workflow WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM collection_maintenance WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM associated_multimedia WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM codes WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	DELETE FROM insurances WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;
	RETURN OLD;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_remove_array_elem(IN in_array anyarray, IN elem anyelement,OUT out_array anyarray)
AS $$
BEGIN
	SELECT array(select s FROM fct_explode_array (in_array)  as s WHERE s != elem) INTO out_array;
END;
$$
LANGUAGE plpgsql immutable;

CREATE OR REPLACE FUNCTION fct_explode_array(in_array anyarray) returns setof anyelement as
$$
    select ($1)[s] from generate_series(1,array_upper($1, 1)) as s;
$$
LANGUAGE sql immutable;


CREATE OR REPLACE FUNCTION fct_array_find(IN in_array anyarray, IN elem anyelement,OUT item_order integer)
AS $$
    select s from generate_series(1,array_upper($1, 1)) as s where $1[s] = $2;
$$
LANGUAGE sql immutable;

CREATE OR REPLACE FUNCTION fct_array_find(IN in_array varchar, IN elem anyelement,OUT item_order integer)
AS $$
    select fct_array_find(string_to_array($1,','), $2::text);
$$
LANGUAGE sql immutable;


/**
fct_cpy_toFullText
Copy the Full_text version of some fields
Use language if av. or 'simple' if not
*/
CREATE OR REPLACE FUNCTION fct_cpy_toFullText() RETURNS TRIGGER
AS
$$
BEGIN
	IF TG_OP = 'INSERT' THEN
		IF TG_TABLE_NAME = 'comments' THEN
			NEW.comment_ts := to_tsvector(NEW.comment_language_full_text::regconfig, NEW.comment);
		ELSEIF TG_TABLE_NAME = 'identifications' THEN
			NEW.value_defined_ts := to_tsvector('simple', NEW.value_defined);
		ELSEIF TG_TABLE_NAME = 'people_addresses' THEN
			NEW.address_parts_ts := to_tsvector('simple',
							COALESCE(NEW.entry,'') || ' ' || COALESCE(NEW.po_box,'') || ' ' || COALESCE(NEW.extended_address,'')
							|| ' ' || COALESCE(NEW.locality,'')  || ' ' || COALESCE(NEW.region,'')
							|| ' ' || COALESCE(NEW.zip_code,'') || ' ' || COALESCE(NEW.country,'')
					);
		ELSEIF TG_TABLE_NAME = 'users_addresses' THEN
			NEW.address_parts_ts := to_tsvector('simple',
							COALESCE(NEW.entry,'') || ' ' || COALESCE(NEW.po_box,'') || ' ' || COALESCE(NEW.extended_address,'')
							|| ' ' || COALESCE(NEW.locality,'')  || ' ' || COALESCE(NEW.region,'')
							|| ' ' || COALESCE(NEW.zip_code,'') || ' ' || COALESCE(NEW.country,'')
					);
		ELSEIF TG_TABLE_NAME = 'multimedia' THEN
			NEW.descriptive_ts := to_tsvector(NEW.descriptive_language_full_text::regconfig, NEW.title ||' '|| NEW.subject);
		ELSEIF TG_TABLE_NAME = 'collection_maintenance' THEN
			NEW.description_ts := to_tsvector(NEW.language_full_text::regconfig,NEW.description);
		ELSEIF TG_TABLE_NAME = 'expeditions' THEN
			NEW.name_ts := to_tsvector(NEW.name_language_full_text::regconfig, NEW.name);
		ELSEIF TG_TABLE_NAME = 'habitats' THEN
			NEW.description_ts := to_tsvector(NEW.description_language_full_text::regconfig, NEW.description);
		ELSEIF TG_TABLE_NAME = 'vernacular_names' THEN
			NEW.name_ts := to_tsvector('simple', NEW.name);
		END IF;
	ELSE
		IF TG_TABLE_NAME = 'comments' THEN
			IF OLD.comment != NEW.comment OR OLD.comment_language_full_text != NEW.comment_language_full_text THEN
				NEW.comment_ts := to_tsvector(NEW.comment_language_full_text::regconfig, NEW.comment);
			END IF;
		ELSEIF TG_TABLE_NAME = 'identifications' THEN
			IF OLD.value_defined != NEW.value_defined THEN 
				NEW.value_defined_ts := to_tsvector('simple', NEW.value_defined);
			END IF;
		ELSEIF TG_TABLE_NAME = 'people_addresses' THEN
				NEW.address_parts_ts := to_tsvector('simple',
							COALESCE(NEW.entry,'') || ' ' || COALESCE(NEW.po_box,'') || ' ' || COALESCE(NEW.extended_address,'')
							|| ' ' || COALESCE(NEW.locality,'')  || ' ' || COALESCE(NEW.region,'')
							|| ' ' || COALESCE(NEW.zip_code,'') || ' ' || COALESCE(NEW.country,'')
					);
		ELSEIF TG_TABLE_NAME = 'users_addresses' THEN
				NEW.address_parts_ts := to_tsvector('simple', 
							COALESCE(NEW.entry,'') || ' ' || COALESCE(NEW.po_box,'') || ' ' || COALESCE(NEW.extended_address,'')
							|| ' ' || COALESCE(NEW.locality,'')  || ' ' || COALESCE(NEW.region,'')
							|| ' ' || COALESCE(NEW.zip_code,'') || ' ' || COALESCE(NEW.country,'')
					);
		ELSEIF TG_TABLE_NAME = 'multimedia' THEN
			IF OLD.title != NEW.title OR  OLD.subject != NEW.subject OR OLD.descriptive_language_full_text != NEW.descriptive_language_full_text THEN
				NEW.descriptive_ts := to_tsvector(NEW.descriptive_language_full_text::regconfig, NEW.title ||' '|| NEW.subject);
			END IF;
		ELSEIF TG_TABLE_NAME = 'collection_maintenance' THEN
			IF OLD.description != NEW.description OR OLD.language_full_text != NEW.language_full_text THEN
				NEW.description_ts := to_tsvector(NEW.language_full_text::regconfig, NEW.description);
			END IF;
		ELSEIF TG_TABLE_NAME = 'expeditions' THEN
			IF OLD.name != NEW.name OR OLD.name_language_full_text != NEW.name_language_full_text THEN
				NEW.name_ts := to_tsvector(NEW.name_language_full_text::regconfig, NEW.name);
			END IF;
		ELSEIF TG_TABLE_NAME = 'habitats' THEN
			IF OLD.description != NEW.descriptiont OR OLD.description_language_full_text != NEW.description_language_full_text THEN
				NEW.description_ts := to_tsvector(NEW.description_language_full_text::regconfig, NEW.description);
			END IF;
		ELSEIF TG_TABLE_NAME = 'vernacular_names' THEN
			IF OLD.name != NEW.name OR OLD.country_language_full_text != NEW.country_language_full_text THEN
				NEW.name_ts := to_tsvector('simple', NEW.name);
			END IF;
		END IF;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/**
* fct_chk_possible_upper_levels
* When inserting or updating a hierarchical unit, checks, considering parent level, that unit level is ok (depending on definitions given in possible_upper_levels_table)
*/
CREATE OR REPLACE FUNCTION fct_chk_possible_upper_level (referenced_relation varchar, new_parent_ref template_classifications.parent_ref%TYPE, new_level_ref template_classifications.level_ref%TYPE, new_id integer) RETURNS boolean
AS $$
DECLARE
	response boolean default false;
BEGIN
	IF new_id = 0 OR (new_parent_ref = 0 AND new_level_ref IN (1, 55, 64, 70, 75)) THEN
		response := true;
	ELSE
		EXECUTE 'select count(*)::integer::boolean ' ||
			'from possible_upper_levels ' ||
			'where level_ref = ' || new_level_ref || 
			'  and level_upper_ref = (select level_ref from ' || quote_ident(referenced_relation) || ' where id = ' || new_parent_ref || ')'
		INTO response;
	END IF;
	RETURN response;
EXCEPTION
	WHEN OTHERS THEN
		RETURN response;
END;
$$ LANGUAGE plpgsql;

/**
fct_cas_userType
Copy the new dbuser type if it's changed
users db_user_type
*/
CREATE OR REPLACE FUNCTION fct_cas_userType() RETURNS TRIGGER
AS $$
DECLARE
	still_mgr boolean;
BEGIN

	IF NEW.db_user_type = OLD.db_user_type THEN
		RETURN NEW;
	END IF;
	
	/** Copy to other fields **/
	UPDATE record_visibilities SET db_user_type=NEW.db_user_type WHERE user_ref=NEW.id;
	UPDATE collections_fields_visibilities SET db_user_type=NEW.db_user_type WHERE user_ref=NEW.id;
	UPDATE users_coll_rights_asked SET db_user_type=NEW.db_user_type WHERE user_ref=NEW.id;
	
	
	/** IF REVOKE ***/
	IF NEW.db_user_type < OLD.db_user_type THEN
		/*Each number of this suite represent a right on the collection: 1 for read, 2 for insert, 4 for update and 8 for delete*/
		/*db user type 1 for registered user, 2 for encoder, 3 for collection manager, 4 for system admin,*/
		IF OLD.db_user_type >= 3 THEN
			/** If retrograde from collection_man, remove all collection administrated **/
			SELECT count(*) != 0 INTO still_mgr FROM collections WHERE main_manager_ref = NEW.id;
			IF still_mgr THEN
				RAISE EXCEPTION 'Still Manager in some Collections.';
			END IF;
			DELETE FROM collections_admin WHERE user_ref = NEW.id;
		END IF;
		
		IF OLD.db_user_type >= 2 AND NEW.db_user_type = 1 THEN
			/** If retrograde to register , remove write/insert/update rights**/
			UPDATE collections_rights SET rights=1 WHERE user_ref=NEW.id;
		END IF;
	END IF;
	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

/**
* fct_cpy_update_children_when_parent_updated
* Update all related children, when parent_ref or level_ref of parent have been updated
*/

CREATE OR REPLACE FUNCTION fct_cpy_update_children_when_parent_updated (referenced_relation varchar, parent_id integer, parent_old_level template_classifications.level_ref%TYPE, parent_new_level template_classifications.level_ref%TYPE, parent_hierarchy_ref integer[], parent_hierarchy_indexed tsvector[]) RETURNS BOOLEAN
AS $$
DECLARE
	response boolean default true;
	levels integer[];
	parent_old_level_sys_name catalogue_levels.level_sys_name%TYPE;
	sub_genus_by_extract integer;
BEGIN
	EXECUTE 'SELECT ARRAY(SELECT id FROM catalogue_levels WHERE level_type = '  || quote_literal(referenced_relation) || ' order by id)' into levels;
	SELECT level_sys_name INTO parent_old_level_sys_name FROM catalogue_levels WHERE id = parent_old_level;
	IF referenced_relation = 'chronostratigraphy' THEN
		EXECUTE 'UPDATE chronostratigraphy AS c ' ||
			'SET eon_ref = ' || coalesce(parent_hierarchy_ref[1], 0) || ', ' ||
			'    eon_indexed = ' || quote_literal(coalesce(parent_hierarchy_indexed[1], '')) || ', ' ||
			'    era_ref = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || coalesce(parent_hierarchy_ref[2], 0) ||
			'                   WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.era_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(era_ref,0) ' ||
			'              END, ' ||
			'    era_indexed = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[2], '')) ||
			'                       WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                       CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN '''' ' ||
			'                            ELSE (SELECT pt.era_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                       END ' ||
			'                       ELSE coalesce(era_indexed, '''') ' ||
			'                  END, ' ||
			'    sub_era_ref = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || coalesce(parent_hierarchy_ref[3], 0) ||
			'                       WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.sub_era_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                       ELSE coalesce(sub_era_ref,0) ' ||
			'                  END, ' ||
			'    sub_era_indexed = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[3], '')) ||
			'                           WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN '''' ' ||
			'                                ELSE (SELECT pt.sub_era_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(sub_era_indexed, '''') ' ||
			'                      END, ' ||
			'    system_ref = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || coalesce(parent_hierarchy_ref[4], 0) ||
			'                   WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.system_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(system_ref,0) ' ||
			'              END, ' ||
			'    system_indexed = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[4], '')) ||
			'                   WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.system_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(system_indexed, '''') ' ||
			'              END, ' ||
			'    serie_ref = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || coalesce(parent_hierarchy_ref[5], 0) ||
			'                   WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.serie_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(serie_ref,0) ' ||
			'              END, ' ||
			'    serie_indexed = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[5], '')) ||
			'                   WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.serie_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(serie_indexed, '''') ' ||
			'              END, ' ||
			'    stage_ref = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || coalesce(parent_hierarchy_ref[6], 0) ||
			'                   WHEN level_ref > ' || levels[6] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.stage_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(stage_ref,0) ' ||
			'              END, ' ||
			'    stage_indexed = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[6], '')) ||
			'                   WHEN level_ref > ' || levels[6] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.stage_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(stage_indexed, '''') ' ||
			'              END, ' ||
			'    sub_stage_ref = CASE WHEN ' || levels[7] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[7] || ') THEN ' || coalesce(parent_hierarchy_ref[7], 0) ||
			'                   WHEN level_ref > ' || levels[7] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[7] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.sub_stage_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_stage_ref,0) ' ||
			'              END, ' ||
			'    sub_stage_indexed = CASE WHEN ' || levels[7] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[7] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[7], '')) ||
			'                   WHEN level_ref > ' || levels[7] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[7] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.sub_stage_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_stage_indexed, '''') ' ||
			'              END, ' ||
			'    sub_level_1_ref = CASE WHEN ' || levels[8] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[8] || ') THEN ' || coalesce(parent_hierarchy_ref[8], 0) ||
			'                   WHEN level_ref > ' || levels[8] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[8] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.sub_level_1_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_level_1_ref,0) ' ||
			'              END, ' ||
			'    sub_level_1_indexed = CASE WHEN ' || levels[8] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[8] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[8], '')) ||
			'                   WHEN level_ref > ' || levels[8] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[8] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.sub_level_1_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_level_1_indexed, '''') ' ||
			'              END, ' ||
			'    sub_level_2_ref = CASE WHEN ' || levels[9] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[9] || ') THEN ' || coalesce(parent_hierarchy_ref[9], 0) ||
			'                   WHEN level_ref > ' || levels[9] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[9] || ' THEN 0 ' ||
			'                        ELSE (SELECT pt.sub_level_2_ref FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_level_2_ref,0) ' ||
			'              END, ' ||
			'    sub_level_2_indexed = CASE WHEN ' || levels[9] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[9] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[9], '')) ||
			'                   WHEN level_ref > ' || levels[9] || ' THEN ' || 
			'                   CASE WHEN ' || parent_old_level || ' >= ' || levels[9] || ' THEN '''' ' ||
			'                        ELSE (SELECT pt.sub_level_2_indexed FROM chronostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                   END ' ||
			'                   ELSE coalesce(sub_level_2_indexed, '''') ' ||
			'              END ' ||
			'WHERE id <> ' || parent_id || ' ' ||
			'  AND ' || quote_ident(parent_old_level_sys_name::varchar || '_ref') || ' = ' || parent_id;
		response := true;
	ELSIF referenced_relation = 'lithostratigraphy' THEN
		EXECUTE 'UPDATE lithostratigraphy AS c ' ||
			'SET group_ref = ' || coalesce(parent_hierarchy_ref[1], 0) || ', ' ||
			'    group_indexed = ' || quote_literal(coalesce(parent_hierarchy_indexed[1], '')) || ', ' ||
			'    formation_ref = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || coalesce(parent_hierarchy_ref[2], 0) ||
			'                         WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN 0 ' ||
			'                              ELSE (SELECT pt.formation_ref FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(formation_ref,0) ' ||
			'                    END, ' ||
			'    formation_indexed = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[2], '')) ||
			'                             WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN '''' ' ||
			'                                  ELSE (SELECT pt.formation_indexed FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(formation_indexed, '''') ' ||
			'                        END, ' ||
			'    member_ref = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || coalesce(parent_hierarchy_ref[3], 0) ||
			'                      WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                      CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN 0 ' ||
			'                           ELSE (SELECT pt.member_ref FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                      END ' ||
			'                      ELSE coalesce(member_ref,0) ' ||
			'                 END, ' ||
			'    member_indexed = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[3], '')) ||
			'                          WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN '''' ' ||
			'                               ELSE (SELECT pt.member_indexed FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(member_indexed, '''') ' ||
			'                     END, ' ||
			'    layer_ref = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || coalesce(parent_hierarchy_ref[4], 0) ||
			'                     WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN 0 ' ||
			'                          ELSE (SELECT pt.layer_ref FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(layer_ref,0) ' ||
			'                END, ' ||
			'    layer_indexed = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[4], '')) ||
			'                         WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN '''' ' ||
			'                              ELSE (SELECT pt.layer_indexed FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(layer_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_level_1_ref = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || coalesce(parent_hierarchy_ref[5], 0) ||
			'                           WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN 0 ' ||
			'                                ELSE (SELECT pt.sub_level_1_ref FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(sub_level_1_ref,0) ' ||
			'                      END, ' ||
			'    sub_level_1_indexed = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[5], '')) ||
			'                               WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN '''' ' ||
			'                                    ELSE (SELECT pt.sub_level_1_indexed FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(sub_level_1_indexed, '''') ' ||
			'                          END, ' ||
			'    sub_level_2_ref = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || coalesce(parent_hierarchy_ref[6], 0) ||
			'                           WHEN level_ref > ' || levels[6] || ' THEN ' || 
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN 0 ' ||
			'                                ELSE (SELECT pt.sub_level_2_ref FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(sub_level_2_ref,0) ' ||
			'                      END, ' ||
			'    sub_level_2_indexed = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[6], '')) ||
			'                               WHEN level_ref > ' || levels[6] || ' THEN ' || 
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN '''' ' ||
			'                                    ELSE (SELECT pt.sub_level_2_indexed FROM lithostratigraphy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(sub_level_2_indexed, '''') ' ||
			'                          END ' ||
			'WHERE id <> ' || parent_id || ' ' ||
			'  AND ' || quote_ident(parent_old_level_sys_name::varchar || '_ref') || ' = ' || parent_id;
		response := true;
	ELSIF referenced_relation = 'mineralogy' THEN
		EXECUTE 'UPDATE mineralogy AS c ' ||
			'SET unit_class_ref = ' || coalesce(parent_hierarchy_ref[1], 0) || ', ' ||
			'    unit_class_indexed = ' || quote_literal(coalesce(parent_hierarchy_indexed[1], '')) || ', ' ||
			'    unit_division_ref = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || coalesce(parent_hierarchy_ref[2], 0) ||
			'                             WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN 0 ' ||
			'                                  ELSE (SELECT pt.unit_division_ref FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(unit_division_ref,0) ' ||
			'                        END, ' ||
			'    unit_division_indexed = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[2], '')) ||
			'                                 WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                                 CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN '''' ' ||
			'                                      ELSE (SELECT pt.unit_division_indexed FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                 END ' ||
			'                                 ELSE coalesce(unit_division_indexed, '''') ' ||
			'                            END, ' ||
			'    unit_family_ref = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || coalesce(parent_hierarchy_ref[3], 0) ||
			'                           WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN 0 ' ||
			'                                ELSE (SELECT pt.unit_family_ref FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(unit_family_ref,0) ' ||
			'                      END, ' ||
			'    unit_family_indexed = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[3], '')) ||
			'                               WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN '''' ' ||
			'                                    ELSE (SELECT pt.unit_family_indexed FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(unit_family_indexed, '''') ' ||
			'                          END, ' ||
			'    unit_group_ref = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || coalesce(parent_hierarchy_ref[4], 0) ||
			'                          WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN 0 ' ||
			'                               ELSE (SELECT pt.unit_group_ref FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(unit_group_ref,0) ' ||
			'                     END, ' ||
			'    unit_group_indexed = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[4], '')) ||
			'                              WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN '''' ' ||
			'                                   ELSE (SELECT pt.unit_group_indexed FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(unit_group_indexed, '''') ' ||
			'                         END, ' ||
			'    unit_variety_ref = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || coalesce(parent_hierarchy_ref[5], 0) ||
			'                            WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN 0 ' ||
			'                                 ELSE (SELECT pt.unit_variety_ref FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(unit_variety_ref,0) ' ||
			'                       END, ' ||
			'    unit_variety_indexed = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[5], '')) ||
			'                                WHEN level_ref > ' || levels[5] || ' THEN ' || 
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN '''' ' ||
			'                                     ELSE (SELECT pt.unit_variety_indexed FROM mineralogy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(unit_variety_indexed, '''') ' ||
			'                           END ' ||
			'WHERE id <> ' || parent_id || ' ' ||
			'  AND ' || quote_ident(parent_old_level_sys_name::varchar || '_ref') || ' = ' || parent_id;
		response := true;
	ELSIF referenced_relation = 'taxonomy' THEN
		EXECUTE 'UPDATE taxonomy AS c ' ||
			'SET domain_ref = ' || coalesce(parent_hierarchy_ref[1], 0) || ', ' ||
			'    domain_indexed = ' || quote_literal(coalesce(parent_hierarchy_indexed[1], '')) || ', ' ||
			'    kingdom_ref = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || coalesce(parent_hierarchy_ref[2], 0) ||
			'                       WHEN level_ref > ' || levels[2] || ' THEN ' ||
			'                       CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN 0 ' ||
			'                            ELSE (SELECT pt.kingdom_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                       END ' ||
			'                       ELSE coalesce(kingdom_ref,0) ' ||
			'                  END, ' ||
			'    kingdom_indexed = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[2], '')) ||
			'                           WHEN level_ref > ' || levels[2] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN '''' ' ||
                        '                                ELSE (SELECT pt.kingdom_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(kingdom_indexed, '''') ' ||
			'                      END, ' ||
			'    super_phylum_ref = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || coalesce(parent_hierarchy_ref[3], 0) ||
			'                            WHEN level_ref > ' || levels[3] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.super_phylum_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(super_phylum_ref,0) ' ||
			'                       END, ' ||
			'    super_phylum_indexed = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[3], '')) ||
			'                                WHEN level_ref > ' || levels[3] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.super_phylum_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(super_phylum_indexed, '''') ' ||
			'                           END, ' ||
			'    phylum_ref = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || coalesce(parent_hierarchy_ref[4], 0) ||
			'                      WHEN level_ref > ' || levels[4] || ' THEN ' ||
			'                      CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN 0 ' ||
                        '                           ELSE (SELECT pt.phylum_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                      END ' ||
			'                      ELSE coalesce(phylum_ref,0) ' ||
			'                 END, ' ||
			'    phylum_indexed = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[4], '')) ||
			'                          WHEN level_ref > ' || levels[4] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN '''' ' ||
                        '                               ELSE (SELECT pt.phylum_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(phylum_indexed, '''') ' ||
			'                     END, ' ||
			'    sub_phylum_ref = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || coalesce(parent_hierarchy_ref[5], 0) ||
			'                          WHEN level_ref > ' || levels[5] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN 0 ' ||
                        '                               ELSE (SELECT pt.sub_phylum_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(sub_phylum_ref,0) ' ||
			'		      END, ' ||
			'    sub_phylum_indexed = CASE WHEN ' || levels[5] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[5] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[5], '')) ||
			'                              WHEN level_ref > ' || levels[5] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[5] || ' THEN '''' ' ||
                        '                                   ELSE (SELECT pt.sub_phylum_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(sub_phylum_indexed, '''') ' ||
			'                         END, ' ||
			'    infra_phylum_ref = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || coalesce(parent_hierarchy_ref[6], 0) ||
			'                            WHEN level_ref > ' || levels[6] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.infra_phylum_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(infra_phylum_ref,0) ' ||
			'                       END, ' ||
			'    infra_phylum_indexed = CASE WHEN ' || levels[6] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[6] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[6], '')) ||
			'                                WHEN level_ref > ' || levels[6] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[6] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.infra_phylum_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(infra_phylum_indexed, '''') ' ||
			'                           END, ' ||
			'    super_cohort_botany_ref = CASE WHEN ' || levels[7] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[7] || ') THEN ' || coalesce(parent_hierarchy_ref[7], 0) ||
			'                                   WHEN level_ref > ' || levels[7] || ' THEN ' ||
			'                                   CASE WHEN ' || parent_old_level || ' >= ' || levels[7] || ' THEN 0 ' ||
                        '                                        ELSE (SELECT pt.super_cohort_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                   END ' ||
			'                                   ELSE coalesce(super_cohort_botany_ref,0) ' ||
			'                              END, ' ||
			'    super_cohort_botany_indexed = CASE WHEN ' || levels[7] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[7] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[7], '')) ||
			'                                       WHEN level_ref > ' || levels[7] || ' THEN ' ||
			'                                       CASE WHEN ' || parent_old_level || ' >= ' || levels[7] || ' THEN '''' ' ||
                        '                                            ELSE (SELECT pt.super_cohort_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                       END ' ||
			'                                       ELSE coalesce(super_cohort_botany_indexed, '''') ' ||
			'                                  END, ' ||
			'    cohort_botany_ref = CASE WHEN ' || levels[8] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[8] || ') THEN ' || coalesce(parent_hierarchy_ref[8], 0) ||
			'                             WHEN level_ref > ' || levels[8] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[8] || ' THEN 0 ' ||
                        '                                  ELSE (SELECT pt.cohort_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(cohort_botany_ref,0) ' ||
			'                        END, ' ||
			'    cohort_botany_indexed = CASE WHEN ' || levels[8] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[8] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[8], '')) ||
			'                                 WHEN level_ref > ' || levels[8] || ' THEN ' ||
			'                                 CASE WHEN ' || parent_old_level || ' >= ' || levels[8] || ' THEN '''' ' ||
                        '                                      ELSE (SELECT pt.cohort_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                 END ' ||
			'                                 ELSE coalesce(cohort_botany_indexed, '''') ' ||
			'                            END, ' ||
			'    sub_cohort_botany_ref = CASE WHEN ' || levels[9] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[9] || ') THEN ' || coalesce(parent_hierarchy_ref[9], 0) ||
			'                                 WHEN level_ref > ' || levels[9] || ' THEN ' ||
			'                                 CASE WHEN ' || parent_old_level || ' >= ' || levels[9] || ' THEN 0 ' ||
                        '                                      ELSE (SELECT pt.sub_cohort_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                 END ' ||
			'                                 ELSE coalesce(sub_cohort_botany_ref,0) ' ||
			'                            END, ' ||
			'    sub_cohort_botany_indexed = CASE WHEN ' || levels[9] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[9] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[9], '')) ||
			'                                     WHEN level_ref > ' || levels[9] || ' THEN ' ||
			'                                     CASE WHEN ' || parent_old_level || ' >= ' || levels[9] || ' THEN '''' ' ||
                        '                                          ELSE (SELECT pt.sub_cohort_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                     END ' ||
			'                                     ELSE coalesce(sub_cohort_botany_indexed, '''') ' ||
			'                                END, ' ||
			'    infra_cohort_botany_ref = CASE WHEN ' || levels[10] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[10] || ') THEN ' || coalesce(parent_hierarchy_ref[10], 0) ||
			'                                   WHEN level_ref > ' || levels[10] || ' THEN ' ||
			'                                   CASE WHEN ' || parent_old_level || ' >= ' || levels[10] || ' THEN 0 ' ||
                        '                                        ELSE (SELECT pt.infra_cohort_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                   END ' ||
			'                                   ELSE coalesce(infra_cohort_botany_ref,0) ' ||
			'                              END, ' ||
			'    infra_cohort_botany_indexed = CASE WHEN ' || levels[10] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[10] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[10], '')) ||
			'                                       WHEN level_ref > ' || levels[10] || ' THEN ' ||
			'                                       CASE WHEN ' || parent_old_level || ' >= ' || levels[10] || ' THEN '''' ' ||
                        '                                            ELSE (SELECT pt.infra_cohort_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                       END ' ||
			'                                       ELSE coalesce(infra_cohort_botany_indexed, '''') ' ||
			'                                  END, ' ||
			'    super_class_ref = CASE WHEN ' || levels[11] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[11] || ') THEN ' || coalesce(parent_hierarchy_ref[11], 0) ||
			'                           WHEN level_ref > ' || levels[11] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[11] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.super_class_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(super_class_ref,0) ' ||
			'                      END, ' ||
			'    super_class_indexed = CASE WHEN ' || levels[11] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[11] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[11], '')) ||
			'                               WHEN level_ref > ' || levels[11] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[11] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.super_class_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(super_class_indexed, '''') ' ||
			'                          END, ' ||
			'    class_ref = CASE WHEN ' || levels[12] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[12] || ') THEN ' || coalesce(parent_hierarchy_ref[12], 0) ||
			'                     WHEN level_ref > ' || levels[12] || ' THEN ' ||
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[12] || ' THEN 0 ' ||
                        '                          ELSE (SELECT pt.class_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(class_ref,0) ' ||
			'                END, ' ||
			'    class_indexed = CASE WHEN ' || levels[12] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[12] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[12], '')) ||
			'                         WHEN level_ref > ' || levels[12] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[12] || ' THEN '''' ' ||
                        '                              ELSE (SELECT pt.class_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(class_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_class_ref = CASE WHEN ' || levels[13] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[13] || ') THEN ' || coalesce(parent_hierarchy_ref[13], 0) ||
			'                         WHEN level_ref > ' || levels[13] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[13] || ' THEN 0 ' ||
                        '                              ELSE (SELECT pt.sub_class_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(sub_class_ref,0) ' ||
			'                    END, ' ||
			'    sub_class_indexed = CASE WHEN ' || levels[13] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[13] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[13], '')) ||
			'                             WHEN level_ref > ' || levels[13] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[13] || ' THEN '''' ' ||
                        '                                  ELSE (SELECT pt.sub_class_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(sub_class_indexed, '''') ' ||
			'                        END, ' ||
			'    infra_class_ref = CASE WHEN ' || levels[14] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[14] || ') THEN ' || coalesce(parent_hierarchy_ref[14], 0) ||
			'                           WHEN level_ref > ' || levels[14] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[14] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.infra_class_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(infra_class_ref,0) ' ||
			'                      END, ' ||
			'    infra_class_indexed = CASE WHEN ' || levels[14] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[14] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[14], '')) ||
			'                               WHEN level_ref > ' || levels[14] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[14] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.infra_class_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(infra_class_indexed, '''') ' ||
			'                          END, ' ||
			'    super_division_ref = CASE WHEN ' || levels[15] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[15] || ') THEN ' || coalesce(parent_hierarchy_ref[15], 0) ||
			'                              WHEN level_ref > ' || levels[15] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[15] || ' THEN 0 ' ||
                        '                                   ELSE (SELECT pt.super_division_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(super_division_ref,0) ' ||
			'                         END, ' ||
			'    super_division_indexed = CASE WHEN ' || levels[15] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[15] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[15], '')) ||
			'                                  WHEN level_ref > ' || levels[15] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[15] || ' THEN '''' ' ||
                        '                                       ELSE (SELECT pt.super_division_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(super_division_indexed, '''') ' ||
			'                             END, ' ||
			'    division_ref = CASE WHEN ' || levels[16] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[16] || ') THEN ' || coalesce(parent_hierarchy_ref[16], 0) ||
			'                        WHEN level_ref > ' || levels[16] || ' THEN ' ||
			'                        CASE WHEN ' || parent_old_level || ' >= ' || levels[16] || ' THEN 0 ' ||
                        '                             ELSE (SELECT pt.division_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                        END ' ||
			'                        ELSE coalesce(division_ref,0) ' ||
			'                   END, ' ||
			'    division_indexed = CASE WHEN ' || levels[16] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[16] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[16], '')) ||
			'                            WHEN level_ref > ' || levels[16] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[16] || ' THEN '''' ' ||
                        '                                 ELSE (SELECT pt.division_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(division_indexed, '''') ' ||
			'                       END, ' ||
			'    sub_division_ref = CASE WHEN ' || levels[17] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[17] || ') THEN ' || coalesce(parent_hierarchy_ref[17], 0) ||
			'                            WHEN level_ref > ' || levels[17] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[17] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.sub_division_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(sub_division_ref,0) ' ||
			'                       END, ' ||
			'    sub_division_indexed = CASE WHEN ' || levels[17] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[17] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[17], '')) ||
			'                                WHEN level_ref > ' || levels[17] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[17] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.sub_division_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(sub_division_indexed, '''') ' ||
			'                           END, ' ||
			'    infra_division_ref = CASE WHEN ' || levels[18] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[18] || ') THEN ' || coalesce(parent_hierarchy_ref[18], 0) ||
			'                              WHEN level_ref > ' || levels[18] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[18] || ' THEN 0 ' ||
                        '                                   ELSE (SELECT pt.infra_division_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(infra_division_ref,0) ' ||
			'                         END, ' ||
			'    infra_division_indexed = CASE WHEN ' || levels[18] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[18] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[18], '')) ||
			'                                  WHEN level_ref > ' || levels[18] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[18] || ' THEN '''' ' ||
                        '                                       ELSE (SELECT pt.infra_division_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(infra_division_indexed, '''') ' ||
			'                             END, ' ||
			'    super_legion_ref = CASE WHEN ' || levels[19] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[19] || ') THEN ' || coalesce(parent_hierarchy_ref[19], 0) ||
			'                            WHEN level_ref > ' || levels[19] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[19] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.super_legion_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(super_legion_ref,0) ' ||
			'                       END, ' ||
			'    super_legion_indexed = CASE WHEN ' || levels[19] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[19] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[19], '')) ||
			'                                WHEN level_ref > ' || levels[19] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[19] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.super_legion_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(super_legion_indexed, '''') ' ||
			'                           END, ' ||
			'    legion_ref = CASE WHEN ' || levels[20] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[20] || ') THEN ' || coalesce(parent_hierarchy_ref[20], 0) ||
			'                      WHEN level_ref > ' || levels[20] || ' THEN ' ||
			'                      CASE WHEN ' || parent_old_level || ' >= ' || levels[20] || ' THEN 0 ' ||
                        '                           ELSE (SELECT pt.legion_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                      END ' ||
			'                      ELSE coalesce(legion_ref,0) ' ||
			'                 END, ' ||
			'    legion_indexed = CASE WHEN ' || levels[20] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[20] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[20], '')) ||
			'                          WHEN level_ref > ' || levels[20] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[20] || ' THEN '''' ' ||
                        '                               ELSE (SELECT pt.legion_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(legion_indexed, '''') ' ||
			'                     END, ' ||
			'    sub_legion_ref = CASE WHEN ' || levels[21] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[21] || ') THEN ' || coalesce(parent_hierarchy_ref[21], 0) ||
			'                          WHEN level_ref > ' || levels[21] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[21] || ' THEN 0 ' ||
                        '                               ELSE (SELECT pt.sub_legion_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(sub_legion_ref,0) ' ||
			'                     END, ' ||
			'    sub_legion_indexed = CASE WHEN ' || levels[21] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[21] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[21], '')) ||
			'                              WHEN level_ref > ' || levels[21] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[21] || ' THEN '''' ' ||
                        '                                   ELSE (SELECT pt.sub_legion_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(sub_legion_indexed, '''') ' ||
			'                         END, ' ||
			'    infra_legion_ref = CASE WHEN ' || levels[22] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[22] || ') THEN ' || coalesce(parent_hierarchy_ref[22], 0) ||
			'                            WHEN level_ref > ' || levels[22] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[22] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.infra_legion_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(infra_legion_ref,0) ' ||
			'                       END, ' ||
			'    infra_legion_indexed = CASE WHEN ' || levels[22] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[22] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[22], '')) ||
			'                                WHEN level_ref > ' || levels[22] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[22] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.infra_legion_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(infra_legion_indexed, '''') ' ||
			'                           END, ' ||
			'    super_cohort_zoology_ref = CASE WHEN ' || levels[23] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[23] || ') THEN ' || coalesce(parent_hierarchy_ref[23], 0) ||
			'                                    WHEN level_ref > ' || levels[23] || ' THEN ' ||
			'                                    CASE WHEN ' || parent_old_level || ' >= ' || levels[23] || ' THEN 0 ' ||
                        '                                         ELSE (SELECT pt.super_cohort_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                    END ' ||
			'                                    ELSE coalesce(super_cohort_zoology_ref,0) ' ||
			'                               END, ' ||
			'    super_cohort_zoology_indexed = CASE WHEN ' || levels[23] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[23] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[23], '')) ||
			'                                        WHEN level_ref > ' || levels[23] || ' THEN ' ||
			'                                        CASE WHEN ' || parent_old_level || ' >= ' || levels[23] || ' THEN '''' ' ||
                        '                                             ELSE (SELECT pt.super_cohort_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                        END ' ||
			'                                        ELSE coalesce(super_cohort_zoology_indexed, '''') ' ||
			'                                   END, ' ||
			'    cohort_zoology_ref = CASE WHEN ' || levels[24] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[24] || ') THEN ' || coalesce(parent_hierarchy_ref[24], 0) ||
			'                              WHEN level_ref > ' || levels[24] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[24] || ' THEN 0 ' ||
                        '                                   ELSE (SELECT pt.cohort_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(cohort_zoology_ref,0) ' ||
			'                         END, ' ||
			'    cohort_zoology_indexed = CASE WHEN ' || levels[24] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[24] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[24], '')) ||
			'                                  WHEN level_ref > ' || levels[24] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[24] || ' THEN '''' ' ||
                        '                                       ELSE (SELECT pt.cohort_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(cohort_zoology_indexed, '''') ' ||
			'                             END, ' ||
			'    sub_cohort_zoology_ref = CASE WHEN ' || levels[25] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[25] || ') THEN ' || coalesce(parent_hierarchy_ref[25], 0) ||
			'                                  WHEN level_ref > ' || levels[25] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[25] || ' THEN 0 ' ||
                        '                                       ELSE (SELECT pt.sub_cohort_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(sub_cohort_zoology_ref,0) ' ||
			'                             END, ' ||
			'    sub_cohort_zoology_indexed = CASE WHEN ' || levels[25] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[25] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[25], '')) ||
			'                                      WHEN level_ref > ' || levels[25] || ' THEN ' ||
			'                                      CASE WHEN ' || parent_old_level || ' >= ' || levels[25] || ' THEN '''' ' ||
                        '                                           ELSE (SELECT pt.sub_cohort_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                      END ' ||
			'                                      ELSE coalesce(sub_cohort_zoology_indexed, '''') ' ||
			'                                 END, ' ||
			'    infra_cohort_zoology_ref = CASE WHEN ' || levels[26] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[26] || ') THEN ' || coalesce(parent_hierarchy_ref[26], 0) ||
			'                                    WHEN level_ref > ' || levels[26] || ' THEN ' ||
			'                                    CASE WHEN ' || parent_old_level || ' >= ' || levels[26] || ' THEN 0 ' ||
                        '                                         ELSE (SELECT pt.infra_cohort_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                    END ' ||
			'                                    ELSE coalesce(infra_cohort_zoology_ref,0) ' ||
			'                               END, ' ||
			'    infra_cohort_zoology_indexed = CASE WHEN ' || levels[26] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[26] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[26], '')) ||
			'                                        WHEN level_ref > ' || levels[26] || ' THEN ' ||
			'                                        CASE WHEN ' || parent_old_level || ' >= ' || levels[26] || ' THEN '''' ' ||
                        '                                             ELSE (SELECT pt.infra_cohort_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                        END ' ||
			'                                        ELSE coalesce(infra_cohort_zoology_indexed, '''') ' ||
			'                                   END, ' ||
			'    super_order_ref = CASE WHEN ' || levels[27] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[27] || ') THEN ' || coalesce(parent_hierarchy_ref[27], 0) ||
			'                           WHEN level_ref > ' || levels[27] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[27] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.super_order_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(super_order_ref,0) ' ||
			'                      END, ' ||
			'    super_order_indexed = CASE WHEN ' || levels[27] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[27] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[27], '')) ||
			'                               WHEN level_ref > ' || levels[27] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[27] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.super_order_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(super_order_indexed, '''') ' ||
			'                          END, ' ||
			'    order_ref = CASE WHEN ' || levels[28] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[28] || ') THEN ' || coalesce(parent_hierarchy_ref[28], 0) ||
			'                     WHEN level_ref > ' || levels[28] || ' THEN ' ||
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[28] || ' THEN 0 ' ||
                        '                          ELSE (SELECT pt.order_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(order_ref,0) ' ||
			'                END, ' ||
			'    order_indexed = CASE WHEN ' || levels[28] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[28] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[28], '')) ||
			'                         WHEN level_ref > ' || levels[28] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[28] || ' THEN '''' ' ||
                        '                              ELSE (SELECT pt.order_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(order_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_order_ref = CASE WHEN ' || levels[29] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[29] || ') THEN ' || coalesce(parent_hierarchy_ref[29], 0) ||
			'                         WHEN level_ref > ' || levels[29] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[29] || ' THEN 0 ' ||
                        '                              ELSE (SELECT pt.sub_order_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(sub_order_ref,0) ' ||
			'                    END, ' ||
			'    sub_order_indexed = CASE WHEN ' || levels[29] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[29] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[29], '')) ||
			'                             WHEN level_ref > ' || levels[29] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[29] || ' THEN '''' ' ||
                        '                                  ELSE (SELECT pt.sub_order_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(sub_order_indexed, '''') ' ||
			'                        END, ' ||
			'    infra_order_ref = CASE WHEN ' || levels[30] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[30] || ') THEN ' || coalesce(parent_hierarchy_ref[30], 0) ||
			'                           WHEN level_ref > ' || levels[30] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[30] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.infra_order_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(infra_order_ref,0) ' ||
			'                      END, ' ||
			'    infra_order_indexed = CASE WHEN ' || levels[30] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[30] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[30], '')) ||
			'                               WHEN level_ref > ' || levels[30] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[30] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.infra_order_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(infra_order_indexed, '''') ' ||
			'                          END, ' ||
			'    section_zoology_ref = CASE WHEN ' || levels[31] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[31] || ') THEN ' || coalesce(parent_hierarchy_ref[31], 0) ||
			'                               WHEN level_ref > ' || levels[31] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[31] || ' THEN 0 ' ||
                        '                                    ELSE (SELECT pt.section_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(section_zoology_ref,0) ' ||
			'                          END, ' ||
			'    section_zoology_indexed = CASE WHEN ' || levels[31] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[31] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[31], '')) ||
			'                                   WHEN level_ref > ' || levels[31] || ' THEN ' ||
			'                                   CASE WHEN ' || parent_old_level || ' >= ' || levels[31] || ' THEN '''' ' ||
                        '                                        ELSE (SELECT pt.section_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                   END ' ||
			'                                   ELSE coalesce(section_zoology_indexed, '''') ' ||
			'                              END, ' ||
			'    sub_section_zoology_ref = CASE WHEN ' || levels[32] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[32] || ') THEN ' || coalesce(parent_hierarchy_ref[32], 0) ||
			'                                   WHEN level_ref > ' || levels[32] || ' THEN ' ||
			'                                   CASE WHEN ' || parent_old_level || ' >= ' || levels[32] || ' THEN 0 ' ||
                        '                                        ELSE (SELECT pt.sub_section_zoology_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                   END ' ||
			'                                   ELSE coalesce(sub_section_zoology_ref,0) ' ||
			'                              END, ' ||
			'    sub_section_zoology_indexed = CASE WHEN ' || levels[32] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[32] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[32], '')) ||
			'                                       WHEN level_ref > ' || levels[32] || ' THEN ' ||
			'                                       CASE WHEN ' || parent_old_level || ' >= ' || levels[32] || ' THEN '''' ' ||
                        '                                            ELSE (SELECT pt.sub_section_zoology_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                       END ' ||
			'                                       ELSE coalesce(sub_section_zoology_indexed, '''') ' ||
			'                                  END, ' ||
			'    super_family_ref = CASE WHEN ' || levels[33] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[33] || ') THEN ' || coalesce(parent_hierarchy_ref[33], 0) ||
			'                            WHEN level_ref > ' || levels[33] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[33] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.super_family_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(super_family_ref,0) ' ||
			'                        END, ' ||
			'    super_family_indexed = CASE WHEN ' || levels[33] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[33] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[33], '')) ||
			'                                WHEN level_ref > ' || levels[33] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[33] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.super_family_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(super_family_indexed, '''') ' ||
			'                           END, ' ||
			'    family_ref = CASE WHEN ' || levels[34] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[34] || ') THEN ' || coalesce(parent_hierarchy_ref[34], 0) ||
			'                      WHEN level_ref > ' || levels[34] || ' THEN ' ||
			'                      CASE WHEN ' || parent_old_level || ' >= ' || levels[34] || ' THEN 0 ' ||
                        '                           ELSE (SELECT pt.family_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                      END ' ||
			'                      ELSE coalesce(family_ref,0) ' ||
			'                 END, ' ||
			'    family_indexed = CASE WHEN ' || levels[34] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[34] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[34], '')) ||
			'                          WHEN level_ref > ' || levels[34] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[34] || ' THEN '''' ' ||
                        '                               ELSE (SELECT pt.family_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(family_indexed, '''') ' ||
			'                     END, ' ||
			'    sub_family_ref = CASE WHEN ' || levels[35] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[35] || ') THEN ' || coalesce(parent_hierarchy_ref[35], 0) ||
			'                          WHEN level_ref > ' || levels[35] || ' THEN ' ||
			'                          CASE WHEN ' || parent_old_level || ' >= ' || levels[35] || ' THEN 0 ' ||
                        '                               ELSE (SELECT pt.sub_family_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                          END ' ||
			'                          ELSE coalesce(sub_family_ref,0) ' ||
			'                     END, ' ||
			'    sub_family_indexed = CASE WHEN ' || levels[35] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[35] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[35], '')) ||
			'                              WHEN level_ref > ' || levels[35] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[35] || ' THEN '''' ' ||
                        '                                   ELSE (SELECT pt.sub_family_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(sub_family_indexed, '''') ' ||
			'                         END, ' ||
			'    infra_family_ref = CASE WHEN ' || levels[36] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[36] || ') THEN ' || coalesce(parent_hierarchy_ref[36], 0) ||
			'                            WHEN level_ref > ' || levels[36] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[36] || ' THEN 0 ' ||
                        '                                 ELSE (SELECT pt.infra_family_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(infra_family_ref,0) ' ||
			'                       END, ' ||
			'    infra_family_indexed = CASE WHEN ' || levels[36] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[36] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[36], '')) ||
			'                                WHEN level_ref > ' || levels[36] || ' THEN ' ||
			'                                CASE WHEN ' || parent_old_level || ' >= ' || levels[36] || ' THEN '''' ' ||
                        '                                     ELSE (SELECT pt.infra_family_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                END ' ||
			'                                ELSE coalesce(infra_family_indexed, '''') ' ||
			'                           END, ' ||
			'    super_tribe_ref = CASE WHEN ' || levels[37] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[37] || ') THEN ' || coalesce(parent_hierarchy_ref[37], 0) ||
			'                           WHEN level_ref > ' || levels[37] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[37] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.super_tribe_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(super_tribe_ref,0) ' ||
			'                      END, ' ||
			'    super_tribe_indexed = CASE WHEN ' || levels[37] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[37] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[37], '')) ||
			'                               WHEN level_ref > ' || levels[37] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[37] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.super_tribe_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(super_tribe_indexed, '''') ' ||
			'                          END, ' ||
			'    tribe_ref = CASE WHEN ' || levels[38] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[38] || ') THEN ' || coalesce(parent_hierarchy_ref[38], 0) ||
			'                     WHEN level_ref > ' || levels[38] || ' THEN ' ||
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[38] || ' THEN 0 ' ||
                        '                          ELSE (SELECT pt.tribe_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(tribe_ref,0) ' ||
			'                END, ' ||
			'    tribe_indexed = CASE WHEN ' || levels[38] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[38] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[38], '')) ||
			'                         WHEN level_ref > ' || levels[38] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[38] || ' THEN '''' ' ||
                        '                              ELSE (SELECT pt.tribe_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(tribe_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_tribe_ref = CASE WHEN ' || levels[39] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[39] || ') THEN ' || coalesce(parent_hierarchy_ref[39], 0) ||
			'                         WHEN level_ref > ' || levels[39] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[39] || ' THEN 0 ' ||
                        '                              ELSE (SELECT pt.sub_tribe_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(sub_tribe_ref,0) ' ||
			'                    END, ' ||
			'    sub_tribe_indexed = CASE WHEN ' || levels[39] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[39] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[39], '')) ||
			'                             WHEN level_ref > ' || levels[39] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[39] || ' THEN '''' ' ||
                        '                                  ELSE (SELECT pt.sub_tribe_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(sub_tribe_indexed, '''') ' ||
			'                        END, ' ||
			'    infra_tribe_ref = CASE WHEN ' || levels[40] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[40] || ') THEN ' || coalesce(parent_hierarchy_ref[40], 0) ||
			'                           WHEN level_ref > ' || levels[40] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[40] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.infra_tribe_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(infra_tribe_ref,0) ' ||
			'                      END, ' ||
			'    infra_tribe_indexed = CASE WHEN ' || levels[40] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[40] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[40], '')) ||
			'                               WHEN level_ref > ' || levels[40] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[40] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.infra_tribe_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(infra_tribe_indexed, '''') ' ||
			'                          END, ' ||
			'    genus_ref = CASE WHEN ' || levels[41] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[41] || ') THEN ' || coalesce(parent_hierarchy_ref[41], 0) ||
			'                     WHEN level_ref > ' || levels[41] || ' THEN ' ||
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[41] || ' THEN 0 ' ||
                        '                          ELSE (SELECT pt.genus_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(genus_ref,0) ' ||
			'                END, ' ||
			'    genus_indexed = CASE WHEN ' || levels[41] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[41] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[41], '')) ||
			'                         WHEN level_ref > ' || levels[41] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[41] || ' THEN '''' ' ||
                        '                              ELSE (SELECT pt.genus_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(genus_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_genus_ref = CASE WHEN ' || levels[42] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[42] || ') THEN ' || coalesce(parent_hierarchy_ref[42], 0) ||
			'                         WHEN level_ref > ' || levels[42] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[42] || ' THEN 0 ' ||
                        '                              ELSE (SELECT pt.sub_genus_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(sub_genus_ref,0) ' ||
			'                    END, ' ||
			'    sub_genus_indexed = CASE WHEN ' || levels[42] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[42] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[42], '')) ||
			'                             WHEN level_ref > ' || levels[42] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[42] || ' THEN '''' ' ||
                        '                                  ELSE (SELECT pt.sub_genus_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(sub_genus_indexed, '''') ' ||
			'                        END, ' ||
			'    section_botany_ref = CASE WHEN ' || levels[43] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[43] || ') THEN ' || coalesce(parent_hierarchy_ref[43], 0) ||
			'                              WHEN level_ref > ' || levels[43] || ' THEN ' ||
			'                              CASE WHEN ' || parent_old_level || ' >= ' || levels[43] || ' THEN 0 ' ||
			'                                   ELSE (SELECT pt.section_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                              END ' ||
			'                              ELSE coalesce(section_botany_ref,0) ' ||
			'                         END, ' ||
			'    section_botany_indexed = CASE WHEN ' || levels[43] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[43] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[43], '')) ||
			'                                  WHEN level_ref > ' || levels[43] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[43] || ' THEN '''' ' ||
                        '                                       ELSE (SELECT pt.section_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(section_botany_indexed, '''') ' ||
			'                             END, ' ||
			'    sub_section_botany_ref = CASE WHEN ' || levels[44] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[44] || ') THEN ' || coalesce(parent_hierarchy_ref[44], 0) ||
			'                                  WHEN level_ref > ' || levels[44] || ' THEN ' ||
			'                                  CASE WHEN ' || parent_old_level || ' >= ' || levels[44] || ' THEN 0 ' ||
                        '                                       ELSE (SELECT pt.sub_section_botany_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                  END ' ||
			'                                  ELSE coalesce(sub_section_botany_ref,0) ' ||
			'                             END, ' ||
			'    sub_section_botany_indexed = CASE WHEN ' || levels[44] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[44] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[44], '')) ||
			'                                      WHEN level_ref > ' || levels[44] || ' THEN ' ||
			'                                      CASE WHEN ' || parent_old_level || ' >= ' || levels[44] || ' THEN '''' ' ||
                        '                                           ELSE (SELECT pt.sub_section_botany_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                      END ' ||
			'                                      ELSE coalesce(sub_section_botany_indexed, '''') ' ||
			'                                 END, ' ||
			'    serie_ref = CASE WHEN ' || levels[45] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[45] || ') THEN ' || coalesce(parent_hierarchy_ref[45], 0) ||
			'                     WHEN level_ref > ' || levels[45] || ' THEN ' ||
			'                     CASE WHEN ' || parent_old_level || ' >= ' || levels[45] || ' THEN 0 ' ||
                        '                          ELSE (SELECT pt.serie_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                     END ' ||
			'                     ELSE coalesce(serie_ref,0) ' ||
			'                END, ' ||
			'    serie_indexed = CASE WHEN ' || levels[45] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[45] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[45], '')) ||
			'                         WHEN level_ref > ' || levels[45] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[45] || ' THEN '''' ' ||
                        '                              ELSE (SELECT pt.serie_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(serie_indexed, '''') ' ||
			'                    END, ' ||
			'    sub_serie_ref = CASE WHEN ' || levels[46] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[46] || ') THEN ' || coalesce(parent_hierarchy_ref[46], 0) ||
			'                         WHEN level_ref > ' || levels[46] || ' THEN ' ||
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[46] || ' THEN 0 ' ||
                        '                              ELSE (SELECT pt.sub_serie_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(sub_serie_ref,0) ' ||
			'                    END, ' ||
			'    sub_serie_indexed = CASE WHEN ' || levels[46] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[46] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[46], '')) ||
			'                             WHEN level_ref > ' || levels[46] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[46] || ' THEN '''' ' ||
                        '                                  ELSE (SELECT pt.sub_serie_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(sub_serie_indexed, '''') ' ||
			'                        END, ' ||
			'    super_species_ref = CASE WHEN ' || levels[47] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[47] || ') THEN ' || coalesce(parent_hierarchy_ref[47], 0) ||
			'                             WHEN level_ref > ' || levels[47] || ' THEN ' ||
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[47] || ' THEN 0 ' ||
                        '                                  ELSE (SELECT pt.super_species_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(super_species_ref,0) ' ||
			'                        END, ' ||
			'    super_species_indexed = CASE WHEN ' || levels[47] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[47] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[47], '')) ||
			'                                 WHEN level_ref > ' || levels[47] || ' THEN ' ||
			'                                 CASE WHEN ' || parent_old_level || ' >= ' || levels[47] || ' THEN '''' ' ||
                        '                                      ELSE (SELECT pt.super_species_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                                 END ' ||
			'                                 ELSE coalesce(super_species_indexed, '''') ' ||
			'                            END, ' ||
			'    species_ref = CASE WHEN ' || levels[48] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[48] || ') THEN ' || coalesce(parent_hierarchy_ref[48], 0) ||
			'                       WHEN level_ref > ' || levels[48] || ' THEN ' ||
			'                       CASE WHEN ' || parent_old_level || ' >= ' || levels[48] || ' THEN 0 ' ||
                        '                            ELSE (SELECT pt.species_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                       END ' ||
			'                       ELSE coalesce(species_ref,0) ' ||
			'                  END, ' ||
			'    species_indexed = CASE WHEN ' || levels[48] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[48] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[48], '')) ||
			'                           WHEN level_ref > ' || levels[48] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[48] || ' THEN '''' ' ||
                        '                                ELSE (SELECT pt.species_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(species_indexed, '''') ' ||
			'                      END, ' ||
			'    sub_species_ref = CASE WHEN ' || levels[49] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[49] || ') THEN ' || coalesce(parent_hierarchy_ref[49], 0) ||
			'                           WHEN level_ref > ' || levels[49] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[49] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.sub_species_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(sub_species_ref,0) ' ||
			'                      END, ' ||
			'    sub_species_indexed = CASE WHEN ' || levels[49] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[49] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[49], '')) ||
			'                               WHEN level_ref > ' || levels[49] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[49] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.sub_species_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(sub_species_indexed, '''') ' ||
			'                          END, ' ||
			'    variety_ref = CASE WHEN ' || levels[50] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[50] || ') THEN ' || coalesce(parent_hierarchy_ref[50], 0) ||
			'                       WHEN level_ref > ' || levels[50] || ' THEN ' ||
			'                       CASE WHEN ' || parent_old_level || ' >= ' || levels[50] || ' THEN 0 ' ||
                        '                            ELSE (SELECT pt.variety_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                       END ' ||
			'                       ELSE coalesce(variety_ref,0) ' ||
			'                  END, ' ||
			'    variety_indexed = CASE WHEN ' || levels[50] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[50] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[50], '')) ||
			'                           WHEN level_ref > ' || levels[50] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[50] || ' THEN '''' ' ||
                        '                                ELSE (SELECT pt.variety_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(variety_indexed, '''') ' ||
			'                      END, ' ||
			'    sub_variety_ref = CASE WHEN ' || levels[51] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[51] || ') THEN ' || coalesce(parent_hierarchy_ref[51], 0) ||
			'                           WHEN level_ref > ' || levels[51] || ' THEN ' ||
			'                           CASE WHEN ' || parent_old_level || ' >= ' || levels[51] || ' THEN 0 ' ||
                        '                                ELSE (SELECT pt.sub_variety_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                           END ' ||
			'                           ELSE coalesce(sub_variety_ref,0) ' ||
			'                      END, ' ||
			'    sub_variety_indexed = CASE WHEN ' || levels[51] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[51] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[51], '')) ||
			'                               WHEN level_ref > ' || levels[51] || ' THEN ' ||
			'                               CASE WHEN ' || parent_old_level || ' >= ' || levels[51] || ' THEN '''' ' ||
                        '                                    ELSE (SELECT pt.sub_variety_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                               END ' ||
			'                               ELSE coalesce(sub_variety_indexed, '''') ' ||
			'                          END, ' ||
			'    form_ref = CASE WHEN ' || levels[52] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[52] || ') THEN ' || coalesce(parent_hierarchy_ref[52], 0) ||
			'                    WHEN level_ref > ' || levels[52] || ' THEN ' ||
			'                    CASE WHEN ' || parent_old_level || ' >= ' || levels[52] || ' THEN 0 ' ||
                        '                         ELSE (SELECT pt.form_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                    END ' ||
			'                    ELSE coalesce(form_ref,0) ' ||
			'               END, ' ||
			'    form_indexed = CASE WHEN ' || levels[52] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[52] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[52], '')) ||
			'                        WHEN level_ref > ' || levels[52] || ' THEN ' ||
			'                        CASE WHEN ' || parent_old_level || ' >= ' || levels[52] || ' THEN '''' ' ||
                        '                             ELSE (SELECT pt.form_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                        END ' ||
			'                        ELSE coalesce(form_indexed, '''') ' ||
			'                   END, ' ||
			'    sub_form_ref = CASE WHEN ' || levels[53] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[53] || ') THEN ' || coalesce(parent_hierarchy_ref[53], 0) ||
			'                        WHEN level_ref > ' || levels[53] || ' THEN ' ||
			'                        CASE WHEN ' || parent_old_level || ' >= ' || levels[53] || ' THEN 0 ' ||
                        '                             ELSE (SELECT pt.sub_form_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                        END ' ||
			'                        ELSE coalesce(sub_form_ref,0) ' ||
			'                   END, ' ||
			'    sub_form_indexed = CASE WHEN ' || levels[53] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[53] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[53], '')) ||
			'                            WHEN level_ref > ' || levels[53] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[53] || ' THEN '''' ' ||
                        '                                 ELSE (SELECT pt.sub_form_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(sub_form_indexed, '''') ' ||
			'                       END, ' ||
			'    abberans_ref = CASE WHEN ' || levels[54] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[54] || ') THEN ' || coalesce(parent_hierarchy_ref[54], 0) ||
			'                        WHEN level_ref > ' || levels[54] || ' THEN ' ||
			'                        CASE WHEN ' || parent_old_level || ' >= ' || levels[54] || ' THEN 0 ' ||
                        '                             ELSE (SELECT pt.abberans_ref FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                        END ' ||
			'                        ELSE coalesce(abberans_ref,0) ' ||
			'                   END, ' ||
			'    abberans_indexed = CASE WHEN ' || levels[54] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[54] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[54], '')) ||
			'                            WHEN level_ref > ' || levels[54] || ' THEN ' ||
			'                            CASE WHEN ' || parent_old_level || ' >= ' || levels[54] || ' THEN '''' ' ||
                        '                                 ELSE (SELECT pt.abberans_indexed FROM taxonomy AS pt WHERE pt.id = c.parent_ref) ' ||
			'                            END ' ||
			'                            ELSE coalesce(abberans_indexed, '''') ' ||
			'                       END ' ||
			'WHERE c.id <> ' || parent_id || ' ' ||
			'  AND c.' || quote_ident(parent_old_level_sys_name::varchar || '_ref') || ' = ' || parent_id;
		response := true;
	ELSIF referenced_relation = 'lithology' THEN
		EXECUTE 'UPDATE lithology AS c ' ||
			'SET unit_main_group_ref = ' || coalesce(parent_hierarchy_ref[1], 0) || ', ' ||
			'    unit_main_group_indexed = ' || quote_literal(coalesce(parent_hierarchy_indexed[1], '')) || ', ' ||
			'    unit_group_ref = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || coalesce(parent_hierarchy_ref[2], 0) ||
			'                         WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN 0 ' ||
			'                              ELSE (SELECT pt.unit_group_ref FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(unit_group_ref,0) ' ||
			'                    END, ' ||
			'    unit_group_indexed = CASE WHEN ' || levels[2] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[2] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[2], '')) ||
			'                             WHEN level_ref > ' || levels[2] || ' THEN ' || 
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[2] || ' THEN '''' ' ||
			'                                  ELSE (SELECT pt.unit_group_indexed FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(unit_group_indexed, '''') ' ||
			'                        END, ' ||
			'    unit_sub_group_ref = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || coalesce(parent_hierarchy_ref[3], 0) ||
			'                         WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN 0 ' ||
			'                              ELSE (SELECT pt.unit_sub_group_ref FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(unit_sub_group_ref,0) ' ||
			'                    END, ' ||
			'    unit_sub_group_indexed = CASE WHEN ' || levels[3] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[3] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[3], '')) ||
			'                             WHEN level_ref > ' || levels[3] || ' THEN ' || 
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[3] || ' THEN '''' ' ||
			'                                  ELSE (SELECT pt.unit_sub_group_indexed FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(unit_sub_group_indexed, '''') ' ||
			'                        END, ' ||
			'    unit_rock_ref = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || coalesce(parent_hierarchy_ref[4], 0) ||
			'                         WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                         CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN 0 ' ||
			'                              ELSE (SELECT pt.unit_rock_ref FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                         END ' ||
			'                         ELSE coalesce(unit_rock_ref,0) ' ||
			'                    END, ' ||
			'    unit_rock_indexed = CASE WHEN ' || levels[4] || ' <= ' || parent_new_level || ' OR (parent_ref = ' || parent_id || ' AND level_ref <> ' || levels[4] || ') THEN ' || quote_literal(coalesce(parent_hierarchy_indexed[4], '')) ||
			'                             WHEN level_ref > ' || levels[4] || ' THEN ' || 
			'                             CASE WHEN ' || parent_old_level || ' >= ' || levels[4] || ' THEN '''' ' ||
			'                                  ELSE (SELECT pt.unit_rock_indexed FROM lithology AS pt WHERE pt.id = c.parent_ref) ' ||
			'                             END ' ||
			'                             ELSE coalesce(unit_rock_indexed, '''') ' ||
			'                        END ' ||
			'WHERE id <> ' || parent_id || ' ' ||
			'  AND ' || quote_ident(parent_old_level_sys_name::varchar || '_ref') || ' = ' || parent_id;
		response := true;
	END IF;
	return response;
END;
$$ LANGUAGE plpgsql;

/**
* fct_cpy_update_levels_or_parent_cascade
* Test that new level and new parent definitions of a unit fits rules of "possible_upper_levels"
* If level updated, test that direct children can still be attached to unit updated
* If all tests passed, update hierarchical structure of unit updated and of all children related
*/
CREATE OR REPLACE FUNCTION fct_cpy_update_levels_or_parent_cascade() RETURNS TRIGGER
AS $$
DECLARE
	level_sys_name_new catalogue_levels.level_sys_name%TYPE;
	children_ready boolean default false;
	hierarchy_ref integer[];
	hierarchy_indexed tsvector[];
BEGIN
	IF NEW.level_ref <> OLD.level_ref OR NEW.parent_ref <> OLD.parent_ref THEN
		IF NOT fct_chk_possible_upper_level(TG_TABLE_NAME::varchar, NEW.parent_ref::integer, NEW.level_ref::integer, NEW.id::integer) THEN
			RAISE EXCEPTION 'The modification of level and/or parent reference is not allowed, because unit modified won''t follow the rules of possible upper level attachement';
		END IF;
		SELECT cl.level_sys_name INTO level_sys_name_new FROM catalogue_levels AS cl WHERE cl.id = NEW.level_ref;
		IF NEW.level_ref <> OLD.level_ref THEN
			EXECUTE 'SELECT (SELECT COUNT(*) FROM (SELECT DISTINCT tab1.level_ref ' ||
				'			       FROM ' || quote_ident(TG_TABLE_NAME::varchar) || ' AS tab1 ' ||
				'			       WHERE tab1.level_ref IN (' || 
				'							SELECT pul1.level_ref ' ||
				'							FROM possible_upper_levels AS pul1 ' ||
				'							WHERE pul1.level_upper_ref = ' || OLD.level_ref ||
				'						       ) ' ||
				'				 AND parent_ref = ' || OLD.id ||
				'			      ) AS c1' ||
				'	) ' ||
				' = ' ||
				'	(SELECT COUNT(*) FROM (SELECT DISTINCT pul2.level_ref ' ||
				'			       FROM possible_upper_levels AS pul2 ' ||
				'			       WHERE pul2.level_upper_ref = ' || NEW.level_ref || ' ' ||
				'				 AND pul2.level_ref IN (SELECT DISTINCT tab2.level_ref ' || 
				'							FROM ' || quote_ident(TG_TABLE_NAME::varchar) || ' AS tab2 ' ||
				'							WHERE tab2.level_ref IN (' ||
				'										 SELECT pul3.level_ref ' ||
				'										 FROM possible_upper_levels AS pul3 ' ||
				'										 WHERE pul3.level_upper_ref = ' || OLD.level_ref ||
				'										) ' ||
				'							AND parent_ref = ' || OLD.id ||
				'						       ) ' ||
				'			      ) as c2 ' ||
				'	) '
			INTO children_ready;
			IF NOT children_ready THEN
				RAISE EXCEPTION 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.';
			END IF;
		END IF;
			IF TG_TABLE_NAME = 'chronostratigraphy' THEN
				SELECT
					CASE 
						WHEN level_sys_name_new = 'eon' THEN
							NEW.id
						ELSE
							pc.eon_ref
					END AS eon_ref,
					CASE
                                                WHEN level_sys_name_new = 'eon' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.eon_indexed
                                        END AS eon_indexed,
					CASE 
						WHEN level_sys_name_new = 'era' THEN
							NEW.id
						ELSE
							pc.era_ref
					END AS era_ref,
					CASE
                                                WHEN level_sys_name_new = 'era' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.era_indexed
                                        END AS era_indexed,
					CASE 
						WHEN level_sys_name_new = 'sub_era' THEN
							NEW.id
						ELSE
							pc.sub_era_ref
					END AS sub_era_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_era' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.sub_era_indexed
                                        END AS sub_era_indexed,
					CASE 
						WHEN level_sys_name_new = 'system' THEN
							NEW.id
						ELSE
							pc.system_ref
					END AS system_ref,
					CASE
                                                WHEN level_sys_name_new = 'system' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.system_indexed
                                        END AS system_indexed,
					CASE 
						WHEN level_sys_name_new = 'serie' THEN
							NEW.id
						ELSE
							pc.serie_ref
					END AS serie_ref,
					CASE
                                                WHEN level_sys_name_new = 'serie' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.serie_indexed
                                        END AS serie_indexed,
					CASE 
						WHEN level_sys_name_new = 'stage' THEN
							NEW.id
						ELSE
							pc.stage_ref
					END AS stage_ref,
					CASE
                                                WHEN level_sys_name_new = 'stage' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.stage_indexed
                                        END AS stage_indexed,
					CASE 
						WHEN level_sys_name_new = 'sub_stage' THEN
							NEW.id
						ELSE
							pc.sub_stage_ref
					END AS sub_stage_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_stage' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.sub_stage_indexed
                                        END AS sub_stage_indexed,
					CASE 
						WHEN level_sys_name_new = 'sub_level_1' THEN
							NEW.id
						ELSE
							pc.sub_level_1_ref
					END AS sub_level_1_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_level_1' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.sub_level_1_indexed
                                        END AS sub_level_1_indexed,
					CASE 
						WHEN level_sys_name_new = 'sub_level_2' THEN
							NEW.id
						ELSE
							pc.sub_level_2_ref
					END AS sub_level_2_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_level_2' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pc.sub_level_2_indexed
                                        END AS sub_level_2_indexed
				INTO
					NEW.eon_ref,
					NEW.eon_indexed,
					NEW.era_ref,
					NEW.era_indexed,
					NEW.sub_era_ref,
					NEW.sub_era_indexed,
					NEW.system_ref,
					NEW.system_indexed,
					NEW.serie_ref,
					NEW.serie_indexed,
					NEW.stage_ref,
					NEW.stage_indexed,
					NEW.sub_stage_ref,
					NEW.sub_stage_indexed,
					NEW.sub_level_1_ref,
					NEW.sub_level_1_indexed,
					NEW.sub_level_2_ref,
					NEW.sub_level_2_indexed
				FROM chronostratigraphy AS pc
				WHERE pc.id = NEW.parent_ref;
				hierarchy_ref := ARRAY[NEW.eon_ref::integer, NEW.era_ref::integer, NEW.sub_era_ref::integer, NEW.system_ref::integer, NEW.serie_ref::integer, NEW.stage_ref::integer, NEW.sub_stage_ref::integer, NEW.sub_level_1_ref::integer, NEW.sub_level_2_ref::integer];
				hierarchy_indexed := ARRAY[NEW.eon_indexed::tsvector, NEW.era_indexed::tsvector, NEW.sub_era_indexed::tsvector, NEW.system_indexed::tsvector, NEW.serie_indexed::tsvector, NEW.stage_indexed::tsvector, NEW.sub_stage_indexed::tsvector, NEW.sub_level_1_indexed::tsvector, NEW.sub_level_2_indexed::tsvector];
			ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
				SELECT
					CASE
						WHEN level_sys_name_new = 'group' THEN
							NEW.id
						ELSE
							pl.group_ref
					END as group_ref,
					CASE
                                                WHEN level_sys_name_new = 'group' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.group_indexed
                                        END as group_indexed,
					CASE
						WHEN level_sys_name_new = 'formation' THEN
							NEW.id
						ELSE
							pl.formation_ref
					END as formation_ref,
					CASE
                                                WHEN level_sys_name_new = 'formation' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.formation_indexed
                                        END as formation_indexed,
					CASE
						WHEN level_sys_name_new = 'member' THEN
							NEW.id
						ELSE
							pl.member_ref
					END as member_ref,
					CASE
                                                WHEN level_sys_name_new = 'member' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.member_indexed
                                        END as member_indexed,
					CASE
						WHEN level_sys_name_new = 'layer' THEN
							NEW.id
						ELSE
							pl.layer_ref
					END as layer_ref,
					CASE
                                                WHEN level_sys_name_new = 'layer' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.layer_indexed
                                        END as layer_indexed,
					CASE
						WHEN level_sys_name_new = 'sub_level_1' THEN
							NEW.id
						ELSE
							pl.sub_level_1_ref
					END as sub_level_1_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_level_1' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.sub_level_1_indexed
                                        END as sub_level_1_indexed,
					CASE
						WHEN level_sys_name_new = 'sub_level_2' THEN
							NEW.id
						ELSE
							pl.sub_level_2_ref
					END as sub_level_2_ref,
					CASE
                                                WHEN level_sys_name_new = 'sub_level_2' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.sub_level_2_indexed
                                        END as sub_level_2_indexed
				INTO
					NEW.group_ref,
					NEW.group_indexed,
					NEW.formation_ref,
					NEW.formation_indexed,
					NEW.member_ref,
					NEW.member_indexed,
					NEW.layer_ref,
					NEW.layer_indexed,
					NEW.sub_level_1_ref,
					NEW.sub_level_1_indexed,
					NEW.sub_level_2_ref,
					NEW.sub_level_2_indexed
				FROM lithostratigraphy AS pl
				WHERE pl.id = NEW.parent_ref;
				hierarchy_ref := ARRAY[NEW.group_ref::integer, NEW.formation_ref::integer, NEW.member_ref::integer, NEW.layer_ref::integer, NEW.sub_level_1_ref::integer, NEW.sub_level_2_ref::integer];
				hierarchy_indexed := ARRAY[NEW.group_indexed::tsvector, NEW.formation_indexed::tsvector, NEW.member_indexed::tsvector, NEW.layer_indexed::tsvector, NEW.sub_level_1_indexed::tsvector, NEW.sub_level_2_indexed::tsvector];
			ELSIF TG_TABLE_NAME = 'lithology' THEN
				SELECT
					CASE
						WHEN level_sys_name_new = 'unit_main_group' THEN
							NEW.id
						ELSE
							pl.unit_main_group_ref
					END as unit_main_group_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_main_group' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.unit_main_group_indexed
                                        END as unit_main_group_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_group' THEN
							NEW.id
						ELSE
							pl.unit_group_ref
					END as unit_group_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_group' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.unit_group_indexed
                                        END as unit_group_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_sub_group' THEN
							NEW.id
						ELSE
							pl.unit_sub_group_ref
					END as unit_sub_group_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_sub_group' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.unit_sub_group_indexed
                                        END as unit_sub_group_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_rock' THEN
							NEW.id
						ELSE
							pl.unit_rock_ref
					END as unit_rock_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_rock' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pl.unit_rock_indexed
                                        END as unit_rock_indexed
				INTO
					NEW.unit_main_group_ref,
					NEW.unit_main_group_indexed,
					NEW.unit_group_ref,
					NEW.unit_group_indexed,
					NEW.unit_sub_group_ref,
					NEW.unit_sub_group_indexed,
					NEW.unit_rock_ref,
					NEW.unit_rock_indexed
				FROM lithology AS pl
				WHERE pl.id = NEW.parent_ref;
				hierarchy_ref := ARRAY[NEW.unit_main_group_ref::integer, NEW.unit_group_ref::integer, NEW.unit_sub_group_ref::integer, NEW.unit_rock_ref::integer];
				hierarchy_indexed := ARRAY[NEW.unit_main_group_indexed::tsvector, NEW.unit_group_indexed::tsvector, NEW.unit_sub_group_indexed::tsvector, NEW.unit_rock_indexed::tsvector];
			ELSIF TG_TABLE_NAME = 'mineralogy' THEN
				SELECT
					CASE
						WHEN level_sys_name_new = 'unit_class' THEN
							NEW.id
						ELSE
							pm.unit_class_ref
					END as unit_class_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_class' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pm.unit_class_indexed
                                        END as unit_class_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_division' THEN
							NEW.id
						ELSE
							pm.unit_division_ref
					END as unit_division_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_division' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pm.unit_division_indexed
                                        END as unit_division_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_family' THEN
							NEW.id
						ELSE
							pm.unit_family_ref
					END as unit_family_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_family' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pm.unit_family_indexed
                                        END as unit_family_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_group' THEN
							NEW.id
						ELSE
							pm.unit_group_ref
					END as unit_group_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_group' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pm.unit_group_indexed
                                        END as unit_group_indexed,
					CASE
						WHEN level_sys_name_new = 'unit_variety' THEN
							NEW.id
						ELSE
							pm.unit_variety_ref
					END as unit_variety_ref,
					CASE
                                                WHEN level_sys_name_new = 'unit_variety' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pm.unit_variety_indexed
                                        END as unit_variety_indexed
				INTO
					NEW.unit_class_ref,
					NEW.unit_class_indexed,
					NEW.unit_division_ref,
					NEW.unit_division_indexed,
					NEW.unit_family_ref,
					NEW.unit_family_indexed,
					NEW.unit_group_ref,
					NEW.unit_group_indexed,
					NEW.unit_variety_ref,
					NEW.unit_variety_indexed
				FROM mineralogy AS pm
				WHERE pm.id = NEW.parent_ref;
				hierarchy_ref := ARRAY[NEW.unit_class_ref::integer, NEW.unit_division_ref::integer, NEW.unit_family_ref::integer, NEW.unit_group_ref::integer, NEW.unit_variety_ref::integer];
				hierarchy_indexed := ARRAY[NEW.unit_class_indexed::tsvector, NEW.unit_division_indexed::tsvector, NEW.unit_family_indexed::tsvector, NEW.unit_group_indexed::tsvector, NEW.unit_variety_indexed::tsvector];
			ELSIF TG_TABLE_NAME = 'taxonomy' THEN
				SELECT
					CASE
						WHEN level_sys_name_new = 'domain' THEN
							NEW.id
						ELSE
							pt.domain_ref
					END as domain_ref,
					CASE
						WHEN level_sys_name_new = 'domain' THEN
							NEW.name_indexed
						ELSE
							pt.domain_indexed
					END as domain_indexed,
					CASE
                                                WHEN level_sys_name_new = 'kingdom' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.kingdom_ref
                                        END as kingdom_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'kingdom' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.kingdom_indexed
                                        END as kingdom_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_phylum' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_phylum_ref
                                        END as super_phylum_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_phylum' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_phylum_indexed
                                        END as super_phylum_indexed,
					CASE
                                                WHEN level_sys_name_new = 'phylum' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.phylum_ref
                                        END as phylum_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'phylum' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.phylum_indexed
                                        END as phylum_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_phylum' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_phylum_ref
                                        END as sub_phylum_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_phylum' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_phylum_indexed
                                        END as sub_phylum_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_phylum' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_phylum_ref
                                        END as infra_phylum_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_phylum' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_phylum_indexed
                                        END as infra_phylum_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_cohort_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_cohort_botany_ref
                                        END as super_cohort_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_cohort_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_cohort_botany_indexed
                                        END as super_cohort_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'cohort_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.cohort_botany_ref
                                        END as cohort_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'cohort_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.cohort_botany_indexed
                                        END as cohort_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_cohort_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_cohort_botany_ref
                                        END as sub_cohort_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_cohort_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_cohort_botany_indexed
                                        END as sub_cohort_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_cohort_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_cohort_botany_ref
                                        END as infra_cohort_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_cohort_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_cohort_botany_indexed
                                        END as infra_cohort_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_class' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_class_ref
                                        END as super_class_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_class' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_class_indexed
                                        END as super_class_indexed,
					CASE
                                                WHEN level_sys_name_new = 'class' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.class_ref
                                        END as class_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'class' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.class_indexed
                                        END as class_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_class' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_class_ref
                                        END as sub_class_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_class' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_class_indexed
                                        END as sub_class_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_class' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_class_ref
                                        END as infra_class_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_class' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_class_indexed
                                        END as infra_class_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_division' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_division_ref
                                        END as super_division_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_division' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_division_indexed
                                        END as super_division_indexed,
					CASE
                                                WHEN level_sys_name_new = 'division' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.division_ref
                                        END as division_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'division' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.division_indexed
                                        END as division_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_division' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_division_ref
                                        END as sub_division_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_division' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_division_indexed
                                        END as sub_division_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_division' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_division_ref
                                        END as infra_division_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_division' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_division_indexed
                                        END as infra_division_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_legion' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_legion_ref
                                        END as super_legion_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_legion' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_legion_indexed
                                        END as super_legion_indexed,
					CASE
                                                WHEN level_sys_name_new = 'legion' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.legion_ref
                                        END as legion_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'legion' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.legion_indexed
                                        END as legion_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_legion' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_legion_ref
                                        END as sub_legion_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_legion' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_legion_indexed
                                        END as sub_legion_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_legion' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_legion_ref
                                        END as infra_legion_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_legion' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_legion_indexed
                                        END as infra_legion_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_cohort_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_cohort_zoology_ref
                                        END as super_cohort_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_cohort_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_cohort_zoology_indexed
                                        END as super_cohort_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'cohort_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.cohort_zoology_ref
                                        END as cohort_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'cohort_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.cohort_zoology_indexed
                                        END as cohort_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_cohort_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_cohort_zoology_ref
                                        END as sub_cohort_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_cohort_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_cohort_zoology_indexed
                                        END as sub_cohort_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_cohort_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_cohort_zoology_ref
                                        END as infra_cohort_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_cohort_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_cohort_zoology_indexed
                                        END as infra_cohort_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_order' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_order_ref
                                        END as super_order_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_order' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_order_indexed
                                        END as super_order_indexed,
					CASE
                                                WHEN level_sys_name_new = 'order' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.order_ref
                                        END as order_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'order' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.order_indexed
                                        END as order_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_order' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_order_ref
                                        END as sub_order_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_order' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_order_indexed
                                        END as sub_order_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_order' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_order_ref
                                        END as infra_order_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_order' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_order_indexed
                                        END as infra_order_indexed,
					CASE
                                                WHEN level_sys_name_new = 'section_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.section_zoology_ref
                                        END as section_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'section_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.section_zoology_indexed
                                        END as section_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_section_zoology' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_section_zoology_ref
                                        END as sub_section_zoology_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_section_zoology' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_section_zoology_indexed
                                        END as sub_section_zoology_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_family' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_family_ref
                                        END as super_family_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_family' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_family_indexed
                                        END as super_family_indexed,
					CASE
                                                WHEN level_sys_name_new = 'family' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.family_ref
                                        END as family_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'family' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.family_indexed
                                        END as family_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_family' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_family_ref
                                        END as sub_family_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_family' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_family_indexed
                                        END as sub_family_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_family' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_family_ref
                                        END as infra_family_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_family' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_family_indexed
                                        END as infra_family_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_tribe' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_tribe_ref
                                        END as super_tribe_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_tribe' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_tribe_indexed
                                        END as super_tribe_indexed,
					CASE
                                                WHEN level_sys_name_new = 'tribe' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.tribe_ref
                                        END as tribe_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'tribe' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.tribe_indexed
                                        END as tribe_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_tribe' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_tribe_ref
                                        END as sub_tribe_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_tribe' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_tribe_indexed
                                        END as sub_tribe_indexed,
					CASE
                                                WHEN level_sys_name_new = 'infra_tribe' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.infra_tribe_ref
                                        END as infra_tribe_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'infra_tribe' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.infra_tribe_indexed
                                        END as infra_tribe_indexed,
					CASE
                                                WHEN level_sys_name_new = 'genus' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.genus_ref
                                        END as genus_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'genus' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.genus_indexed
                                        END as genus_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_genus' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_genus_ref
                                        END as sub_genus_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_genus' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_genus_indexed
                                        END as sub_genus_indexed,
					CASE
                                                WHEN level_sys_name_new = 'section_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.section_botany_ref
                                        END as section_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'section_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.section_botany_indexed
                                        END as section_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_section_botany' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_section_botany_ref
                                        END as sub_section_botany_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_section_botany' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_section_botany_indexed
                                        END as sub_section_botany_indexed,
					CASE
                                                WHEN level_sys_name_new = 'serie' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.serie_ref
                                        END as serie_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'serie' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.serie_indexed
                                        END as serie_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_serie' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_serie_ref
                                        END as sub_serie_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_serie' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_serie_indexed
                                        END as sub_serie_indexed,
					CASE
                                                WHEN level_sys_name_new = 'super_species' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.super_species_ref
                                        END as super_species_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'super_species' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.super_species_indexed
                                        END as super_species_indexed,
					CASE
                                                WHEN level_sys_name_new = 'species' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.species_ref
                                        END as species_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'species' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.species_indexed
                                        END as species_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_species' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_species_ref
                                        END as sub_species_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_species' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_species_indexed
                                        END as sub_species_indexed,
					CASE
                                                WHEN level_sys_name_new = 'variety' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.variety_ref
                                        END as variety_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'variety' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.variety_indexed
                                        END as variety_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_variety' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_variety_ref
                                        END as sub_variety_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_variety' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_variety_indexed
                                        END as sub_variety_indexed,
					CASE
                                                WHEN level_sys_name_new = 'form' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.form_ref
                                        END as form_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'form' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.form_indexed
                                        END as form_indexed,
					CASE
                                                WHEN level_sys_name_new = 'sub_form' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.sub_form_ref
                                        END as sub_form_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'sub_form' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.sub_form_indexed
                                        END as sub_form_indexed,
					CASE
                                                WHEN level_sys_name_new = 'abberans' THEN
                                                        NEW.id
                                                ELSE
                                                        pt.abberans_ref
                                        END as abberans_ref,
                                        CASE
                                                WHEN level_sys_name_new = 'abberans' THEN
                                                        NEW.name_indexed
                                                ELSE
                                                        pt.abberans_indexed
                                        END as abberans_indexed
				INTO
					NEW.domain_ref,
					NEW.domain_indexed,
					NEW.kingdom_ref,
					NEW.kingdom_indexed,
					NEW.super_phylum_ref,
					NEW.super_phylum_indexed,
					NEW.phylum_ref,
					NEW.phylum_indexed,
					NEW.sub_phylum_ref,
					NEW.sub_phylum_indexed,
					NEW.infra_phylum_ref,
					NEW.infra_phylum_indexed,
					NEW.super_cohort_botany_ref,
					NEW.super_cohort_botany_indexed,
					NEW.cohort_botany_ref,
					NEW.cohort_botany_indexed,
					NEW.sub_cohort_botany_ref,
					NEW.sub_cohort_botany_indexed,
					NEW.infra_cohort_botany_ref,
					NEW.infra_cohort_botany_indexed,
					NEW.super_class_ref,
					NEW.super_class_indexed,
					NEW.class_ref,
					NEW.class_indexed,
					NEW.sub_class_ref,
					NEW.sub_class_indexed,
					NEW.infra_class_ref,
					NEW.infra_class_indexed,
					NEW.super_division_ref,
					NEW.super_division_indexed,
					NEW.division_ref,
					NEW.division_indexed,
					NEW.sub_division_ref,
					NEW.sub_division_indexed,
					NEW.infra_division_ref,
					NEW.infra_division_indexed,
					NEW.super_legion_ref,
					NEW.super_legion_indexed,
					NEW.legion_ref,
					NEW.legion_indexed,
					NEW.sub_legion_ref,
					NEW.sub_legion_indexed,
					NEW.infra_legion_ref,
					NEW.infra_legion_indexed,
					NEW.super_cohort_zoology_ref,
					NEW.super_cohort_zoology_indexed,
					NEW.cohort_zoology_ref,
					NEW.cohort_zoology_indexed,
					NEW.sub_cohort_zoology_ref,
					NEW.sub_cohort_zoology_indexed,
					NEW.infra_cohort_zoology_ref,
					NEW.infra_cohort_zoology_indexed,
					NEW.super_order_ref,
					NEW.super_order_indexed,
					NEW.order_ref,
					NEW.order_indexed,
					NEW.sub_order_ref,
					NEW.sub_order_indexed,
					NEW.infra_order_ref,
					NEW.infra_order_indexed,
					NEW.section_zoology_ref,
					NEW.section_zoology_indexed,
					NEW.sub_section_zoology_ref,
					NEW.sub_section_zoology_indexed,
					NEW.super_family_ref,
					NEW.super_family_indexed,
					NEW.family_ref,
					NEW.family_indexed,
					NEW.sub_family_ref,
					NEW.sub_family_indexed,
					NEW.infra_family_ref,
					NEW.infra_family_indexed,
					NEW.super_tribe_ref,
					NEW.super_tribe_indexed,
					NEW.tribe_ref,
					NEW.tribe_indexed,
					NEW.sub_tribe_ref,
					NEW.sub_tribe_indexed,
					NEW.infra_tribe_ref,
					NEW.infra_tribe_indexed,
					NEW.genus_ref,
					NEW.genus_indexed,
					NEW.sub_genus_ref,
					NEW.sub_genus_indexed,
					NEW.section_botany_ref,
					NEW.section_botany_indexed,
					NEW.sub_section_botany_ref,
					NEW.sub_section_botany_indexed,
					NEW.serie_ref,
					NEW.serie_indexed,
					NEW.sub_serie_ref,
					NEW.sub_serie_indexed,
					NEW.super_species_ref,
					NEW.super_species_indexed,
					NEW.species_ref,
					NEW.species_indexed,
					NEW.sub_species_ref,
					NEW.sub_species_indexed,
					NEW.variety_ref,
					NEW.variety_indexed,
					NEW.sub_variety_ref,
					NEW.sub_variety_indexed,
					NEW.form_ref,
					NEW.form_indexed,
					NEW.sub_form_ref,
					NEW.sub_form_indexed,
					NEW.abberans_ref,
					NEW.abberans_indexed
				FROM taxonomy AS pt
				WHERE pt.id = NEW.parent_ref;
				hierarchy_ref := ARRAY[NEW.domain_ref::integer, NEW.kingdom_ref::integer, NEW.super_phylum_ref::integer, NEW.phylum_ref::integer, NEW.sub_phylum_ref::integer, NEW.infra_phylum_ref::integer, NEW.super_cohort_botany_ref::integer, NEW.cohort_botany_ref::integer, NEW.sub_cohort_botany_ref::integer, NEW.infra_cohort_botany_ref::integer, NEW.super_class_ref::integer, NEW.class_ref::integer, NEW.sub_class_ref::integer, NEW.infra_class_ref::integer, NEW.super_division_ref::integer, NEW.division_ref::integer, NEW.sub_division_ref::integer, NEW.infra_division_ref::integer, NEW.super_legion_ref::integer, NEW.legion_ref::integer, NEW.sub_legion_ref::integer, NEW.infra_legion_ref::integer, NEW.super_cohort_zoology_ref::integer, NEW.cohort_zoology_ref::integer, NEW.sub_cohort_zoology_ref::integer, NEW.infra_cohort_zoology_ref::integer, NEW.super_order_ref::integer, NEW.order_ref::integer, NEW.sub_order_ref::integer, NEW.infra_order_ref::integer, NEW.section_zoology_ref::integer, NEW.sub_section_zoology_ref::integer, NEW.super_family_ref::integer, NEW.family_ref::integer, NEW.sub_family_ref::integer, NEW.infra_family_ref::integer, NEW.super_tribe_ref::integer, NEW.tribe_ref::integer, NEW.sub_tribe_ref::integer, NEW.infra_tribe_ref::integer, NEW.genus_ref::integer, NEW.sub_genus_ref::integer, NEW.section_botany_ref::integer, NEW.sub_section_botany_ref::integer, NEW.serie_ref::integer, NEW.sub_serie_ref::integer, NEW.super_species_ref::integer, NEW.species_ref::integer, NEW.sub_species_ref::integer, NEW.variety_ref::integer, NEW.sub_variety_ref::integer, NEW.form_ref::integer, NEW.sub_form_ref::integer, NEW.abberans_ref::integer];
				hierarchy_indexed := ARRAY[NEW.domain_indexed::tsvector, NEW.kingdom_indexed::tsvector, NEW.super_phylum_indexed::tsvector, NEW.phylum_indexed::tsvector, NEW.sub_phylum_indexed::tsvector, NEW.infra_phylum_indexed::tsvector, NEW.super_cohort_botany_indexed::tsvector, NEW.cohort_botany_indexed::tsvector, NEW.sub_cohort_botany_indexed::tsvector, NEW.infra_cohort_botany_indexed::tsvector, NEW.super_class_indexed::tsvector, NEW.class_indexed::tsvector, NEW.sub_class_indexed::tsvector, NEW.infra_class_indexed::tsvector, NEW.super_division_indexed::tsvector, NEW.division_indexed::tsvector, NEW.sub_division_indexed::tsvector, NEW.infra_division_indexed::tsvector, NEW.super_legion_indexed::tsvector, NEW.legion_indexed::tsvector, NEW.sub_legion_indexed::tsvector, NEW.infra_legion_indexed::tsvector, NEW.super_cohort_zoology_indexed::tsvector, NEW.cohort_zoology_indexed::tsvector, NEW.sub_cohort_zoology_indexed::tsvector, NEW.infra_cohort_zoology_indexed::tsvector, NEW.super_order_indexed::tsvector, NEW.order_indexed::tsvector, NEW.sub_order_indexed::tsvector, NEW.infra_order_indexed::tsvector, NEW.section_zoology_indexed::tsvector, NEW.sub_section_zoology_indexed::tsvector, NEW.super_family_indexed::tsvector, NEW.family_indexed::tsvector, NEW.sub_family_indexed::tsvector, NEW.infra_family_indexed::tsvector, NEW.super_tribe_indexed::tsvector, NEW.tribe_indexed::tsvector, NEW.sub_tribe_indexed::tsvector, NEW.infra_tribe_indexed::tsvector, NEW.genus_indexed::tsvector, NEW.sub_genus_indexed::tsvector, NEW.section_botany_indexed::tsvector, NEW.sub_section_botany_indexed::tsvector, NEW.serie_indexed::tsvector, NEW.sub_serie_indexed::tsvector, NEW.super_species_indexed::tsvector, NEW.species_indexed::tsvector, NEW.sub_species_indexed::tsvector, NEW.variety_indexed::tsvector, NEW.sub_variety_indexed::tsvector, NEW.form_indexed::tsvector, NEW.sub_form_indexed::tsvector, NEW.abberans_indexed::tsvector];
			END IF;
			IF NOT fct_cpy_update_children_when_parent_updated (TG_TABLE_NAME::varchar, NEW.id::integer, OLD.level_ref::integer, NEW.level_ref::integer, hierarchy_ref, hierarchy_indexed) THEN
				RAISE EXCEPTION 'Impossible to update children ! Update of parent_ref and/or level_ref of current unit aborted !';
			END IF;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

/**
* fct_chk_peopleType
* When removing author flag for a people, check if he is not referenced as author in a catalogue
*/
CREATE OR REPLACE FUNCTION fct_chk_peopleType() RETURNS TRIGGER
AS $$
DECLARE
	still_referenced boolean;
BEGIN
	/** AUTHOR FLAG IS 2 **/
	IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 2)>0 )  THEN
		SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='authors';
		IF still_referenced THEN
			RAISE EXCEPTION 'Author still used as author.';
		END IF;
	END IF;

	/** Expert Flag is 8 **/
        IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 8)>0 )  THEN
                SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='experts';
                IF still_referenced THEN
                        RAISE EXCEPTION 'Expert still used as expert.';
                END IF;
        END IF;

	RETURN NEW;
END;
$$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION fct_chk_AreRole() RETURNS TRIGGER
AS $$
DECLARE
	are_not_author boolean;
BEGIN
	IF NEW.people_type = 'authors' THEN
		IF TG_OP ='UPDATE' THEN
			IF OLD.people_ref = NEW.people_ref THEN 
				RETURN NEW;
			END IF;
		END IF;
	
		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 2)=0 AND id=NEW.people_ref;
		
		IF are_not_author THEN
			RAISE EXCEPTION 'Author must be defined as author.';
		END IF;
		
	ELSIF NEW.people_type = 'experts' THEN
		IF TG_OP ='UPDATE' THEN
			IF OLD.people_ref = NEW.people_ref THEN 
				RETURN NEW;
			END IF;
		END IF;
	
		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 8)=0 AND id=NEW.people_ref;
		
		IF are_not_author THEN
			RAISE EXCEPTION 'Experts must be defined as expert.';
		END IF;
	END IF;

	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_chk_ReferencedRecord(referenced_relation varchar,record_id integer) RETURNS boolean
AS $$
DECLARE
	rec_exists boolean;
BEGIN
 	EXECUTE 'SELECT count(id)>0 FROM ' || quote_ident(referenced_relation) || ' WHERE id=' || quote_literal(record_id::varchar) INTO rec_exists;
	RETURN rec_exists;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_clr_title() RETURNS TRIGGER
AS $$
BEGIN
	IF NEW.is_physical THEN
		IF TG_OP ='UPDATE' THEN
			IF OLD.gender != NEW.gender THEN
				IF ( NEW.gender='M' AND NEW.title='Mrs') OR ( NEW.gender='F' AND NEW.title='Mr') THEN
					NEW.title='';
				END IF;
			END IF;
		END IF;
		
		IF NEW.title is NULL OR NEW.title = '' THEN
			IF NEW.gender = 'M' THEN
				NEW.title := 'Mr';
			ELSE
				NEW.title := 'Mrs';
			END IF;
		END IF;
	ELSE
		NEW.title := '';
	END IF;
	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_FormattedName() RETURNS TRIGGER
AS $$
BEGIN
	IF TG_OP ='UPDATE' THEN
		IF NEW.family_name = OLD.family_name AND NEW.given_name = OLD.given_name AND NEW.title = OLD.title THEN
			RETURN NEW;
		END IF;
	END IF;
	
	IF NEW.is_physical THEN
		NEW.formated_name := COALESCE(NEW.family_name,'') || ' ' || COALESCE(NEW.given_name,'') || ' (' || NEW.title || ')';
	ELSE
		NEW.formated_name := NEW.family_name;
	END IF;
	NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
	NEW.formated_name_ts := to_tsvector('simple', NEW.formated_name);
	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

/*
fct_clr_SavedSpecimens
Remove specimen in saved specimen.
*/
CREATE OR REPLACE FUNCTION fct_clr_SavedSpecimens() RETURNS TRIGGER
As $$
BEGIN
	UPDATE my_saved_specimens SET specimen_ids = array_to_string(fct_remove_array_elem(string_to_array(specimen_ids,',')::integer[],OLD.id),',')
		WHERE string_to_array(specimen_ids,',')::integer[] @> ARRAY[OLD.id];
	RETURN OLD;
END;
$$
LANGUAGE plpgsql;

/*
fct_cpy_update_path
When insertion of a new hierarchical unit is done, construct automatically the path of this unit.
When <levels_structures>_ref of a unit are updated, the path have to be reconstructed
*/
CREATE OR REPLACE FUNCTION fct_cpy_update_path() RETURNS TRIGGER
AS $$
DECLARE
	booContinue boolean default true;
BEGIN
	IF TG_OP = 'UPDATE' THEN
		IF TG_TABLE_NAME = 'chronostratigraphy' THEN
			booContinue := 	((OLD.eon_ref <> NEW.eon_ref) OR (OLD.era_ref <> NEW.era_ref) OR (OLD.sub_era_ref <> NEW.sub_era_ref) OR (OLD.system_ref <> NEW.system_ref) OR (OLD.serie_ref <> NEW.serie_ref) OR (OLD.stage_ref <> NEW.stage_ref) OR (OLD.sub_stage_ref <> NEW.sub_stage_ref) OR (OLD.sub_level_1_ref <> NEW.sub_level_1_ref) OR (OLD.sub_level_2_ref <> NEW.sub_level_2_ref));
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			booContinue := 	((OLD.group_ref <> NEW.group_ref) OR (OLD.formation_ref <> NEW.formation_ref) OR (OLD.member_ref <> NEW.member_ref) OR (OLD.layer_ref <> NEW.layer_ref) OR (OLD.sub_level_1_ref <> NEW.sub_level_1_ref) OR (OLD.sub_level_2_ref <> NEW.sub_level_2_ref));
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			booContinue := 	((OLD.unit_main_group_ref <> NEW.unit_main_group_ref) OR (OLD.unit_group_ref <> NEW.unit_group_ref) OR (OLD.unit_sub_group_ref <> NEW.unit_sub_group_ref) OR (OLD.unit_rock_ref <> NEW.unit_rock_ref));
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			booContinue := 	((OLD.unit_class_ref <> NEW.unit_class_ref) OR (OLD.unit_division_ref <> NEW.unit_division_ref) OR (OLD.unit_family_ref <> NEW.unit_family_ref) OR (OLD.unit_group_ref <> NEW.unit_group_ref) OR (OLD.unit_variety_ref <> NEW.unit_variety_ref));
		ELSIF TG_TABLE_NAME = 'taxonomy' THEN
			booContinue := 	((OLD.domain_ref <> NEW.domain_ref) OR (OLD.kingdom_ref <> NEW.kingdom_ref) OR 
					 (OLD.super_phylum_ref <> NEW.super_phylum_ref) OR (OLD.phylum_ref <> NEW.phylum_ref) OR (OLD.sub_phylum_ref <> NEW.sub_phylum_ref) OR (OLD.infra_phylum_ref <> NEW.infra_phylum_ref) OR
					 (OLD.super_cohort_botany_ref <> NEW.super_cohort_botany_ref) OR (OLD.cohort_botany_ref <> NEW.cohort_botany_ref) OR (OLD.sub_cohort_botany_ref <> NEW.sub_cohort_botany_ref) OR (OLD.infra_cohort_botany_ref <> NEW.infra_cohort_botany_ref) OR
					 (OLD.super_class_ref <> NEW.super_class_ref) OR (OLD.class_ref <> NEW.class_ref) OR (OLD.sub_class_ref <> NEW.sub_class_ref) OR (OLD.infra_class_ref <> NEW.infra_class_ref) OR
					 (OLD.super_division_ref <> NEW.super_division_ref) OR (OLD.division_ref <> NEW.division_ref) OR (OLD.sub_division_ref <> NEW.sub_division_ref) OR (OLD.infra_division_ref <> NEW.infra_division_ref) OR
					 (OLD.super_legion_ref <> NEW.super_legion_ref) OR (OLD.legion_ref <> NEW.legion_ref) OR (OLD.sub_legion_ref <> NEW.sub_legion_ref) OR (OLD.infra_legion_ref <> NEW.infra_legion_ref) OR
					 (OLD.super_cohort_zoology_ref <> NEW.super_cohort_zoology_ref) OR (OLD.cohort_zoology_ref <> NEW.cohort_zoology_ref) OR (OLD.sub_cohort_zoology_ref <> NEW.sub_cohort_zoology_ref) OR (OLD.infra_cohort_zoology_ref <> NEW.infra_cohort_zoology_ref) OR
					 (OLD.super_order_ref <> NEW.super_order_ref) OR (OLD.order_ref <> NEW.order_ref) OR (OLD.sub_order_ref <> NEW.sub_order_ref) OR (OLD.infra_order_ref <> NEW.infra_order_ref) OR
					 (OLD.section_zoology_ref <> NEW.section_zoology_ref) OR (OLD.sub_section_zoology_ref <> NEW.sub_section_zoology_ref) OR
					 (OLD.super_family_ref <> NEW.super_family_ref) OR (OLD.family_ref <> NEW.family_ref) OR (OLD.sub_family_ref <> NEW.sub_family_ref) OR (OLD.infra_family_ref <> NEW.infra_family_ref) OR
					 (OLD.super_tribe_ref <> NEW.super_tribe_ref) OR (OLD.tribe_ref <> NEW.tribe_ref) OR (OLD.sub_tribe_ref <> NEW.sub_tribe_ref) OR (OLD.infra_tribe_ref <> NEW.infra_tribe_ref) OR
					 (OLD.genus_ref <> NEW.genus_ref) OR (OLD.sub_genus_ref <> NEW.sub_genus_ref) OR
					 (OLD.section_botany_ref <> NEW.section_botany_ref) OR (OLD.sub_section_botany_ref <> NEW.sub_section_botany_ref) OR
					 (OLD.serie_ref <> NEW.serie_ref) OR (OLD.sub_serie_ref <> NEW.sub_serie_ref) OR
					 (OLD.super_species_ref <> NEW.super_species_ref) OR (OLD.species_ref <> NEW.species_ref) OR (OLD.sub_species_ref <> NEW.sub_species_ref) OR
					 (OLD.variety_ref <> NEW.variety_ref) OR (OLD.sub_variety_ref <> NEW.sub_variety_ref) OR
					 (OLD.form_ref <> NEW.form_ref) OR (OLD.sub_form_ref <> NEW.sub_form_ref) OR
					 (OLD.abberans_ref <> NEW.abberans_ref)
					);
		END IF;
	END IF;
	IF booContinue THEN
		IF TG_TABLE_NAME = 'chronostratigraphy' THEN
			SELECT replace(replace('/' || NEW.eon_ref::varchar || '/' || NEW.era_ref::varchar || '/' || NEW.sub_era_ref::varchar || '/' || 
						     NEW.system_ref::varchar || '/' || NEW.serie_ref::varchar || '/' || NEW.stage_ref::varchar || '/' || 
						     NEW.sub_stage_ref::varchar || '/' || NEW.sub_level_1_ref::varchar || '/' || 
						     NEW.sub_level_2_ref::varchar || '/',
						     '/0',
						     ''
						    ),
					      '/' || NEW.id::varchar || '/',
					      '/'
					     )
			INTO NEW.path;
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			SELECT replace(replace('/' || NEW.group_ref::varchar || '/' || NEW.formation_ref::varchar || '/' || NEW.member_ref::varchar || 
						     '/' || NEW.layer_ref::varchar || '/' || 
						     NEW.sub_level_1_ref::varchar || '/' || NEW.sub_level_2_ref::varchar || '/',
						     '/0',
						     ''
						    ),
					      '/' || NEW.id::varchar || '/',
					      '/'
					     )
			INTO NEW.path;
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			SELECT replace(replace('/' || NEW.unit_class_ref::varchar || '/' || NEW.unit_division_ref::varchar || '/' || 
						     NEW.unit_family_ref::varchar || '/' || NEW.unit_group_ref::varchar || '/' || 
						     NEW.unit_variety_ref::varchar || '/',
						     '/0',
						     ''
						    ),
					      '/' || NEW.id::varchar || '/',
					      '/'
					     )
			INTO NEW.path;
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			SELECT replace(replace('/' || NEW.unit_main_group_ref::varchar || '/' || NEW.unit_group_ref::varchar || '/' || NEW.unit_sub_group_ref::varchar || 
						     '/' || NEW.unit_rock_ref::varchar || '/',
						     '/0',
						     ''
						    ),
					      '/' || NEW.id::varchar || '/',
					      '/'
					     )
			INTO NEW.path;
		ELSIF TG_TABLE_NAME = 'taxonomy' THEN
			SELECT replace(replace('/' || NEW.domain_ref::varchar || '/' || NEW.kingdom_ref::varchar || '/' || 
						     NEW.super_phylum_ref::varchar || '/' || NEW.phylum_ref::varchar || '/' || NEW.sub_phylum_ref::varchar || '/' || NEW.infra_phylum_ref::varchar || '/' ||
						     NEW.super_cohort_botany_ref::varchar || '/' || NEW.cohort_botany_ref::varchar || '/' || NEW.sub_cohort_botany_ref::varchar || '/' || NEW.infra_cohort_botany_ref::varchar || '/' ||
						     NEW.super_class_ref::varchar || '/' || NEW.class_ref::varchar || '/' || NEW.sub_class_ref::varchar || '/' || NEW.infra_class_ref::varchar || '/' ||
						     NEW.super_division_ref::varchar || '/' || NEW.division_ref::varchar || '/' || NEW.sub_division_ref::varchar || '/' || NEW.infra_division_ref::varchar || '/' ||
						     NEW.super_legion_ref::varchar || '/' || NEW.legion_ref::varchar || '/' || NEW.sub_legion_ref::varchar || '/' || NEW.infra_legion_ref::varchar || '/' ||
						     NEW.super_cohort_zoology_ref::varchar || '/' || NEW.cohort_zoology_ref::varchar || '/' || NEW.sub_cohort_zoology_ref::varchar || '/' || NEW.infra_cohort_zoology_ref::varchar || '/' ||
						     NEW.super_order_ref::varchar || '/' || NEW.order_ref::varchar || '/' || NEW.sub_order_ref::varchar || '/' || NEW.infra_order_ref::varchar || '/' ||
						     NEW.section_zoology_ref::varchar || '/' || NEW.sub_section_zoology_ref::varchar || '/' ||
						     NEW.super_family_ref::varchar || '/' || NEW.family_ref::varchar || '/' || NEW.sub_family_ref::varchar || '/' || NEW.infra_family_ref::varchar || '/' ||
						     NEW.super_tribe_ref::varchar || '/' || NEW.tribe_ref::varchar || '/' || NEW.sub_tribe_ref::varchar || '/' || NEW.infra_tribe_ref::varchar || '/' ||
						     NEW.genus_ref::varchar || '/' || NEW.sub_genus_ref::varchar || '/' ||
						     NEW.section_botany_ref::varchar || '/' || NEW.sub_section_botany_ref::varchar || '/' ||
						     NEW.serie_ref::varchar || '/' || NEW.sub_serie_ref::varchar || '/' ||
						     NEW.super_species_ref::varchar || '/' || NEW.species_ref::varchar || '/' || NEW.sub_species_ref::varchar || '/' ||
						     NEW.variety_ref::varchar || '/' || NEW.sub_variety_ref::varchar || '/' ||
						     NEW.form_ref::varchar || '/' || NEW.sub_form_ref::varchar || '/' ||
						     NEW.abberans_ref::varchar || '/',
						     '/0',
						     ''
						    ),
					      '/' || NEW.id::varchar || '/',
					      '/'
					     )
			INTO NEW.path;
		END IF;
	END IF;

	/** PERMIT TO ADD 0 with triggers in tests **/
	IF NEW.path IS NULL AND NEW.id < 1 THEN
	  NEW.path := '/';
	END IF;
	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_path() RETURNS TRIGGER
AS $$
BEGIN
	IF TG_OP = 'INSERT' THEN
		IF (TG_TABLE_NAME::text = 'multimedia' OR TG_TABLE_NAME::text = 'collections' OR TG_TABLE_NAME::text = 'gtu' OR TG_TABLE_NAME::text = 'habitats') THEN
	            IF NEW.parent_ref IS NULL THEN
        	        NEW.path ='/';
	            ELSE
        	        IF TG_TABLE_NAME::text = 'multimedia' THEN
                	    SELECT path || id || '/' INTO STRICT NEW.path FROM multimedia WHERE
                        	id=NEW.parent_ref;
	                ELSIF TG_TABLE_NAME::text = 'collections' THEN
        	            SELECT path || id || '/' INTO STRICT NEW.path FROM collections WHERE
                	        id=NEW.parent_ref;
			ELSIF TG_TABLE_NAME::text = 'gtu' THEN
			    SELECT path || id || '/' INTO STRICT NEW.path FROM gtu WHERE
                                id=NEW.parent_ref;
			ELSE
			    SELECT path || id || '/' INTO STRICT NEW.path FROM habitats WHERE
                                id=NEW.parent_ref;
	                END IF;
        	    END IF;
		ELSIF TG_TABLE_NAME::text = 'people_relationships' THEN
			SELECT path || NEW.person_1_ref || '/' INTO NEW.path FROM people_relationships WHERE
				person_2_ref=NEW.person_1_ref;
			IF NEW.path is NULL THEN
		                NEW.path = '/' || NEW.person_1_ref || '/';
		        END IF;
		END IF;
	ELSIF TG_OP = 'UPDATE' THEN
        IF (TG_TABLE_NAME::text = 'multimedia' OR TG_TABLE_NAME::text = 'collections' OR TG_TABLE_NAME::text = 'gtu' OR TG_TABLE_NAME::text = 'habitats') THEN
            IF NEW.parent_ref IS DISTINCT FROM OLD.parent_ref THEN
                IF NEW.parent_ref IS NULL THEN
                    NEW.path ='/';
                ELSEIF COALESCE(OLD.parent_ref,0) = COALESCE(NEW.parent_ref,0) THEN
                    RETURN NEW;
                ELSE
                    -- Change current path
                    IF TG_TABLE_NAME::text = 'multimedia' THEN
                        SELECT path || id || '/' INTO STRICT NEW.path FROM multimedia WHERE
                            id=NEW.parent_ref;
                    ELSIF TG_TABLE_NAME::text = 'collections' THEN
                        SELECT path || id || '/' INTO STRICT NEW.path FROM collections WHERE
                            id=NEW.parent_ref;
		    ELSIF TG_TABLE_NAME::text = 'gtu' THEN
			SELECT path || id || '/' INTO STRICT NEW.path FROM gtu WHERE
                            id=NEW.parent_ref;
		    ELSE
			SELECT path || id || '/' INTO STRICT NEW.path FROM habitats WHERE
                            id=NEW.parent_ref;
                    END IF;
                END IF;
                -- Change children's path
                IF TG_TABLE_NAME::text = 'multimedia' THEN
                    UPDATE multimedia SET path=replace(path, OLD.path || OLD.id || '/',  NEW.path || OLD.id || '/') WHERE path like OLD.path || OLD.id || '/%';
                ELSIF TG_TABLE_NAME::text = 'collections' THEN
                    UPDATE collections SET path=replace(path, OLD.path || OLD.id || '/',  NEW.path || OLD.id || '/'), institution_ref=NEW.institution_ref WHERE path like OLD.path || OLD.id || '/%';
		ELSIF TG_TABLE_NAME::text = 'gtu' THEN
		    UPDATE gtu SET path=replace(path, OLD.path || OLD.id || '/',  NEW.path || OLD.id || '/') WHERE path like OLD.path || OLD.id || '/%';
		ELSE
		    UPDATE habitats SET path=replace(path, OLD.path || OLD.id || '/',  NEW.path || OLD.id || '/') WHERE path like OLD.path || OLD.id || '/%';
                END IF;
            END IF;
        ELSE
            IF NEW.person_1_ref != OLD.person_1_ref OR NEW.person_2_ref != OLD.person_2_ref THEN
                SELECT path ||  NEW.person_1_ref || '/' INTO NEW.path FROM people_relationships WHERE
                    person_2_ref=NEW.person_1_ref;
                IF NEW.path is NULL THEN
                    NEW.path = '/' || NEW.person_1_ref || '/';
                END IF;
                -- AND UPDATE CHILDRENS
               UPDATE people_relationships SET path=replace(path, OLD.path, NEW.path) WHERE person_1_ref=OLD.person_2_ref;
            END IF;
        END IF;
    END IF;
    RETURN NEW;
END;
$$
language plpgsql;


CREATE OR REPLACE FUNCTION fct_trk_log_table() RETURNS TRIGGER
AS $$
DECLARE 
	user_id integer;
	trk_id bigint;
	tbl_row RECORD;
BEGIN

	SELECT COALESCE(current_setting('darwin.userid')::int,0) INTO user_id;
	
	IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
		IF TG_OP = 'INSERT' THEN
			INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time)
				VALUES (TG_TABLE_NAME::text, NEW.id, user_id, 'insert', now()) RETURNING id into trk_id;
		ELSE
			INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time)
				VALUES (TG_TABLE_NAME::text, NEW.id, user_id, 'update', now()) RETURNING id into trk_id;
		END IF;

/*		FOR tbl_row IN SELECT field_name FROM users_tables_fields_tracked WHERE referenced_relation = TG_TABLE_NAME::text AND user_ref=user_id
		LOOP
			SELECT array(SELECT ROW(NEW.*) from taxonomy) as s ;
			-- http://www.nabble.com/array-variables-td20477110.html
			EXECUTE 'INSERT INTO users_tracking_records VALUES (' || quote_literal(trk_id) || ' , ' ||
						quote_literal(tbl_row.field_name) || ' , ' ||
						' ''{ OLD.'|| tbl_row.field_name || '}' || ''',' ||
						' ''{NEW.'|| tbl_row.field_name || '}'  || ''' )';
		END LOOP;*/
		RETURN NEW;
		/*EXECUTE 'INSERT INTO users_tracking_records 
			(SELECT trk_id,
					field_name,
					NEW.'brol',
					old.'', 
				FROM users_tables_fields_tracked 
					WHERE referenced_relation =TG_TABLE_NAME::text
						AND user_ref=user_id)';*/
	ELSE
		INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time)
 			VALUES (TG_TABLE_NAME::text, OLD.id, user_id, 'delete', now());
	END IF;
	RETURN NULL;
END;
$$
LANGUAGE plpgsql;

/*
** fct_cpy_length_conversion
** Convert length into unified version (m - meter)
*/
CREATE OR REPLACE FUNCTION fct_cpy_length_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) RETURNS real
language SQL STABLE
AS 
$$
	SELECT  CASE
			WHEN $2 = 'dm' THEN
				($1)*10^(-1)
			WHEN $2 = 'ft' THEN	
				($1)*3.048*10^(-1)
			WHEN $2 = 'P' THEN
				($1)*3.24839385*10^(-1)
			WHEN $2 = 'yd' THEN
				($1)*9.144*10^(-1)
			WHEN $2 = 'cm' THEN
				($1)*10^(-2)
			WHEN $2 = 'in' THEN
				($1)*2.54*10^(-2)
			WHEN $2 = 'mm' THEN
				($1)*10^(-3)
			WHEN $2 = 'pica' THEN
				($1)*4.233333*10^(-3)
			WHEN $2 = 'p' THEN
				($1)*27.069949*10^(-3)
			WHEN $2 = 'mom' THEN
				($1)*10^(-4)
			WHEN $2 IN ('pt', 'point') THEN
				($1)*3.527778*10^(-4)
			WHEN $2 = 'mil' THEN
				($1)*2.54*10^(-5)
			WHEN $2 IN ('µm', 'µ') THEN
				($1)*10^(-6)
			WHEN $2 = 'twp' THEN
				($1)*17.639*10^(-6)
			WHEN $2 = 'cal' THEN
				($1)*254*10^(-6)
			WHEN $2 = 'nm' THEN
				($1)*10^(-9)
			WHEN $2 = 'Å' THEN
				($1)*10^(-10)
			WHEN $2 = 'pm' THEN
				($1)*10^(-12)
			WHEN $2 IN ('fm', 'fermi') THEN
				($1)*10^(-15)
			WHEN $2 = 'am' THEN
				($1)*10^(-18)
			WHEN $2 = 'zm' THEN
				($1)*10^(-21)
			WHEN $2 = 'ym' THEN
				($1)*10^(-24)
			WHEN $2 IN ('brasse', 'vadem') THEN
				($1)*1.8288
			WHEN $2 = 'fathom' THEN
				($1)*1.828804
			WHEN $2 = 'rd' THEN
				($1)*5.02921
			WHEN $2 = 'dam' THEN
				($1)*10
			WHEN $2 = 'ch' THEN
				($1)*20.11684
			WHEN $2 = 'arp' THEN
				($1)*58.471089295
			WHEN $2 IN ('hm', 'K') THEN
				($1)*10^2
			WHEN $2 = 'fur' THEN
				($1)*201.168
			WHEN $2 = 'km' THEN
				($1)*10^3
			WHEN $2 = 'mi' THEN
				($1)*1.609344*10^3
			WHEN $2 = 'nautical mi' THEN
				($1)*1.852*10^3
			WHEN $2 IN ('lieue', 'league') THEN
				($1)*4.828032*10^3
			WHEN $2 = 'mam' THEN
				($1)*10^4
			WHEN $2 = 'Mm' THEN
				($1)*10^6
			WHEN $2 = 'Gm' THEN
				($1)*10^9
			WHEN $2 = 'ua' THEN
				($1)*1.495979*10^11
			WHEN $2 = 'Tm' THEN
				($1)*10^12
			WHEN $2 = 'Pm' THEN
				($1)*10^15
			WHEN $2 = 'pc' THEN
				($1)*3.085678*10^16
			WHEN $2 IN ('ly', 'l.y.') THEN
				($1)*9.4607304725808*10^15
			WHEN $2 = 'Em' THEN
				($1)*10^18
			WHEN $2 = 'Zm' THEN
				($1)*10^21
			WHEN $2 = 'Ym' THEN
				($1)*10^24
			ELSE
				$1
		END::real;
$$;
/*
** fct_cpy_temperature_conversion
** Convert temperatures into unified version (K - Kelvin)
*/
CREATE OR REPLACE FUNCTION fct_cpy_temperature_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) RETURNS real
language SQL STABLE
AS
$$
	SELECT  CASE
			WHEN $2 = '°C' THEN
				($1)+273.15
			WHEN $2 = '°F' THEN
				(($1)+459.67)/1.8
			WHEN $2 = '°Ra' THEN
				($1)/1.8
			WHEN $2 in ('°Ré', '°r') THEN
				(($1)*5/4)+273.15
			WHEN $2 = '°N' THEN
				(($1)+273.15)*0.33
			WHEN $2 = '°Rø' THEN
				(((($1)-7.5)*40)/21)+273.15
			WHEN $2 = '°De' THEN
				373.15-(($1)*2/3)
			ELSE
				$1
		END::real;
$$;

/*
** fct_cpy_time_conversion
** Convert time values into unified one (s - second)
*/
CREATE OR REPLACE FUNCTION fct_cpy_time_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) RETURNS real
language SQL STABLE
AS
$$
	SELECT  CASE
			WHEN $2 = 'ns' THEN
				($1)*10^(-9)
			WHEN $2 = 'shake' THEN
				($1)*10^(-8)
			WHEN $2 = 'µs' THEN
				($1)*10^(-6)
			WHEN $2 = 'ms' THEN
				($1)*10^(-3)
			WHEN $2 = 'cs' THEN
				($1)*10^(-2)
			WHEN $2 = 't' THEN
				($1)/60
			WHEN $2 = 'ds' THEN
				($1)*10^(-1)
			WHEN $2 = 'min' THEN
				60*($1)
			WHEN $2 = 'h' THEN
				3600*($1)
			WHEN $2 IN ('d', 'j') THEN
				86400*($1)
			WHEN $2 IN ('y', 'year') THEN
				($1)*3.1536*10^7
			ELSE
				$1
		END::real;
$$;

/*
** fct_cpy_speed_conversion
** Convert windspeed values into unified one (m/s).
** If no unit or wrong unit provided value entered is returned so.
** If array of values is empty, empty array of values returned
*/
CREATE OR REPLACE FUNCTION fct_cpy_speed_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) RETURNS real
language SQL STABLE
AS
$$
	SELECT  CASE
			WHEN $2 = 'Kt' THEN
				($1)*0.51444444444444
			WHEN $2 = 'Beaufort' THEN
				CASE
					WHEN $1 = 0 THEN
						0.13888888888888
					WHEN $1 = 1 THEN
						3*0.27777777777778
					WHEN $1 = 2 THEN
						8*0.27777777777778
					WHEN $1 = 3 THEN
						15*0.27777777777778
					WHEN $1 = 4 THEN
						23.5*0.27777777777778
					WHEN $1 = 5 THEN
						33*0.27777777777778
					WHEN $1 = 6 THEN
						44*0.27777777777778
					WHEN $1 = 7 THEN
						55.5*0.27777777777778
					WHEN $1 = 8 THEN
						68*0.27777777777778
					WHEN $1 = 9 THEN
						81.5*0.27777777777778
					WHEN $1 = 10 THEN
						95.5*0.27777777777778
					WHEN $1 = 11 THEN
						110*0.27777777777778
					ELSE
						120*0.27777777777778
				END
			ELSE
				CASE
					WHEN strpos($2, '/') > 0 THEN
						fct_cpy_length_conversion($1, substr($2, 0, strpos($2, '/')))/fct_cpy_time_conversion(1, substr($2, strpos($2, '/')+1))
					ELSE
						$1
				END
		END::real;
$$;


/*
** convert_to_unified
* convert the unit to the unified form
*/
CREATE OR REPLACE FUNCTION convert_to_unified (IN property varchar, IN property_unit varchar, IN property_type varchar) RETURNS varchar
language plpgsql
AS
$$
BEGIN 
    IF property is NULL THEN
        RETURN NULL;
    END IF;
    
    IF property_type = 'speed' THEN
        RETURN fct_cpy_speed_conversion(property::real, property_unit)::text;
    END IF;

    IF property_type = 'temperature' AND property_unit IN ('K', '°C', '°F', '°Ra', '°Re', '°r', '°N', '°Rø', '°De') THEN
        RETURN fct_cpy_temperature_conversion(property::real, property_unit)::text;
    END IF;
    
    IF property_type = 'length' AND property_unit IN ('m', 'dm', 'cm', 'mm', 'µm', 'nm', 'pm', 'fm', 'am', 'zm', 'ym', 'am', 'dam', 'hm', 'km', 'Mm', 'Gm', 'Tm', 'Pm', 'Em', 'Zm', 'Ym', 'mam', 'mom', 'Å', 'ua', 'ch', 'fathom', 'fermi', 'ft', 'in', 'K', 'l.y.', 'ly', 'µ', 'mil', 'mi', 'nautical mi', 'pc', 'point', 'pt', 'pica', 'rd', 'yd', 'arp', 'lieue', 'league', 'cal', 'twp', 'p', 'P', 'fur', 'brasse', 'vadem', 'fms') THEN
        RETURN fct_cpy_length_conversion(property::real, property_unit)::text;
    END IF;

    RETURN  property;
END;
$$;

/*
** fct_cpy_unified_values
** Used as a trigger in catalogue_properties table to transform values into unified common value
** Case by case function
*/
CREATE OR REPLACE FUNCTION fct_cpy_unified_values () RETURNS TRIGGER
language plpgsql
AS

$$
DECLARE
	property_line catalogue_properties%ROWTYPE;
BEGIN
    IF TG_TABLE_NAME ='properties_values' THEN
        SELECT * INTO property_line FROM  catalogue_properties WHERE id=NEW.property_ref;
        NEW.property_value_unified := convert_to_unified(NEW.property_value,  property_line.property_unit, property_line.property_sub_type_indexed);
        NEW.property_accuracy_unified := convert_to_unified(NEW.property_accuracy::varchar,  property_line.property_accuracy_unit, property_line.property_sub_type_indexed)::real;
    ELSE
        UPDATE properties_values SET
            property_value_unified = convert_to_unified(property_value, NEW.property_unit, NEW.property_sub_type_indexed),
            property_accuracy_unified = convert_to_unified(property_accuracy::varchar,  NEW.property_accuracy_unit, NEW.property_sub_type_indexed)::real
            WHERE property_ref = NEW.id;
    END IF;
	RETURN NEW;
END;
$$;

/**
 Check That a parent is not attached twice in a path to avoid cycle
*/
CREATE OR REPLACE FUNCTION fct_chk_onceInPath(path varchar) RETURNS boolean
language plpgsql
AS
$$
BEGIN
    PERFORM * FROM regexp_split_to_table(path, E'\/') as i_id WHERE i_id != '' GROUP BY i_id HAVING COUNT(*)>1;
    IF FOUND THEN
        RETURN FALSE;
    END IF;
    RETURN true;
END;
$$;


/**
* Bloody mysql!
*/
CREATE OR REPLACE FUNCTION concat(text, text) RETURNS text AS $$
    SELECT $1 || $2;
$$ LANGUAGE 'sql';


CREATE OR REPLACE FUNCTION concat(text, text, text) RETURNS text AS $$
    SELECT $1 || $2 || $3;
$$ LANGUAGE 'sql';

/*
** Function used for encrypting passwords using pgcrypto function
*/

CREATE OR REPLACE FUNCTION sha1(bytea) RETURNS varchar LANGUAGE plpgsql AS
$$
BEGIN
        RETURN ENCODE(DIGEST($1, 'sha1'), 'hex');
END;
$$;

CREATE OR REPLACE FUNCTION ts_stat(tsvector, OUT word text, OUT ndoc
integer, OUT nentry integer)
RETURNS SETOF record AS
$$
    SELECT ts_stat('SELECT ' || quote_literal( $1::text ) || '::tsvector') WHERE $1 != to_tsvector('');
$$ LANGUAGE SQL RETURNS NULL ON NULL INPUT IMMUTABLE;

CREATE OR REPLACE FUNCTION fct_trg_word() RETURNS TRIGGER
AS
$$
BEGIN

   IF TG_TABLE_NAME ='collection_maintenance' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.description_ts != NEW.description_ts THEN
	  PERFORM fct_cpy_word('collection_maintenance','description_ts', NEW.description_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('collection_maintenance','description_ts', NEW.description_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='comments' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.comment_ts != NEW.comment_ts THEN
	  PERFORM fct_cpy_word('comments','comment_ts', NEW.comment_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('comments','comment_ts', NEW.comment_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='vernacular_names' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_ts != NEW.name_ts THEN
	  PERFORM fct_cpy_word('vernacular_names','name_ts', NEW.name_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('vernacular_names','name_ts', NEW.name_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='identifications' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.value_defined_ts != NEW.value_defined_ts THEN
	  PERFORM fct_cpy_word('identifications','value_defined_ts', NEW.value_defined_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('identifications','value_defined_ts', NEW.value_defined_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='multimedia' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.descriptive_ts != NEW.descriptive_ts THEN
	  PERFORM fct_cpy_word('multimedia','descriptive_ts', NEW.descriptive_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('multimedia','descriptive_ts', NEW.descriptive_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='people' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.formated_name_ts != NEW.formated_name_ts THEN
	  PERFORM fct_cpy_word('people','formated_name_ts', NEW.formated_name_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('people','formated_name_ts', NEW.formated_name_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='people_addresses' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.address_parts_ts != NEW.address_parts_ts THEN
	  PERFORM fct_cpy_word('people_addresses','address_parts_ts', NEW.address_parts_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('people_addresses','address_parts_ts', NEW.address_parts_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='users_addresses' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.address_parts_ts != NEW.address_parts_ts THEN
	   PERFORM fct_cpy_word('users_addresses','address_parts_ts', NEW.address_parts_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('users_addresses','address_parts_ts', NEW.address_parts_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='users' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.formated_name_ts != NEW.formated_name_ts THEN
	  PERFORM fct_cpy_word('users','formated_name_ts', NEW.formated_name_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('users','formated_name_ts', NEW.formated_name_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='expeditions' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_ts != NEW.name_ts THEN
	  PERFORM fct_cpy_word('expeditions','name_ts', NEW.name_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('expeditions','name_ts', NEW.name_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='habitats' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.description_ts != NEW.description_ts THEN
	  PERFORM fct_cpy_word('habitats','description_ts', NEW.description_ts);
	END IF;
      ELSE
	PERFORM fct_cpy_word('habitats','description_ts', NEW.description_ts);
      END IF;

   ELSIF TG_TABLE_NAME ='mineralogy' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_indexed != NEW.name_indexed THEN
	  PERFORM fct_cpy_word('mineralogy','name_indexed', NEW.name_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('mineralogy','name_indexed', NEW.name_indexed);
      END IF;

   ELSIF TG_TABLE_NAME ='chronostratigraphy' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_indexed != NEW.name_indexed THEN
	  PERFORM fct_cpy_word('chronostratigraphy','name_indexed', NEW.name_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('chronostratigraphy','name_indexed', NEW.name_indexed);
      END IF;

   ELSIF TG_TABLE_NAME ='lithostratigraphy' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_indexed != NEW.name_indexed THEN
	  PERFORM fct_cpy_word('lithostratigraphy','name_indexed', NEW.name_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('lithostratigraphy','name_indexed', NEW.name_indexed);
      END IF;

   ELSIF TG_TABLE_NAME ='lithology' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_indexed != NEW.name_indexed THEN
	  PERFORM fct_cpy_word('lithology','name_indexed', NEW.name_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('lithology','name_indexed', NEW.name_indexed);
      END IF;

   ELSIF TG_TABLE_NAME ='taxonomy' THEN

      IF TG_OP = 'UPDATE' THEN 
	IF OLD.name_indexed != NEW.name_indexed THEN
	  PERFORM fct_cpy_word('taxonomy','name_indexed', NEW.name_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('taxonomy','name_indexed', NEW.name_indexed);
      END IF;

   END IF;

   RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_word(tbl_name words.referenced_relation%TYPE, fld_name words.field_name%TYPE, word_ts tsvector) RETURNS boolean
AS
$$
DECLARE
  item varchar;
BEGIN
    FOR item IN SELECT word FROM ts_stat(word_ts) LOOP
      BEGIN
	INSERT INTO words (referenced_relation, field_name, word) VALUES (tbl_name, fld_name, item);
      EXCEPTION WHEN unique_violation THEN
	    -- Just Sleep and insert the next one
      END;
    END LOOP;
    RETURN true;
END;
$$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION search_words_to_query(tbl_name words.referenced_relation%TYPE, fld_name words.field_name%TYPE, value varchar, op varchar) RETURNS tsquery AS
$$
 
  SELECT to_tsquery('simple',array_to_string( array(SELECT word FROM words
      WHERE referenced_relation = $1
	AND field_name = $2
	AND word % $3
        AND to_tsvector($3) <> to_tsvector('')
	AND word ilike 
	  CASE WHEN $4 = 'begin' THEN $3 || '%'
	      WHEN $4 = 'end' THEN '%' || $3
	      WHEN $4 = 'contains' THEN '%' || $3 || '%'
	      ELSE word
	  END),
   ' | '));
$$
LANGUAGE SQL IMMUTABLE;

CREATE OR REPLACE FUNCTION fct_nbr_in_relation() RETURNS TRIGGER
AS
--fct_chk_nbr_in_relation(relation_type catalogue_relationships.relationship_type%TYPE, table_name catalogue_relationships.referenced_relation%TYPE, rid  catalogue_relationships.record_id_1%TYPE ) RETURNS BOOLEAN AS
$$
DECLARE 
  nbr integer = 0 ;
BEGIN
  SELECT count(record_id_2) INTO nbr FROM catalogue_relationships WHERE
      relationship_type = NEW.relationship_type
      AND record_id_1 = NEW.record_id_1
      AND referenced_relation = NEW.referenced_relation;
  
  IF NEW.relationship_type = 'current_name' THEN
    IF TG_OP = 'INSERT' THEN
      IF nbr > 0 THEN
	RAISE EXCEPTION 'Maximum number of renamed item reach';
      END IF;
    ELSE
      IF nbr > 1 THEN
	RAISE EXCEPTION 'Maximum number of renamed item reach';
      END IF;
    END IF;
  ELSEIF NEW.relationship_type = 'recombined from' THEN
    IF TG_OP = 'INSERT' THEN
      IF nbr > 1 THEN
	RAISE EXCEPTION 'Maximum number of recombined item reach';
      END IF;
    ELSE
      IF nbr > 2 THEN
	RAISE EXCEPTION 'Maximum number of recombined item reach';
      END IF;
    END IF;
  END IF;
  RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_nbr_in_synonym() RETURNS TRIGGER
AS
$$
DECLARE 
  nbr integer = 0 ;
BEGIN

  SELECT count(id) INTO nbr FROM classification_synonymies WHERE
      referenced_relation = NEW.referenced_relation
      AND record_id = NEW.record_id
      AND group_name = NEW.group_name;

  IF TG_OP = 'INSERT' THEN
    IF nbr > 1 THEN
      RAISE EXCEPTION 'You can ''t set this synonym twice!';
    END IF;
  ELSE
--     RAISE info 'nbr %', nbr;
    IF nbr > 2 THEN
      RAISE EXCEPTION 'You can ''t set this synonym twice!';
    END IF;
  END IF;

  RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION datesOverlaps(start1 date, end1 date, start2 date, end2 date) RETURNS boolean LANGUAGE plpgsql
AS
$$
DECLARE
  response boolean := true;
BEGIN
  SELECT (start1, end1) OVERLAPS (start2, end2) INTO response;
  return response;
EXCEPTION
  WHEN OTHERS THEN
    response := false;
    RAISE NOTICE 'Error in datesOverlaps function: %', SQLERRM;
    return response;
END;
$$;
