CREATE OR REPLACE FUNCTION fct_clr_incrementMainCode() RETURNS trigger
as $$
DECLARE
	last_line specimens_codes%ROWTYPE;
	must_be_incremented collections.code_auto_increment%TYPE;
BEGIN
	SELECT collections.code_auto_increment INTO must_be_incremented FROM collections WHERE collections.id = NEW.collection_ref;
	IF must_be_incremented = true THEN
		SELECT * INTO last_line FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=NEW.id;
		IF FOUND THEN
			RETURN NEW;
 		END IF;
 
 		SELECT specimens_codes.* into last_line FROM specimens_codes
				INNER JOIN specimens ON specimens_codes.specimen_ref = specimens.id
				WHERE specimens.collection_ref =  NEW.collection_ref
					AND code_category = 'main'
					ORDER BY specimens_codes.code DESC
					LIMIT 1;
		IF NOT FOUND THEN
			last_line.code := 0;
			last_line.code_category := 'main';
		END IF;
		
		last_line.code := last_line.code+1;
		
		-- FIXME: Remove Code indexed ==> Trigger!
		INSERT INTO specimens_codes (specimen_ref, code_category, code_prefix, code, full_code_indexed, code_suffix)
			VALUES (NEW.id, 'main', last_line.code_prefix, last_line.code, 'main'||COALESCE(last_line.code_prefix,'')||last_line.code||COALESCE(last_line.code_suffix,'') , last_line.code_suffix );
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION fct_cpy_specimensMainCode() RETURNS trigger
as $$
DECLARE
	spec_code specimens_codes%ROWTYPE;
	must_be_copied collections.code_part_code_auto_copy%TYPE;
BEGIN
	SELECT collections.code_part_code_auto_copy INTO must_be_copied FROM collections 
			INNER JOIN specimens ON collections.id = specimens.collection_ref
			INNER JOIN specimen_individuals ON specimen_individuals.specimen_ref=specimens.id
				WHERE specimen_individuals.id = NEW.specimen_individual_ref;
	
	IF must_be_copied = true THEN
		SELECT specimens_codes.* into spec_code FROM specimens_codes
			INNER JOIN specimens ON specimens_codes.specimen_ref = specimens.id
			INNER JOIN specimen_individuals ON specimen_individuals.specimen_ref=specimens.id
			WHERE specimen_individuals.id = NEW.specimen_individual_ref
				AND code_category = 'main'
				ORDER BY specimens_codes.code DESC
					LIMIT 1;
		IF FOUND THEN
			-- FIXME: Remove Code indexed ==> Trigger!
			INSERT INTO specimen_parts_codes (specimen_part_ref, code_category, code_prefix, code, full_code_indexed, code_suffix)
					VALUES (NEW.id, 'main', spec_code.code_prefix, spec_code.code, 'main'||COALESCE(spec_code.code_prefix,'')||spec_code.code||COALESCE(spec_code.code_suffix,'') , spec_code.code_suffix );
		END IF;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_idToCode() RETURNS trigger
AS $$
BEGIN
	IF NEW.code is null THEN
		NEW.code := NEW.id;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_chk_one_pref_language(person people_languages.people_ref%TYPE, prefered people_languages.prefered_language%TYPE, table_prefix varchar) returns boolean
as $$
DECLARE
	response boolean default false;
	prefix varchar default coalesce(table_prefix, 'people');
        tabl varchar default prefix || '_languages';
	tableExist boolean default false;
BEGIN
	select count(*)::integer::boolean into tableExist from pg_tables where schemaname = 'darwin2' and tablename = tabl;
	IF tableExist THEN
	        IF prefered THEN
			EXECUTE 'select not count(*)::integer::boolean from ' || quote_ident(tabl) || ' where ' || quote_ident(prefix || '_ref') || ' = ' || $1 || ' and prefered_language = ' || $2 INTO response;
		ELSE
			response := true;
		END IF;
	END IF;
	return response;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_chk_one_pref_language(person people_languages.people_ref%TYPE, prefered people_languages.prefered_language%TYPE) returns boolean
