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
	ELSEIF TG_TABLE_NAME = 'lithology' THEN
		
	ELSIF TG_TABLE_NAME = 'lithostratigraphy' THEN
		
	ELSIF TG_TABLE_NAME = 'mineralogy' THEN
		
	ELSIF TG_TABLE_NAME = 'taxa' THEN
		
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
BEGIN

	IF TG_TABLE_NAME = 'catalogue_properties' THEN
		IF NEW.property_tool <> OLD.property_tool THEN
			NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
		END IF;
		IF NEW.property_sub_type <> OLD.property_sub_type THEN
			NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
		END IF;
		IF NEW.property_method <> OLD.property_method THEN
			NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'chronostratigraphy' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			IF NOT fct_cpy_cascade_children_indexed_names(TG_TABLE_NAME, NEW.level_ref, NEW.name_indexed, NEW.id) THEN
				RAISE EXCEPTION 'Impossible to impact children names';
			END IF;
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'expeditions' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		END IF;
	END IF;
		
	IF TG_TABLE_NAME = 'habitats' THEN
		IF NEW.code <> OLD.code THEN
			NEW.code_indexed := fullToIndex(NEW.code);
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'identifications' THEN
		IF NEW.value_defined <> OLD.value_defined THEN
			NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'lithology' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			IF NOT fct_cpy_cascade_children_indexed_names(TG_TABLE_NAME, NEW.level_ref, NEW.name_indexed, NEW.id) THEN
				RAISE EXCEPTION 'Impossible to impact children names';
			END IF;
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'lithostratigraphy' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			IF NOT fct_cpy_cascade_children_indexed_names(TG_TABLE_NAME, NEW.level_ref, NEW.name_indexed, NEW.id) THEN
				RAISE EXCEPTION 'Impossible to impact children names';
			END IF;
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'mineralogy' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			IF NOT fct_cpy_cascade_children_indexed_names(TG_TABLE_NAME, NEW.level_ref, NEW.name_indexed, NEW.id) THEN
				RAISE EXCEPTION 'Impossible to impact children names';
			END IF;
		END IF;
		IF NEW.formule <> OLD.formule THEN
			NEW.formule_indexed := fullToIndex(NEW.formule);
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'multimedia' THEN
		IF NEW.title <> OLD.title THEN
			NEW.title_indexed := fullToIndex(NEW.title);
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'multimedia_keywords' THEN
		IF NEW.keyword <> OLD.keyword THEN
			NEW.keyword_indexed := fullToIndex(NEW.keyword);
		END IF;
	END IF;	
	
	IF TG_TABLE_NAME = 'people' THEN
		IF NEW.formated_name <> OLD.formated_name THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		END IF;
	END IF;	
	
	IF TG_TABLE_NAME = 'multimedia_codes' THEN
		IF NEW.code <> OLD.code OR NEW.code_prefix <> OLD.code_prefix OR NEW.code_suffix <> OLD.code_suffix THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		END IF;
	END IF;
		
	IF TG_TABLE_NAME = 'specimen_parts_codes' THEN
		IF NEW.code <> OLD.code OR NEW.code_prefix <> OLD.code_prefix OR NEW.code_suffix <> OLD.code_suffix THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'specimens_codes' THEN
		IF NEW.code <> OLD.code OR NEW.code_prefix <> OLD.code_prefix OR NEW.code_suffix <> OLD.code_suffix THEN
			NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'tag_groups' THEN
		IF NEW.group_name <> OLD.group_name THEN
			NEW.group_name_indexed := fullToIndex(NEW.group_name);
		END IF;
	END IF;	

	IF TG_TABLE_NAME = 'tags' THEN
		IF NEW.label <> OLD.label THEN
			NEW.label_indexed := fullToIndex(NEW.label);
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'taxa' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
			IF NOT fct_cpy_cascade_children_indexed_names(TG_TABLE_NAME, NEW.level_ref, NEW.name_indexed, NEW.id) THEN
				RAISE EXCEPTION 'Impossible to impact children names';
			END IF;
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'users' THEN
		IF NEW.formated_name <> OLD.formated_name THEN
			NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
		END IF;
	END IF;
	
	IF TG_TABLE_NAME = 'vernacular_names' THEN
		IF NEW.name <> OLD.name THEN
			NEW.name_indexed := fullToIndex(NEW.name);
		END IF;
	END IF;	
	RETURN NEW;
EXCEPTION
	WHEN RAISE_EXCEPTION THEN
		return NULL;
END;
$$ LANGUAGE plpgsql;
