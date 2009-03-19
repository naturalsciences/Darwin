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


CREATE OR REPLACE FUNCTION fct_cpy_fullToIndex() RETURNS trigger
AS $$
BEGIN

	IF TG_TABLE_NAME = 'catalogue_properties' THEN
		NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
		NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
		NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
	END IF;
	
	IF TG_TABLE_NAME = 'chronostratigraphy' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;
	
	IF TG_TABLE_NAME = 'expeditions' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;
		
	IF TG_TABLE_NAME = 'habitats' THEN
		NEW.code_indexed := fullToIndex(NEW.code);
	END IF;
	
	IF TG_TABLE_NAME = 'identifications' THEN
		NEW.value_defined_indexed := COALESCE(fullToIndex(NEW.value_defined),'');
	END IF;
	
	IF TG_TABLE_NAME = 'lithology' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;
	
	IF TG_TABLE_NAME = 'lithostratigraphy' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;
	
	IF TG_TABLE_NAME = 'mineralogy' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
		NEW.formule_indexed := fullToIndex(NEW.formule);
	END IF;
	
	IF TG_TABLE_NAME = 'multimedia' THEN
		NEW.title_indexed := fullToIndex(NEW.title);
	END IF;
	
	IF TG_TABLE_NAME = 'multimedia_keywords' THEN
		NEW.keyword_indexed := fullToIndex(NEW.keyword);
	END IF;	
	
	IF TG_TABLE_NAME = 'people' THEN
		NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
	END IF;	
	
	IF TG_TABLE_NAME = 'multimedia_codes' THEN
		NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
	END IF;
		
	IF TG_TABLE_NAME = 'specimen_parts_codes' THEN
		NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
	END IF;
	
	IF TG_TABLE_NAME = 'specimens_codes' THEN
		NEW.full_code_indexed := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
	END IF;
	
	IF TG_TABLE_NAME = 'tag_groups' THEN
		NEW.group_name_indexed := fullToIndex(NEW.group_name);
	END IF;	

	IF TG_TABLE_NAME = 'tags' THEN
		NEW.label_indexed := fullToIndex(NEW.label);
	END IF;
	
	IF TG_TABLE_NAME = 'taxa' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;
	
	IF TG_TABLE_NAME = 'users' THEN
		NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
	END IF;
	
	IF TG_TABLE_NAME = 'vernacular_names' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	END IF;	
	RETURN NEW;
END;
$$ LANGUAGE plpgsql;