as $$
DECLARE
        response boolean default false;
BEGIN
	response := fct_chk_one_pref_language(person, prefered, 'people');
	return response;
END;
$$ LANGUAGE plpgsql;


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
	temp_string := LOWER(
				public.to_ascii(
					CONVERT_TO(temp_string, 'iso-8859-15'),
					'iso-8859-15')
				);
	--Remove ALL none alphanumerical char
	temp_string := regexp_replace(temp_string,'[^[:alnum:]]','', 'g');
	return substring(temp_string from 0 for 40);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_get_hierarchy_from_parents(table_name varchar, id integer) RETURNS RECORD
AS $$
DECLARE
	level_sys_name catalogue_levels.level_sys_name%TYPE;
	level_ref  template_classifications.level_ref%TYPE;
	name_indexed template_classifications.name_indexed%TYPE;
	parent_ref template_classifications.parent_ref%TYPE;
	result RECORD;
BEGIN

	EXECUTE 'SELECT level_ref, name_indexed, parent_ref FROM ' || quote_ident(table_name) || ' WHERE id = ' || id INTO level_ref, name_indexed, parent_ref;
	SELECT cl.level_sys_name INTO level_sys_name FROM catalogue_levels as cl WHERE cl.id = level_ref;
	IF table_name = 'chronostratigraphy' THEN
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
	ELSIF table_name = 'lithostratigraphy' THEN
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
	ELSIF table_name = 'lithology' THEN
		
	ELSIF table_name = 'mineralogy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'class' THEN
					id
				ELSE
					pm.class_ref
			END AS class_ref,
			CASE
				WHEN level_sys_name = 'class' THEN
					name_indexed
				ELSE
					pm.class_indexed
			END AS class_indexed,
			CASE
				WHEN level_sys_name = 'division' THEN
					id
				ELSE
					pm.division_ref
			END AS division_ref,
			CASE
				WHEN level_sys_name = 'division' THEN
					name_indexed
				ELSE
					pm.division_indexed
			END AS division_indexed,
			CASE
				WHEN level_sys_name = 'family' THEN
					id
				ELSE
					pm.family_ref
			END AS family_ref,
			CASE
				WHEN level_sys_name = 'family' THEN
					name_indexed
				ELSE
					pm.family_indexed
			END AS family_indexed,
			CASE
				WHEN level_sys_name = 'group' THEN
					id
				ELSE
					pm.group_ref
			END AS group_ref,
			CASE
				WHEN level_sys_name = 'group' THEN
					name_indexed
				ELSE
					pm.group_indexed
			END AS group_indexed,
			CASE
				WHEN level_sys_name = 'variety' THEN
					id
				ELSE
					pm.variety_ref
			END AS variety_ref,
			CASE
				WHEN level_sys_name = 'variety' THEN
					name_indexed
				ELSE
					pm.variety_indexed
			END AS variety_indexed
		INTO 
			result
		FROM mineralogy AS pm
		WHERE pm.id = parent_ref;
	ELSIF table_name = 'taxa' THEN
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
		FROM taxa AS pt
		WHERE pt.id = parent_ref;
	END IF;
	RETURN result;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_hierarchy_from_parents() RETURNS trigger
AS $$
DECLARE
	level_sys_name catalogue_levels.level_sys_name%TYPE;
BEGIN
	SELECT cl.level_sys_name INTO level_sys_name FROM catalogue_levels as cl WHERE cl.id = NEW.level_ref;
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
		
	ELSIF TG_TABLE_NAME = 'mineralogy' THEN
		SELECT 
			CASE
				WHEN level_sys_name = 'class' THEN
					NEW.id
				ELSE
					pm.class_ref
			END AS class_ref,
			CASE
				WHEN level_sys_name = 'class' THEN
					NEW.name_indexed
				ELSE
					pm.class_indexed
			END AS class_indexed,
			CASE
				WHEN level_sys_name = 'division' THEN
					NEW.id
				ELSE
					pm.division_ref
			END AS division_ref,
			CASE
				WHEN level_sys_name = 'division' THEN
					NEW.name_indexed
				ELSE
					pm.division_indexed
			END AS division_indexed,
			CASE
				WHEN level_sys_name = 'family' THEN
					NEW.id
				ELSE
					pm.family_ref
			END AS family_ref,
			CASE
				WHEN level_sys_name = 'family' THEN
					NEW.name_indexed
				ELSE
					pm.family_indexed
			END AS family_indexed,
			CASE
				WHEN level_sys_name = 'group' THEN
					NEW.id
				ELSE
					pm.group_ref
			END AS group_ref,
			CASE
				WHEN level_sys_name = 'group' THEN
					NEW.name_indexed
				ELSE
					pm.group_indexed
			END AS group_indexed,
			CASE
				WHEN level_sys_name = 'variety' THEN
					NEW.id
				ELSE
					pm.variety_ref
			END AS variety_ref,
			CASE
				WHEN level_sys_name = 'variety' THEN
					NEW.name_indexed
				ELSE
					pm.variety_indexed
			END AS variety_indexed
		INTO 
			NEW.class_ref,
			NEW.class_indexed,
			NEW.division_ref,
			NEW.division_indexed,
			NEW.family_ref,
			NEW.family_indexed,
			NEW.group_ref,
			NEW.group_indexed,
			NEW.variety_ref,
			NEW.variety_indexed
		FROM mineralogy AS pm
		WHERE pm.id = NEW.parent_ref;
	ELSIF TG_TABLE_NAME = 'taxa' THEN
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
			NEW.sub_genus_ref,
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
		FROM taxa AS pt
		WHERE pt.id = NEW.parent_ref;
	END IF;
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_cascade_children_indexed_names (table_name varchar, new_level_ref template_classifications.level_ref%TYPE, new_name_indexed template_classifications.name_indexed%TYPE, new_id integer) RETURNS boolean
AS $$
DECLARE
	level_prefix catalogue_levels.level_sys_name%TYPE;
	response boolean default false;
BEGIN

	SELECT level_sys_name INTO level_prefix FROM catalogue_levels WHERE id = new_level_ref;
	IF level_prefix IS NOT NULL THEN
		EXECUTE 'UPDATE ' || 
			quote_ident(table_name) || 
			' SET ' || quote_ident(level_prefix || '_indexed') || ' = ' || quote_literal(new_name_indexed) || 
			' WHERE ' || quote_ident(level_prefix || '_ref') || ' = ' || new_id ;
		response := true;
	END IF;
	return response;
EXCEPTION
	WHEN OTHERS THEN
		return response;
END;
$$ LANGUAGE plpgsql;

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
			oldValue := OLD.property_tool;
			IF NEW.property_tool <> oldValue THEN
				NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
			END IF;
			oldValue := OLD.property_sub_type;
			IF NEW.property_sub_type <> oldValue THEN
				NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
			END IF;
			oldValue := OLD.property_method;
			IF NEW.property_method <> oldValue THEN
				NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
			END IF;
		ELSIF TG_TABLE_NAME = 'chronostratigraphy' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		ELSIF TG_TABLE_NAME = 'expeditions' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		ELSIF TG_TABLE_NAME = 'habitats' THEN
			oldValue := OLD.code;
			IF NEW.code <> oldValue THEN
				NEW.code_indexed := fullToIndex(NEW.code);
			END IF;
		ELSIF TG_TABLE_NAME = 'identifications' THEN
			oldValue := OLD.value_defined;
			IF NEW.value_defined <> oldValue THEN
				NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
			END IF;
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
			oldValue := OLD.formule;
			IF NEW.formule <> oldValue THEN
				NEW.formule_indexed := fullToIndex(NEW.formule);
			END IF;
		ELSIF TG_TABLE_NAME = 'multimedia' THEN
			oldValue := OLD.title;
			IF NEW.title <> oldValue THEN
				NEW.title_indexed := fullToIndex(NEW.title);
			END IF;
		ELSIF TG_TABLE_NAME = 'multimedia_keywords' THEN
			oldValue := OLD.keyword;
			IF NEW.keyword <> oldValue THEN
				NEW.keyword_indexed := fullToIndex(NEW.keyword);
			END IF;
		ELSIF TG_TABLE_NAME = 'people' THEN
			oldValue := OLD.formated_name;
			IF NEW.formated_name <> oldValue THEN
				NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
			END IF;
		ELSIF TG_TABLE_NAME = 'multimedia_codes' THEN
			oldCodePrefix := OLD.code_prefix;
			oldCode := OLD.code;
			oldCodeSuffix := OLD.code_suffix;
			IF NEW.code <> oldCode OR NEW.code_prefix <> oldCodePrefix OR NEW.code_suffix <> oldCodeSuffix THEN
				NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
			END IF;
		ELSIF TG_TABLE_NAME = 'specimen_parts_codes' THEN
			oldCodePrefix := OLD.code_prefix;
			oldCode := OLD.code;
			oldCodeSuffix := OLD.code_suffix;
			IF NEW.code <> oldCode OR NEW.code_prefix <> oldCodePrefix OR NEW.code_suffix <> oldCodeSuffix THEN
				NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
			END IF;
		ELSIF TG_TABLE_NAME = 'specimens_codes' THEN
			oldCodePrefix := OLD.code_prefix;
			oldCode := OLD.code;
			oldCodeSuffix := OLD.code_suffix;
			IF NEW.code <> oldCode OR NEW.code_prefix <> oldCodePrefix OR NEW.code_suffix <> oldCodeSuffix THEN
				NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
			END IF;
		ELSIF TG_TABLE_NAME = 'tag_groups' THEN
			oldValue := OLD.group_name;
			IF NEW.group_name <> oldValue THEN
				NEW.group_name_indexed := fullToIndex(NEW.group_name);
			END IF;
		ELSIF TG_TABLE_NAME = 'tags' THEN
			oldValue := OLD.label;
			IF NEW.label <> oldValue THEN
				NEW.label_indexed := fullToIndex(NEW.label);
			END IF;
		ELSIF TG_TABLE_NAME = 'taxa' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		ELSIF TG_TABLE_NAME = 'users' THEN
			oldValue := OLD.formated_name;
			IF NEW.formated_name <> oldValue THEN
				NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
			END IF;
		ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
			oldValue := OLD.name;
			IF NEW.name <> oldValue THEN
				NEW.name_indexed := fullToIndex(NEW.name);
			END IF;
		END IF;	
	ELSIF TG_OP = 'INSERT' THEN
		IF TG_TABLE_NAME = 'catalogue_properties' THEN
			NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
			NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
			NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
		ELSIF TG_TABLE_NAME = 'chronostratigraphy' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'expeditions' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'habitats' THEN
			NEW.code_indexed := fullToIndex(NEW.code);
		ELSIF TG_TABLE_NAME = 'identifications' THEN
			NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
		ELSIF TG_TABLE_NAME = 'lithology' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'mineralogy' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			NEW.formule_indexed := fullToIndex(NEW.formule);
		ELSIF TG_TABLE_NAME = 'multimedia' THEN
			NEW.title_indexed := fullToIndex(NEW.title);
		ELSIF TG_TABLE_NAME = 'multimedia_keywords' THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		ELSIF TG_TABLE_NAME = 'people' THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		ELSIF TG_TABLE_NAME = 'multimedia_codes' THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		ELSIF TG_TABLE_NAME = 'specimen_parts_codes' THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		ELSIF TG_TABLE_NAME = 'specimens_codes' THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		ELSIF TG_TABLE_NAME = 'tag_groups' THEN
			NEW.group_name_indexed := fullToIndex(NEW.group_name);
		ELSIF TG_TABLE_NAME = 'tags' THEN
			NEW.label_indexed := fullToIndex(NEW.label);
		ELSIF TG_TABLE_NAME = 'taxa' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'users' THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		END IF;	
	END IF;
	RETURN NEW;
EXCEPTION
	WHEN RAISE_EXCEPTION THEN
		return NULL;
END;
$$ LANGUAGE plpgsql;
