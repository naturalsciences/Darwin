
CREATE AGGREGATE array_accum (anyelement)
(
  sfunc = array_append,
  stype = anyarray,
  initcond = '{}'
);


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
CREATE OR REPLACE FUNCTION fullToIndex(to_indexed varchar, forUniqueness boolean default false) RETURNS varchar STRICT
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
    IF forUniqueness THEN
      temp_string := LOWER(to_ascii(temp_string, 'LATIN9'));
    ELSE
      temp_string := LOWER(
          public.to_ascii(
            CONVERT_TO(temp_string, 'iso-8859-15'),
            'iso-8859-15')
          );
    END IF;
	--Remove ALL none alphanumerical char
    temp_string := regexp_replace(temp_string,'[^[:alnum:]]','', 'g');
    return substring(temp_string from 0 for 40);
END;
$$ LANGUAGE plpgsql IMMUTABLE;

/***
* Trigger function fct_cpy_fullToIndex
* Call the fulltoIndex function for different tables
*/
CREATE OR REPLACE FUNCTION fct_cpy_fullToIndex() RETURNS trigger
AS $$
BEGIN
	IF TG_TABLE_NAME = 'catalogue_properties' THEN
		NEW.property_tool_indexed := COALESCE(fullToIndex(NEW.property_tool),'');
		NEW.property_sub_type_indexed := COALESCE(fullToIndex(NEW.property_sub_type),'');
		NEW.property_method_indexed := COALESCE(fullToIndex(NEW.property_method),'');
		NEW.property_qualifier_indexed := COALESCE(fullToIndex(NEW.property_qualifier),'');
	ELSIF TG_TABLE_NAME = 'chronostratigraphy' THEN
		NEW.name_indexed := to_tsvector('simple', NEW.name);
		NEW.name_order_by := fullToIndex(NEW.name);
	ELSIF TG_TABLE_NAME = 'collections' THEN
		NEW.name_indexed := fullToIndex(NEW.name);		
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
    NEW.formated_name_unique := COALESCE(fullToIndex(NEW.formated_name, true),'');
	ELSIF TG_TABLE_NAME = 'codes' THEN
		IF NEW.code ~ '^[0-9]+$' THEN
		   NEW.code_num := NEW.code;
		ELSE
    		   NEW.code_num := null;
		END IF;
                NEW.full_code_indexed := to_tsvector('simple', COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,''));
		NEW.full_code_order_by := fullToIndex(COALESCE(NEW.code_prefix,'') || COALESCE(NEW.code::text,'') || COALESCE(NEW.code_suffix,'') );
	ELSIF TG_TABLE_NAME = 'tag_groups' THEN
		NEW.group_name_indexed := fullToIndex(NEW.group_name);
		NEW.sub_group_name_indexed := fullToIndex(NEW.sub_group_name);
	ELSIF TG_TABLE_NAME = 'taxonomy' THEN
		NEW.name_indexed := to_tsvector('simple', NEW.name);
		NEW.name_order_by := fullToIndex(NEW.name);
	ELSIF TG_TABLE_NAME = 'classification_keywords' THEN
		NEW.keyword_indexed := fullToIndex(NEW.keyword);
	ELSIF TG_TABLE_NAME = 'users' THEN
		NEW.formated_name_indexed := COALESCE(fullToIndex(NEW.formated_name),'');
    NEW.formated_name_unique := COALESCE(fullToIndex(NEW.formated_name, true),'');
	ELSIF TG_TABLE_NAME = 'class_vernacular_names' THEN
		NEW.community_indexed := fullToIndex(NEW.community);
	ELSIF TG_TABLE_NAME = 'vernacular_names' THEN
		NEW.name_indexed := fullToIndex(NEW.name);
	ELSIF TG_TABLE_NAME = 'igs' THEN
		NEW.ig_num_indexed := fullToIndex(NEW.ig_num);
  ELSIF TG_TABLE_NAME = 'collecting_methods' THEN
    NEW.method_indexed := fullToIndex(NEW.method);
  ELSIF TG_TABLE_NAME = 'collecting_tools' THEN
    NEW.tool_indexed := fullToIndex(NEW.tool);
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
DECLARE
  newType varchar := fullToIndex(NEW.type);
BEGIN

	-- IF Type not changed
	IF TG_OP = 'UPDATE' THEN
		IF fullToIndex(OLD.type) = newType THEN
			RETURN NEW;
		END IF;
	END IF;

	IF newType= 'specimen' THEN
		NEW.type_search := 'specimen';
		NEW.type_group := 'specimen';
	ELSIF newType= 'type' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	ELSIF newType= 'subtype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	ELSIF newType= 'allotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'allotype';
	ELSIF newType= 'cotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'syntype';
	ELSIF newType= 'genotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	ELSIF newType= 'holotype' THEN
		NEW.type_search := 'holotype';
		NEW.type_group := 'holotype';
	ELSIF newType= 'hypotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'hypotype';
	ELSIF newType= 'lectotype' THEN
		NEW.type_search := 'lectotype';
		NEW.type_group := 'lectotype';
	ELSIF newType= 'locotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'locotype';
	ELSIF newType= 'neallotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type';
	ELSIF newType= 'neotype' THEN
		NEW.type_search := 'neotype';
		NEW.type_group := 'neotype';
	ELSIF newType= 'paralectotype' THEN
		NEW.type_search := 'paralectotype';
		NEW.type_group := 'paralectotype';
	ELSIF newType= 'paratype' THEN
		NEW.type_search := 'paratype';
		NEW.type_group := 'paratype';
	ELSIF newType= 'plastotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'plastotype';
	ELSIF newType= 'plesiotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'plesiotype';
	ELSIF newType= 'syntype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'syntype';
	ELSIF newType= 'topotype' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'topotype';
	ELSIF newType= 'typeinlitteris' THEN
		NEW.type_search := 'type';
		NEW.type_group := 'type in litteris';
	ELSE
    NEW.type_search := 'type';
    NEW.type_group := 'type';
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
/*	DELETE FROM record_visibilities WHERE referenced_relation = TG_TABLE_NAME AND record_id = OLD.id;*/
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
		ELSEIF TG_TABLE_NAME = 'ext_links' THEN			
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
			NEW.description_ts := to_tsvector('simple',NEW.description);
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
		ELSEIF TG_TABLE_NAME = 'ext_link' THEN	
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
			IF OLD.description != NEW.description THEN
				NEW.description_ts := to_tsvector('simple', NEW.description);
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
			IF OLD.name != NEW.name THEN
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
* fct_chk_peopleType
* When removing author flag for a people, check if he is not referenced as author in a catalogue
*/
CREATE OR REPLACE FUNCTION fct_chk_peopleType() RETURNS TRIGGER
AS $$
DECLARE
	still_referenced integer;
BEGIN
	/** AUTHOR FLAG IS 2 **/
	IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 2)>0 )  THEN
		SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='author';
		IF still_referenced !=0 THEN
			RAISE EXCEPTION 'Author still used as author.';
		END IF;
	END IF;

	/** IDENTIFIER FLAG IS 4 **/
	IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 4)>0 )  THEN
		SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='identifier';
		IF still_referenced !=0 THEN
			RAISE EXCEPTION 'Identifier still used as identifier.';
		END IF;
	END IF;

        /** Expert Flag is 8 **/
        IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 8)>0 )  THEN
                SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='expert';
                IF still_referenced !=0 THEN
                        RAISE EXCEPTION 'Expert still used as expert.';
                END IF;
        END IF;

        /** COLLECTOR Flag is 16 **/
        IF NEW.db_people_type != OLD.db_people_type AND NOT ( (NEW.db_people_type & 16)>0 )  THEN
                SELECT count(*) INTO still_referenced FROM catalogue_people WHERE people_ref=NEW.id AND people_type='collector';
                IF still_referenced !=0 THEN
                        RAISE EXCEPTION 'Collector still used as collector.';
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
	IF NEW.people_type = 'author' THEN

		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 2)=0 AND id=NEW.people_ref;

		IF are_not_author THEN
			RAISE EXCEPTION 'Author must be defined as author.';
		END IF;

	ELSIF NEW.people_type = 'identifier' THEN

		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 4)=0 AND id=NEW.people_ref;

                IF are_not_author THEN
                        RAISE EXCEPTION 'Experts must be defined as identifier.';
                END IF;

	ELSIF NEW.people_type = 'expert' THEN

		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 8)=0 AND id=NEW.people_ref;

		IF are_not_author THEN
			RAISE EXCEPTION 'Experts must be defined as expert.';
		END IF;
	ELSIF NEW.people_type = 'collector' THEN

		SELECT COUNT(*)>0 INTO are_not_author FROM people WHERE (db_people_type & 16)=0 AND id=NEW.people_ref;

		IF are_not_author THEN
			RAISE EXCEPTION 'Collectors must be defined as collector.';
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

CREATE OR REPLACE FUNCTION fct_cpy_FormattedName() RETURNS TRIGGER
AS $$
BEGIN
	IF TG_OP ='UPDATE' THEN
		IF NEW.family_name = OLD.family_name AND NEW.given_name = OLD.given_name AND NEW.title = OLD.title THEN
			RETURN NEW;
		END IF;
	END IF;

	IF NEW.is_physical THEN
    IF COALESCE(NEW.title, '') = '' THEN
      NEW.formated_name := COALESCE(NEW.family_name,'') || ' ' || COALESCE(NEW.given_name,'');
		ELSE
		  NEW.formated_name := COALESCE(NEW.family_name,'') || ' ' || COALESCE(NEW.given_name,'') || ' (' || NEW.title || ')';
    END IF;
	ELSE
		NEW.formated_name := NEW.family_name;
	END IF;
	NEW.formated_name_indexed := fullToIndex(NEW.formated_name);
  NEW.formated_name_unique := fullToIndex(NEW.formated_name, true);
	NEW.formated_name_ts := to_tsvector('simple', NEW.formated_name);
	RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_path() RETURNS TRIGGER
AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        IF( TG_TABLE_NAME::text = 'multimedia' OR
          TG_TABLE_NAME::text = 'collections' OR
          TG_TABLE_NAME::text = 'gtu' OR
          TG_TABLE_NAME::text = 'habitats' OR
          TG_TABLE_NAME::text = 'specimen_parts') THEN

          IF NEW.id = 0 THEN
            NEW.parent_ref = null;
          END IF;
          IF NEW.parent_ref IS NULL THEN
            NEW.path ='/';
          ELSE
            EXECUTE 'SELECT path || id || ' || quote_literal('/') ||' FROM ' || quote_ident(TG_TABLE_NAME::text) || ' WHERE id=' || quote_literal(NEW.parent_ref) INTO STRICT NEW.path;
          END IF;
        ELSIF TG_TABLE_NAME::text = 'people_relationships' THEN
          SELECT path || NEW.person_1_ref || '/' INTO NEW.path
            FROM people_relationships
            WHERE person_2_ref=NEW.person_1_ref;
          IF NEW.path is NULL THEN
            NEW.path = '/' || NEW.person_1_ref || '/';
          END IF;
        END IF;
      ELSIF TG_OP = 'UPDATE' THEN
        IF( TG_TABLE_NAME::text = 'multimedia' OR
          TG_TABLE_NAME::text = 'collections' OR
          TG_TABLE_NAME::text = 'gtu' OR
          TG_TABLE_NAME::text = 'habitats' OR
          TG_TABLE_NAME::text = 'specimen_parts') THEN

          IF NEW.parent_ref IS DISTINCT FROM OLD.parent_ref THEN
            IF NEW.parent_ref IS NULL THEN
              NEW.path ='/';
            ELSIF COALESCE(OLD.parent_ref,0) = COALESCE(NEW.parent_ref,0) THEN
              RETURN NEW;
            ELSE
              EXECUTE 'SELECT path || id || ' || quote_literal('/') ||' FROM ' || quote_ident(TG_TABLE_NAME::text) || ' WHERE id=' || quote_literal(NEW.parent_ref) INTO STRICT NEW.path;
            END IF;

            EXECUTE 'UPDATE ' || quote_ident(TG_TABLE_NAME::text) || ' SET path=replace(path, ' ||  quote_literal(OLD.path || OLD.id || '/') ||' , ' || quote_literal( NEW.path || OLD.id || '/') || ') ' ||
              ' WHERE path like ' || quote_literal(OLD.path || OLD.id || '/%');
          END IF;
        ELSE
          IF NEW.person_1_ref != OLD.person_1_ref OR NEW.person_2_ref != OLD.person_2_ref THEN
            SELECT path ||  NEW.person_1_ref || '/' INTO NEW.path FROM people_relationships WHERE person_2_ref=NEW.person_1_ref;

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

CREATE OR REPLACE FUNCTION fct_cpy_path_catalogs() RETURNS TRIGGER
AS $$
BEGIN
    IF TG_OP = 'INSERT' AND (TG_TABLE_NAME::text = 'taxonomy' OR
          TG_TABLE_NAME::text = 'lithology' OR
          TG_TABLE_NAME::text = 'lithostratigraphy' OR
          TG_TABLE_NAME::text = 'mineralogy' OR
          TG_TABLE_NAME::text = 'chronostratigraphy') THEN

          IF NEW.parent_ref = 0 THEN
            NEW.path ='/';
          ELSE
            EXECUTE 'SELECT path || id || ' || quote_literal('/') ||' FROM ' || quote_ident(TG_TABLE_NAME::text) || ' WHERE id=' || quote_literal(NEW.parent_ref) INTO STRICT NEW.path;
          END IF;

    ELSIF TG_OP = 'UPDATE' AND (TG_TABLE_NAME::text = 'taxonomy' OR
        TG_TABLE_NAME::text = 'lithology' OR
        TG_TABLE_NAME::text = 'lithostratigraphy' OR
        TG_TABLE_NAME::text = 'mineralogy' OR
        TG_TABLE_NAME::text = 'chronostratigraphy') THEN

        IF NEW.parent_ref IS DISTINCT FROM OLD.parent_ref THEN
          IF NEW.parent_ref = 0 THEN
            NEW.path ='/';
          ELSIF COALESCE(OLD.parent_ref,0) = COALESCE(NEW.parent_ref,0) THEN
            RETURN NEW;
          ELSE
            EXECUTE 'SELECT path || id ||' || quote_literal('/') ||'  FROM ' || quote_ident(TG_TABLE_NAME::text) || ' WHERE id=' || quote_literal(NEW.parent_ref) INTO STRICT NEW.path;
          END IF;

          EXECUTE 'UPDATE ' || quote_ident(TG_TABLE_NAME::text) || ' SET path=replace(path, ' ||  quote_literal(OLD.path || OLD.id || '/') ||' , ' || quote_literal( NEW.path || OLD.id || '/') || ') ' ||
            ' WHERE path like ' || quote_literal(OLD.path || OLD.id || '/%');
        END IF;
--         RAISE INFO 'nothing diff';
  END IF;
  RETURN NEW;
END;
$$
language plpgsql;


CREATE OR REPLACE FUNCTION fct_chk_upper_level_for_childrens() RETURNS TRIGGER
AS $$
DECLARE
  rec_exists integer;
BEGIN

  EXECUTE 'SELECT count(id)  FROM ' || quote_ident(TG_TABLE_NAME::text) || ' WHERE parent_ref=' || quote_literal(NEW.id) || ' AND fct_chk_possible_upper_level('|| quote_literal(TG_TABLE_NAME::text) ||', parent_ref, level_ref, id) = false ' INTO rec_exists;
  
  IF rec_exists > 0 THEN
    RAISE EXCEPTION 'Children of this record does not follow the level hierarchy';
  END IF;
  RETURN NEW;

END;
$$
language plpgsql;

CREATE OR REPLACE FUNCTION get_setting(IN param text, OUT value text)
LANGUAGE plpgsql STABLE STRICT AS
$$BEGIN
  SELECT current_setting(param) INTO value;
  EXCEPTION
  WHEN UNDEFINED_OBJECT THEN
    value := NULL;
END;$$;

CREATE OR REPLACE FUNCTION fct_trk_log_table() RETURNS TRIGGER
AS $$
DECLARE
	user_id integer;
        track_level integer;
	track_fields integer;
	trk_id bigint;
	tbl_row RECORD;
	new_val varchar;
	old_val varchar;
BEGIN



        SELECT COALESCE(get_setting('darwin.track_level'),'10')::integer INTO track_level;
        IF track_level = 0 THEN --NO Tracking
          RETURN NEW;
        ELSIF track_level = 1 THEN -- Track Only Main tables
          IF TG_TABLE_NAME::text NOT IN ('specimens', 'specimen_individuals', 'specimen_parts', 'taxonomy', 'chronostratigraphy', 'lithostratigraphy',
            'mineralogy', 'lithology', 'habitats', 'people') THEN
            RETURN NEW;
          END IF;
        END IF;

	SELECT COALESCE(get_setting('darwin.userid'),'0')::integer INTO user_id;
	IF user_id = 0 THEN
	  RETURN NEW;
	END IF;

	IF TG_OP = 'INSERT' THEN
		INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time, new_value)
				VALUES (TG_TABLE_NAME::text, NEW.id, user_id, 'insert', now(), hstore(NEW)) RETURNING id into trk_id;
	ELSEIF TG_OP = 'UPDATE' THEN

	  IF ROW(NEW.*) IS DISTINCT FROM ROW(OLD.*) THEN
		INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time, new_value, old_value)
		    VALUES (TG_TABLE_NAME::text, NEW.id, user_id, 'update', now(), hstore(NEW), hstore(OLD)) RETURNING id into trk_id;
	  ELSE
	    RAISE info 'unnecessary update on table "%" and id "%"', TG_TABLE_NAME::text, NEW.id;
	  END IF;

	ELSEIF TG_OP = 'DELETE' THEN
		INSERT INTO users_tracking (referenced_relation, record_id, user_ref, action, modification_date_time, old_value)
 			VALUES (TG_TABLE_NAME::text, OLD.id, user_id, 'delete', now(), hstore(OLD));
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

CREATE OR REPLACE FUNCTION ts_stat(tsvector, OUT word text, OUT ndoc integer, OUT nentry integer) RETURNS SETOF record AS
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

   ELSIF TG_TABLE_NAME ='codes' THEN

      IF TG_OP = 'UPDATE' THEN
	IF OLD.full_code_indexed != NEW.full_code_indexed THEN
	  PERFORM fct_cpy_word('codes','full_code_indexed', NEW.full_code_indexed);
	END IF;
      ELSE
	PERFORM fct_cpy_word('codes','full_code_indexed', NEW.full_code_indexed);
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

CREATE OR REPLACE FUNCTION lineToTagRows(IN line text) RETURNS SETOF varchar AS
$$
SELECT distinct(fulltoIndex(tags)) FROM regexp_split_to_table($1, ';') as tags WHERE fulltoIndex(tags) != '' ;
$$
LANGUAGE 'sql' IMMUTABLE STRICT;

CREATE OR REPLACE FUNCTION fct_cpy_gtuTags() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  curs_entry refcursor;
  entry_row RECORD;
  seen_el varchar[];
BEGIN
  IF TG_OP != 'DELETE' THEN
    OPEN curs_entry FOR SELECT distinct(fulltoIndex(tags)) as u_tag, trim(tags) as tags
                        FROM regexp_split_to_table(NEW.tag_value, ';') as tags
                        WHERE fulltoIndex(tags) != '';
    LOOP
      FETCH curs_entry INTO entry_row;
      EXIT WHEN NOT FOUND;

      seen_el := array_append(seen_el, entry_row.u_tag);

      PERFORM * FROM tags
                WHERE gtu_ref = NEW.gtu_ref
                  AND group_ref = NEW.id
                  AND tag_indexed = entry_row.u_tag
                LIMIT 1;
      IF FOUND THEN
        IF OLD.sub_group_name = NEW.sub_group_name THEN
          UPDATE tags
          SET sub_group_type = NEW.sub_group_name
          WHERE group_ref = NEW.id;
        END IF;
        CONTINUE;
      ELSE
        INSERT INTO tags (gtu_ref, group_ref, tag_indexed, tag, group_type, sub_group_type )
        VALUES ( NEW.gtu_ref, NEW.id, entry_row.u_tag, entry_row.tags, NEW.group_name, NEW.sub_group_name);
      END IF;
    END LOOP;

    CLOSE curs_entry;

    UPDATE gtu
    SET tag_values_indexed = (SELECT array_agg(tags_list)
                              FROM (SELECT lineToTagRows(tag_agg) AS tags_list
                                    FROM (SELECT tag_value AS tag_agg
                                          FROM tag_groups
                                          WHERE id <> NEW.id
                                            AND gtu_ref = NEW.gtu_ref
                                          UNION
                                          SELECT NEW.tag_value
                                         ) as tag_list_selection
                                   ) as tags_rows
                             )
    WHERE id = NEW.gtu_ref;

    DELETE FROM tags
           WHERE group_ref = NEW.id
              AND gtu_ref = NEW.gtu_ref
              AND fct_array_find(seen_el, tag_indexed ) IS NULL;
    RETURN NEW;
  ELSE
    UPDATE gtu
    SET tag_values_indexed = (SELECT array_agg(tags_list)
                              FROM (SELECT lineToTagRows(tag_agg) AS tags_list
                                    FROM (SELECT tag_value AS tag_agg
                                          FROM tag_groups
                                          WHERE id <> OLD.id
                                            AND gtu_ref = OLD.gtu_ref
                                         ) as tag_list_selection
                                   ) as tags_rows
                             )
    WHERE id = OLD.gtu_ref;
    RETURN NULL;
  END IF;
END;
$$;

CREATE OR REPLACE FUNCTION fct_cpy_updateHosts() RETURNS TRIGGER
language plpgsql
AS
$$
BEGIN
  IF TG_OP = 'UPDATE' THEN
    IF NEW.taxon_ref <> OLD.taxon_ref THEN
      UPDATE specimens SET host_taxon_ref = NEW.taxon_ref WHERE host_specimen_ref = NEW.id AND id <> NEW.id;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;


CREATE OR REPLACE FUNCTION fct_cpy_updateSpecHostImpact() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  newTaxonRef specimens.host_taxon_ref%TYPE := 0;
BEGIN
  IF TG_OP = 'UPDATE' THEN
    IF NEW.host_specimen_ref <> OLD.host_specimen_ref AND NEW.host_specimen_ref IS NOT NULL THEN
      SELECT taxon_ref INTO STRICT NEW.host_taxon_ref FROM specimens WHERE id = NEW.host_specimen_ref;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;

/**
* When adding or changing a collection,
* Impact changes on rights
*/
CREATE OR REPLACE FUNCTION fct_cpy_updateCollectionRights() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
	db_user_type_val integer ;
BEGIN
  IF TG_OP = 'INSERT' THEN
    INSERT INTO collections_rights (collection_ref, user_ref, db_user_type)
    (SELECT NEW.id as coll_ref, NEW.main_manager_ref as mgr_ref, 4 as user_type
     UNION
     SELECT NEW.id as coll_ref, user_ref as mgr_ref, db_user_type as user_type
     FROM collections_rights
     WHERE collection_ref = NEW.parent_ref
       AND db_user_type = 4
    );
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.main_manager_ref != OLD.main_manager_ref THEN
      SELECT db_user_type INTO db_user_type_val FROM collections_rights WHERE collection_ref = NEW.id AND user_ref = NEW.main_manager_ref;
      IF FOUND AND db_user_type_val is distinct from 4 THEN
        UPDATE collections_rights
        SET db_user_type = 4
        WHERE collection_ref = NEW.id
          AND user_ref = NEW.main_manager_ref;
      ELSE
        INSERT INTO collections_rights (collection_ref, user_ref, db_user_type)
        VALUES(NEW.id,NEW.main_manager_ref,4);
      END IF;
    END IF;
    IF NEW.parent_ref != OLD.parent_ref THEN
      INSERT INTO collections_rights (collection_ref, user_ref, db_user_type)
      (
        SELECT NEW.id, user_ref, db_user_type
        FROM collections_rights
        WHERE collection_ref = NEW.parent_ref
          AND db_user_type = 4
          AND user_ref NOT IN
            (
              SELECT user_ref
              FROM collections_rights
              WHERE collection_ref = NEW.id
            )
      );
    END IF;
  END IF;

  RETURN NEW;

EXCEPTION
  WHEN OTHERS THEN
    RAISE NOTICE 'An error occured: %', SQLERRM;
    RETURN NEW;
END;
$$;

/*
  Before updating a collections rights entry,
  check we don't do a modification which wouldn't be in accordance with main manager of the concerned collection
*/
CREATE OR REPLACE FUNCTION fct_chk_canUpdateCollectionsRights() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  mgrName varchar;
  booContinue boolean := false;
BEGIN
  /*Check an unpromotion occurs by modifying db_user_type explicitely or implicitely by replacing a user by an other
    or moving a user from one collection to an other
  */
  IF (NEW.db_user_type < 4 AND OLD.db_user_type >=4) OR NEW.collection_ref != OLD.collection_ref OR NEW.user_ref != OLD.user_ref THEN
    SELECT formated_name INTO mgrName
    FROM collections INNER JOIN users ON users.id = collections.main_manager_ref
    WHERE collections.id = OLD.collection_ref
      AND main_manager_ref = OLD.user_ref;
    /*If user concerned still main manager of the collection, cannot be updated*/
    IF FOUND THEN
      RAISE EXCEPTION 'This manager (%) cannot be updated because he/she is still defined as a main manager for this collection', mgrName;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;

/*
  After inserting, updating or deleting a user in collections_rights -> impact user rights
*/
CREATE OR REPLACE FUNCTION fct_cpy_updateUserRights() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  db_user_type_val integer ;
  booCollFound boolean;
  booContinue boolean;
BEGIN
  /*When updating main manager ref -> impact potentially db_user_type
    of new user chosen as manager
  */
  IF TG_TABLE_NAME = 'collections' THEN
    /*We take in count only an update
      An insertion as it's creating an entry in collections_rights will trigger this current trigger again ;)
    */
    IF TG_OP = 'UPDATE' THEN
      IF NEW.main_manager_ref != OLD.main_manager_ref THEN
        UPDATE users
        SET db_user_type = 4
        WHERE id = NEW.main_manager_ref
          AND db_user_type < 4;
      END IF;
    END IF;
  ELSE -- trigger on collections_rights table
    IF TG_OP = 'INSERT' THEN
      /*If user is promoted by inserting her/him
        with a higher db_user_type than she/he is -> promote her/him
      */
      UPDATE users
      SET db_user_type = NEW.db_user_type
      WHERE id = NEW.user_ref
        AND db_user_type < NEW.db_user_type;
    END IF;
    IF TG_OP = 'UPDATE' THEN
      /*First case: replacing a user by an other*/
      IF NEW.user_ref != OLD.user_ref THEN
        /*Update the user db_user_type chosen as the new one as if it would be an insertion*/
        UPDATE users
        SET db_user_type = NEW.db_user_type
        WHERE id = NEW.user_ref
          AND db_user_type < NEW.db_user_type;
        /*Un promote the user replaced if necessary*/
        UPDATE users
          SET db_user_type = subq.db_user_type_max
          FROM (
                SELECT COALESCE(MAX(db_user_type),1) as db_user_type_max
                FROM collections_rights
                WHERE user_ref = OLD.user_ref
              ) subq
          WHERE id = OLD.user_ref
            AND db_user_type != 8;
      END IF;
      IF NEW.db_user_type != OLD.db_user_type THEN
        /* Promotion */
        IF NEW.db_user_type > OLD.db_user_type THEN
          UPDATE users
          SET db_user_type = NEW.db_user_type
          WHERE id = NEW.user_ref
            AND db_user_type < NEW.db_user_type;
        /* Unpromotion */
        ELSE
          UPDATE users
          SET db_user_type = subq.db_user_type_max
          FROM (
                SELECT COALESCE(MAX(db_user_type),1) as db_user_type_max
                FROM collections_rights
                WHERE user_ref = NEW.user_ref
              ) subq
          WHERE id = NEW.user_ref
            AND db_user_type != 8;
        END IF;
      END IF;
    END IF;
    IF TG_OP = 'DELETE' THEN
      IF OLD.db_user_type >=4 THEN
        SELECT true
        INTO booCollFound
        FROM collections
        WHERE id = OLD.collection_ref
          AND main_manager_ref = OLD.user_ref;
        IF FOUND THEN
          RAISE EXCEPTION 'You try to delete a manager who is still defined as a main manager of the current collection';
        END IF;
      END IF;
      UPDATE users
      SET db_user_type = subq.db_user_type_max
      FROM (
            SELECT COALESCE(MAX(db_user_type),1) as db_user_type_max
            FROM collections_rights
            WHERE user_ref = OLD.user_ref
           ) subq
      WHERE id = OLD.user_ref
        AND db_user_type != 8;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION getTagsIndexedAsArray(IN tagList varchar) returns varchar[] LANGUAGE SQL IMMUTABLE AS
$$
  SELECT array_agg(tags) FROM (SELECT lineToTagRows($1) as tags) as subQuery;
$$;


CREATE OR REPLACE FUNCTION fct_update_darwin_flat() returns TRIGGER
language plpgsql
AS
$$
DECLARE
  indCount INTEGER := 0;
  partCount INTEGER := 0;
  indType BOOLEAN := false;
BEGIN
  IF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'expeditions' THEN
    IF NEW.name_indexed IS DISTINCT FROM OLD.name_indexed THEN
      UPDATE darwin_flat
      SET (expedition_name, expedition_name_ts, expedition_name_indexed) =
          (NEW.name, NEW.name_ts, NEW.name_indexed)
      WHERE expedition_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'collections' THEN
    UPDATE darwin_flat
    SET (collection_type, collection_code, collection_name, collection_is_public,
         collection_institution_ref, collection_institution_formated_name, collection_institution_formated_name_ts,
         collection_institution_formated_name_indexed, collection_institution_sub_type, collection_main_manager_ref,
         collection_main_manager_formated_name, collection_main_manager_formated_name_ts, collection_main_manager_formated_name_indexed,
         collection_parent_ref, collection_path
        ) =
        (NEW.collection_type, NEW.code, NEW.name, NEW.is_public,
         NEW.institution_ref, subq.ins_formated_name, subq.ins_formated_name_ts,
         subq.ins_formated_name_indexed, subq.ins_sub_type, NEW.main_manager_ref,
         subq.peo_formated_name, subq.peo_formated_name_ts, subq.peo_formated_name_indexed,
         NEW.parent_ref, NEW.path
        )
        FROM
        (SELECT ins.formated_name as ins_formated_name, ins.formated_name_ts as ins_formated_name_ts,
                ins.formated_name_indexed as ins_formated_name_indexed, ins.sub_type as ins_sub_type,
                peo.formated_name as peo_formated_name, peo.formated_name_ts as peo_formated_name_ts,
                peo.formated_name_indexed as peo_formated_name_indexed
         FROM people ins, users peo
         WHERE ins.id = NEW.institution_ref
           AND peo.id = NEW.main_manager_ref
         LIMIT 1
        ) subq
    WHERE collection_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'people' THEN
    IF NOT NEW.is_physical THEN
      IF NEW.is_physical IS DISTINCT FROM OLD.is_physical 
      OR NEW.formated_name_indexed IS DISTINCT FROM OLD.formated_name_indexed 
      OR NEW.sub_type IS DISTINCT FROM OLD.sub_type
      THEN
        UPDATE darwin_flat
        SET (collection_institution_formated_name,
             collection_institution_formated_name_ts,
             collection_institution_formated_name_indexed,
             collection_institution_sub_type
            ) =
            (NEW.formated_name, NEW.formated_name_ts, NEW.formated_name_indexed, NEW.sub_type)
        WHERE collection_institution_ref = NEW.id;
      END IF;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'users' THEN
    IF NEW.formated_name_indexed IS DISTINCT FROM OLD.formated_name_indexed THEN
      UPDATE darwin_flat
      SET (collection_main_manager_formated_name,
           collection_main_manager_formated_name_ts,
           collection_main_manager_formated_name_indexed
          ) =
          (NEW.formated_name, NEW.formated_name_ts, NEW.formated_name_indexed)
      WHERE collection_main_manager_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'gtu' THEN
    UPDATE darwin_flat
    SET (gtu_code, gtu_parent_ref, gtu_path, gtu_from_date, gtu_from_date_mask,
         gtu_to_date, gtu_to_date_mask, gtu_tag_values_indexed, gtu_location
        ) =
        (NEW.code, NEW.parent_ref, NEW.path, NEW.gtu_from_date, NEW.gtu_from_date_mask,
         NEW.gtu_to_date, NEW.gtu_to_date_mask, NEW.tag_values_indexed, new.location
        )
    WHERE gtu_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'igs' THEN
    IF NEW.ig_num_indexed IS DISTINCT FROM OLD.ig_num_indexed OR NEW.ig_date IS DISTINCT FROM OLD.ig_date THEN
      UPDATE darwin_flat
      SET (ig_num, ig_num_indexed, ig_date, ig_date_mask) =
          (NEW.ig_num, NEW.ig_num_indexed, NEW.ig_date, NEW.ig_date_mask)
      WHERE ig_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'taxonomy' THEN
    UPDATE darwin_flat
    SET (taxon_name, taxon_name_indexed, taxon_name_order_by,
         taxon_level_ref, taxon_level_name,
         taxon_status, taxon_path, taxon_parent_ref, taxon_extinct
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, NEW.path, NEW.parent_ref, NEW.extinct
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE taxon_ref = NEW.id;
    UPDATE darwin_flat
    SET (host_taxon_name, host_taxon_name_indexed, host_taxon_name_order_by,
         host_taxon_level_ref, host_taxon_level_name,
         host_taxon_status, host_taxon_path, host_taxon_parent_ref, host_taxon_extinct
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, NEW.path, NEW.parent_ref, NEW.extinct
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE host_taxon_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'chronostratigraphy' THEN
    UPDATE darwin_flat
    SET (chrono_name, chrono_name_indexed, chrono_name_order_by,
         chrono_level_ref, chrono_level_name,
         chrono_status, 
         chrono_local, chrono_color,
         chrono_path, chrono_parent_ref
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, 
         NEW.local_naming, NEW.color,
         NEW.path, NEW.parent_ref
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE chrono_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'lithostratigraphy' THEN
    UPDATE darwin_flat
    SET (litho_name, litho_name_indexed, litho_name_order_by,
         litho_level_ref, litho_level_name,
         litho_status, 
         litho_local, litho_color,
         litho_path, litho_parent_ref
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, 
         NEW.local_naming, NEW.color,
         NEW.path, NEW.parent_ref
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE litho_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'lithology' THEN
    UPDATE darwin_flat
    SET (lithology_name, lithology_name_indexed, lithology_name_order_by,
         lithology_level_ref, lithology_level_name,
         lithology_status, 
         lithology_local, lithology_color,
         lithology_path, lithology_parent_ref
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, 
         NEW.local_naming, NEW.color,
         NEW.path, NEW.parent_ref
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE lithology_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'mineralogy' THEN
    UPDATE darwin_flat
    SET (mineral_name, mineral_name_indexed, mineral_name_order_by,
         mineral_level_ref, mineral_level_name,
         mineral_status, 
         mineral_local, mineral_color,
         mineral_path, mineral_parent_ref
        ) =
        (NEW.name, NEW.name_indexed, NEW.name_order_by,
         NEW.level_ref, subq.level_name,
         NEW.status, 
         NEW.local_naming, NEW.color,
         NEW.path, NEW.parent_ref
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE mineral_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'specimens' THEN
    UPDATE darwin_flat
    SET (category,
         collection_ref,collection_type,collection_code,collection_name, collection_is_public,
         collection_institution_ref,collection_institution_formated_name,
         collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
         collection_main_manager_ref,collection_main_manager_formated_name,
         collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
         collection_parent_ref,collection_path,
         expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
         station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
         gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
         gtu_tag_values_indexed,gtu_country_tag_value,
         taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
         taxon_path,taxon_parent_ref,taxon_extinct,
         chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
         chrono_local, chrono_color,
         chrono_path,chrono_parent_ref,
         litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
         litho_local, litho_color,
         litho_path,litho_parent_ref,
         lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
         lithology_local, lithology_color,
         lithology_path,lithology_parent_ref,
         mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
         mineral_local, mineral_color,
         mineral_path,mineral_parent_ref,
         host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
         host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
         host_specimen_ref,
         acquisition_category,acquisition_date_mask,acquisition_date
        )=
        (NEW.category,
         NEW.collection_ref, subq.coll_collection_type, subq.coll_code, subq.coll_name, subq.coll_is_public,
         subq.coll_institution_ref, subq.ins_formated_name,
         subq.ins_formated_name_ts, subq.ins_formated_name_indexed, subq.ins_sub_type,
         subq.coll_main_manager_ref, subq.peo_formated_name,
         subq.peo_formated_name_ts, subq.peo_formated_name_indexed,
         subq.coll_parent_ref, subq.coll_path,
         NEW.expedition_ref, subq.expe_name, subq.expe_name_ts, subq.expe_name_indexed,
         NEW.station_visible, NEW.gtu_ref, subq.gtu_code, subq.gtu_parent_ref, subq.gtu_path, subq.gtu_location,
         subq.gtu_from_date_mask, subq.gtu_from_date, subq.gtu_to_date_mask, subq.gtu_to_date,
         subq.gtu_tag_values_indexed, subq.taggr_tag_value,
         NEW.taxon_ref, subq.taxon_name, subq.taxon_name_indexed, subq.taxon_name_order_by,
         subq.taxon_level_ref, subq.taxon_level_level_name, subq.taxon_status,
         subq.taxon_path, subq.taxon_parent_ref, subq.taxon_extinct,
         NEW.chrono_ref, subq.chrono_name, subq.chrono_name_indexed, subq.chrono_name_order_by,
         subq.chrono_level_ref, subq.chrono_level_level_name, subq.chrono_status,
         subq.chrono_local, subq.chrono_color,
         subq.chrono_path, subq.chrono_parent_ref,
         NEW.litho_ref, subq.litho_name, subq.litho_name_indexed, subq.litho_name_order_by,
         subq.litho_level_ref, subq.litho_level_level_name, subq.litho_status,
         subq.litho_local, subq.litho_color,
         subq.litho_path, subq.litho_parent_ref,
         NEW.lithology_ref, subq.lithology_name, subq.lithology_name_indexed, subq.lithology_name_order_by,
         subq.lithology_level_ref, subq.lithology_level_level_name, subq.lithology_status,
         subq.lithology_local, subq.lithology_color,
         subq.lithology_path, subq.lithology_parent_ref,
         NEW.mineral_ref, subq.mineral_name, subq.mineral_name_indexed, subq.mineral_name_order_by,
         subq.mineral_level_ref, subq.mineral_level_level_name, subq.mineral_status,
         subq.mineral_local, subq.mineral_color,
         subq.mineral_path, subq.mineral_parent_ref,
         NEW.host_taxon_ref, NEW.host_relationship, subq.host_taxon_name, subq.host_taxon_name_indexed, subq.host_taxon_name_order_by,
         subq.host_taxon_level_ref, subq.host_taxon_level_level_name, subq.host_taxon_status,
         subq.host_taxon_path, subq.host_taxon_parent_ref, subq.host_taxon_extinct,
         NEW.host_specimen_ref,
         NEW.acquisition_category, NEW.acquisition_date_mask, NEW.acquisition_date
        )
        FROM
        (SELECT coll.collection_type coll_collection_type, coll.code coll_code, coll.name coll_name, coll.is_public coll_is_public,
                coll.institution_ref coll_institution_ref, ins.formated_name ins_formated_name,
                ins.formated_name_ts ins_formated_name_ts, ins.formated_name_indexed ins_formated_name_indexed, ins.sub_type ins_sub_type,
                coll.main_manager_ref coll_main_manager_ref, peo.formated_name peo_formated_name,
                peo.formated_name_ts peo_formated_name_ts, peo.formated_name_indexed peo_formated_name_indexed,
                coll.parent_ref coll_parent_ref, coll.path coll_path,
                expe.name expe_name, expe.name_ts expe_name_ts, expe.name_indexed expe_name_indexed,
                gtu.code gtu_code, gtu.parent_ref gtu_parent_ref, gtu.path gtu_path,gtu.location gtu_location,
                gtu.gtu_from_date_mask, gtu.gtu_from_date, gtu.gtu_to_date_mask, gtu.gtu_to_date,
                gtu.tag_values_indexed gtu_tag_values_indexed, taggr.tag_value taggr_tag_value,
                taxon.name taxon_name, taxon.name_indexed taxon_name_indexed, taxon.name_order_by taxon_name_order_by,
                taxon.level_ref taxon_level_ref, taxon_level.level_name taxon_level_level_name, taxon.status taxon_status,
                taxon.path taxon_path, taxon.parent_ref taxon_parent_ref, taxon.extinct taxon_extinct,
                chrono.name chrono_name, chrono.name_indexed chrono_name_indexed, chrono.name_order_by chrono_name_order_by,
                chrono.level_ref chrono_level_ref, chrono_level.level_name chrono_level_level_name, chrono.status chrono_status,
                chrono.local_naming chrono_local, chrono.color chrono_color,
                chrono.path chrono_path, chrono.parent_ref chrono_parent_ref,
                litho.name litho_name, litho.name_indexed litho_name_indexed, litho.name_order_by litho_name_order_by,
                litho.level_ref litho_level_ref, litho_level.level_name litho_level_level_name, litho.status litho_status,
                litho.local_naming litho_local, litho.color litho_color,
                litho.path litho_path, litho.parent_ref litho_parent_ref,
                lithology.name lithology_name, lithology.name_indexed lithology_name_indexed, lithology.name_order_by lithology_name_order_by,
                lithology.level_ref lithology_level_ref, lithology_level.level_name lithology_level_level_name, lithology.status lithology_status,
                lithology.local_naming lithology_local, lithology.color lithology_color,
                lithology.path lithology_path, lithology.parent_ref lithology_parent_ref,
                mineral.name mineral_name, mineral.name_indexed mineral_name_indexed, mineral.name_order_by mineral_name_order_by,
                mineral.level_ref mineral_level_ref, mineral_level.level_name mineral_level_level_name, mineral.status mineral_status,
                mineral.local_naming mineral_local, mineral.color mineral_color,
                mineral.path mineral_path, mineral.parent_ref mineral_parent_ref,
                host_taxon.name host_taxon_name, host_taxon.name_indexed host_taxon_name_indexed, host_taxon.name_order_by host_taxon_name_order_by,
                host_taxon.level_ref host_taxon_level_ref, host_taxon_level.level_name host_taxon_level_level_name, host_taxon.status host_taxon_status,
                host_taxon.path host_taxon_path, host_taxon.parent_ref host_taxon_parent_ref, host_taxon.extinct host_taxon_extinct
         FROM (collections coll INNER JOIN people ins ON coll.institution_ref = ins.id
                                INNER JOIN users peo ON coll.main_manager_ref = peo.id
              ),
              expeditions expe,
              (gtu LEFT JOIN tag_groups taggr ON gtu.id = taggr.gtu_ref AND taggr.group_name_indexed = 'administrativearea' AND taggr.sub_group_name_indexed = 'country'),
              (taxonomy taxon INNER JOIN catalogue_levels taxon_level ON taxon.level_ref = taxon_level.id),
              (taxonomy host_taxon INNER JOIN catalogue_levels host_taxon_level ON host_taxon.level_ref = host_taxon_level.id),
              (chronostratigraphy chrono INNER JOIN catalogue_levels chrono_level ON chrono.level_ref = chrono_level.id),
              (lithostratigraphy litho INNER JOIN catalogue_levels litho_level ON litho.level_ref = litho_level.id),
              (lithology INNER JOIN catalogue_levels lithology_level ON lithology.level_ref = lithology_level.id),
              (mineralogy mineral INNER JOIN catalogue_levels mineral_level ON mineral.level_ref = mineral_level.id)
         WHERE coll.id = NEW.collection_ref
           AND expe.id = NEW.expedition_ref
           AND gtu.id = NEW.gtu_ref
           AND taxon.id = NEW.taxon_ref
           AND host_taxon.id = NEW.host_taxon_ref
           AND chrono.id = NEW.chrono_ref
           AND litho.id = NEW.litho_ref
           AND lithology.id = NEW.lithology_ref
           AND mineral.id = NEW.mineral_ref
         LIMIT 1
        ) subq
    WHERE spec_ref = NEW.id;
  ELSIF TG_OP = 'INSERT' AND TG_TABLE_NAME = 'specimens' THEN
    INSERT INTO darwin_flat
    (spec_ref,category,
     collection_ref,collection_type,collection_code,collection_name, collection_is_public,
     collection_institution_ref,collection_institution_formated_name,
     collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
     collection_main_manager_ref,collection_main_manager_formated_name,
     collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
     collection_parent_ref,collection_path,
     expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
     station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
     gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
     gtu_tag_values_indexed,gtu_country_tag_value,
     taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
     taxon_path,taxon_parent_ref,taxon_extinct,
     chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
     chrono_local,chrono_color,
     chrono_path,chrono_parent_ref,
     litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
     litho_local,litho_color,
     litho_path,litho_parent_ref,
     lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
     lithology_local,lithology_color,
     lithology_path,lithology_parent_ref,
     mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
     mineral_local,mineral_color,
     mineral_path,mineral_parent_ref,
     host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
     host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
     host_specimen_ref,
     acquisition_category,acquisition_date_mask,acquisition_date
    )
    (SELECT NEW.id, NEW.category,
            NEW.collection_ref, coll.collection_type, coll.code, coll.name, coll.is_public,
            coll.institution_ref, ins.formated_name,
            ins.formated_name_ts, ins.formated_name_indexed, ins.sub_type,
            coll.main_manager_ref, peo.formated_name,
            peo.formated_name_ts, peo.formated_name_indexed,
            coll.parent_ref, coll.path,
            NEW.expedition_ref, expe.name, expe.name_ts, expe.name_indexed,
            NEW.station_visible, NEW.gtu_ref, gtu.code, gtu.parent_ref, gtu.path, gtu.location,
            gtu.gtu_from_date_mask, gtu.gtu_from_date, gtu.gtu_to_date_mask, gtu.gtu_to_date,
            gtu.tag_values_indexed, taggr.tag_value,
            NEW.taxon_ref, taxon.name, taxon.name_indexed, taxon.name_order_by, taxon.level_ref, taxon_level.level_name, taxon.status,
            taxon.path, taxon.parent_ref, taxon.extinct,
            NEW.chrono_ref, chrono.name, chrono.name_indexed, chrono.name_order_by, chrono.level_ref, chrono_level.level_name, chrono.status,
            chrono.local_naming, chrono.color,
            chrono.path, chrono.parent_ref,
            NEW.litho_ref, litho.name, litho.name_indexed, litho.name_order_by, litho.level_ref, litho_level.level_name, litho.status,
            litho.local_naming,litho.color,
            litho.path, litho.parent_ref,
            NEW.lithology_ref, lithology.name, lithology.name_indexed, lithology.name_order_by, lithology.level_ref, lithology_level.level_name, lithology.status,
            lithology.local_naming,lithology.color,
            lithology.path, lithology.parent_ref,
            NEW.mineral_ref, mineral.name, mineral.name_indexed, mineral.name_order_by, mineral.level_ref, mineral_level.level_name, mineral.status,
            mineral.local_naming,mineral.color,
            mineral.path, mineral.parent_ref,
            NEW.host_taxon_ref, NEW.host_relationship, host_taxon.name, host_taxon.name_indexed, host_taxon.name_order_by, host_taxon.level_ref, host_taxon_level.level_name, host_taxon.status,
            host_taxon.path, host_taxon.parent_ref, host_taxon.extinct,
            NEW.host_specimen_ref,
            NEW.acquisition_category, NEW.acquisition_date_mask, NEW.acquisition_date
     FROM (collections coll INNER JOIN people ins ON coll.institution_ref = ins.id
                            INNER JOIN users peo ON coll.main_manager_ref = peo.id
          ),
          expeditions expe,
          (gtu LEFT JOIN tag_groups taggr ON gtu.id = taggr.gtu_ref AND taggr.group_name_indexed = 'administrativearea' AND taggr.sub_group_name_indexed = 'country'),
          (taxonomy taxon INNER JOIN catalogue_levels taxon_level ON taxon.level_ref = taxon_level.id),
          (taxonomy host_taxon INNER JOIN catalogue_levels host_taxon_level ON host_taxon.level_ref = host_taxon_level.id),
          (chronostratigraphy chrono INNER JOIN catalogue_levels chrono_level ON chrono.level_ref = chrono_level.id),
          (lithostratigraphy litho INNER JOIN catalogue_levels litho_level ON litho.level_ref = litho_level.id),
          (lithology INNER JOIN catalogue_levels lithology_level ON lithology.level_ref = lithology_level.id),
          (mineralogy mineral INNER JOIN catalogue_levels mineral_level ON mineral.level_ref = mineral_level.id)
     WHERE coll.id = NEW.collection_ref
       AND expe.id = NEW.expedition_ref
       AND gtu.id = NEW.gtu_ref
       AND taxon.id = NEW.taxon_ref
       AND host_taxon.id = NEW.host_taxon_ref
       AND chrono.id = NEW.chrono_ref
       AND litho.id = NEW.litho_ref
       AND lithology.id = NEW.lithology_ref
       AND mineral.id = NEW.mineral_ref
     LIMIT 1
    );
  END IF;
  IF TG_TABLE_NAME = 'tag_groups' THEN
    IF TG_OP = 'INSERT' THEN
      IF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'country' THEN
        UPDATE darwin_flat
        SET gtu_country_tag_value = NEW.tag_value
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
    ELSIF TG_OP = 'UPDATE' THEN
      IF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'country' THEN
        UPDATE darwin_flat
        SET gtu_country_tag_value = NEW.tag_value
        WHERE gtu_ref = NEW.gtu_ref;
      ELSIF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'country' THEN
        UPDATE darwin_flat
        SET gtu_country_tag_value = NULL
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
    ELSIF TG_OP = 'DELETE' THEN
      IF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'country' THEN
        UPDATE darwin_flat
        SET gtu_country_tag_value = NULL
        WHERE gtu_ref = OLD.gtu_ref;
      END IF;
    END IF;
  END IF;
  IF TG_TABLE_NAME = 'specimen_individuals' THEN
    /*Check there's at least one type - if insertion, this new one is taken in count due to after insert trigger*/
    SELECT true INTO indType FROM specimen_individuals WHERE specimen_ref = NEW.specimen_ref AND type_group <> 'specimen' LIMIT 1;
    IF NOT FOUND THEN
      indType := false;
    END IF;
    IF TG_OP = 'INSERT' THEN
      /*Test if the new individual inserted is the only one for the specimen concerned*/
      SELECT COUNT(*) INTO indCount FROM specimen_individuals WHERE specimen_ref = NEW.specimen_ref;
      IF indCount = 1 THEN
        /*If it's the case, update the line concerned*/
        UPDATE darwin_flat
        SET
        (with_individuals,
         individual_ref,
         individual_type, individual_type_group, individual_type_search,
         individual_sex, individual_state, individual_stage,
         individual_social_status, individual_rock_form,
         individual_count_min, individual_count_max
        )
        =
        (true,
         NEW.id,
         NEW.type, NEW.type_group, NEW.type_search,
         NEW.sex, NEW.state, NEW.stage,
         NEW.social_status, NEW.rock_form,
         NEW.specimen_individuals_count_min, NEW.specimen_individuals_count_max
        )
        WHERE spec_ref = NEW.specimen_ref;
      ELSE
        /*If not insert new line*/
        INSERT INTO darwin_flat
        (spec_ref,category,
         collection_ref,collection_type,collection_code,collection_name,collection_is_public,
         collection_institution_ref,collection_institution_formated_name,
         collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
         collection_main_manager_ref,collection_main_manager_formated_name,
         collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
         collection_parent_ref,collection_path,
         expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
         station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
         gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
         gtu_tag_values_indexed,gtu_country_tag_value,
         taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
         taxon_path,taxon_parent_ref,taxon_extinct,
         chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
         chrono_local,chrono_color,
         chrono_path,chrono_parent_ref,
         litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
         litho_local,litho_color,
         litho_path,litho_parent_ref,
         lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
         lithology_local,lithology_color,
         lithology_path,lithology_parent_ref,
         mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
         mineral_local,mineral_color,
         mineral_path,mineral_parent_ref,
         host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
         host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
         host_specimen_ref,
         acquisition_category,acquisition_date_mask,acquisition_date,
         ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
         with_individuals,
         individual_ref,
         individual_type, individual_type_group, individual_type_search,
         individual_sex, individual_state, individual_stage,
         individual_social_status, individual_rock_form,
         individual_count_min, individual_count_max
        )
        (SELECT
         spec_ref,category,
         collection_ref,collection_type,collection_code,collection_name,collection_is_public,
         collection_institution_ref,collection_institution_formated_name,
         collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
         collection_main_manager_ref,collection_main_manager_formated_name,
         collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
         collection_parent_ref,collection_path,
         expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
         station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
         gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
         gtu_tag_values_indexed,gtu_country_tag_value,
         taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
         taxon_path,taxon_parent_ref,taxon_extinct,
         chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
         chrono_local,chrono_color,
         chrono_path,chrono_parent_ref,
         litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
         litho_local,litho_color,
         litho_path,litho_parent_ref,
         lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
         lithology_local,lithology_color,
         lithology_path,lithology_parent_ref,
         mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
         mineral_local,mineral_color,
         mineral_path,mineral_parent_ref,
         host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
         host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
         host_specimen_ref,
         acquisition_category,acquisition_date_mask,acquisition_date,
         ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
         true,
         NEW.id,
         NEW.type, NEW.type_group, NEW.type_search,
         NEW.sex, NEW.state, NEW.stage,
         NEW.social_status, NEW.rock_form,
         NEW.specimen_individuals_count_min, NEW.specimen_individuals_count_max
         FROM darwin_flat
         WHERE spec_ref = NEW.specimen_ref
         LIMIT 1
        );
      END IF;
    ELSIF TG_OP = 'UPDATE' THEN
      /*If it's an update, update first individuals data*/
      UPDATE darwin_flat
      SET
      (individual_ref,
       individual_type, individual_type_group, individual_type_search,
       individual_sex, individual_state, individual_stage,
       individual_social_status, individual_rock_form,
       individual_count_min, individual_count_max
      )
      =
      (NEW.id,
       NEW.type, NEW.type_group, NEW.type_search,
       NEW.sex, NEW.state, NEW.stage,
       NEW.social_status, NEW.rock_form,
       NEW.specimen_individuals_count_min, NEW.specimen_individuals_count_max
      )
      WHERE individual_ref = NEW.id;
      /*If it's a move from one specimen to an other, then...*/
      IF OLD.specimen_ref != NEW.specimen_ref THEN
        /*Check first if the individual moved was the last one attached to the specimen concerned*/
        SELECT COUNT(*) INTO indCount FROM specimen_individuals WHERE specimen_ref = OLD.specimen_ref AND id != OLD.id;
        IF indCount = 0 THEN
          /*If it's the case, create an empty line of the specimen the individual is moved from*/
          INSERT INTO darwin_flat
          ( spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date
          )
          (SELECT
            spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date
            FROM darwin_flat
            WHERE spec_ref = OLD.specimen_ref
            LIMIT 1
          );
        END IF;
        /*Then update specimen data for lines for the individual concerned*/
        UPDATE darwin_flat
        SET ( spec_ref,category,
              collection_ref,collection_type,collection_code,collection_name, collection_is_public,
              collection_institution_ref,collection_institution_formated_name,
              collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
              collection_main_manager_ref,collection_main_manager_formated_name,
              collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
              collection_parent_ref,collection_path,
              expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
              station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
              gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
              gtu_tag_values_indexed,gtu_country_tag_value,
              taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
              taxon_path,taxon_parent_ref,taxon_extinct,
              chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
              chrono_local,chrono_color,
              chrono_path,chrono_parent_ref,
              litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
              litho_local,litho_color,
              litho_path,litho_parent_ref,
              lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
              lithology_local,lithology_color,
              lithology_path,lithology_parent_ref,
              mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
              mineral_local,mineral_color,
              mineral_path,mineral_parent_ref,
              host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
              host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
              host_specimen_ref,
              acquisition_category,acquisition_date_mask,acquisition_date,
              ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date
            )=
            ( subq.spec_ref,subq.category,
              subq.collection_ref,subq.collection_type,subq.collection_code,subq.collection_name,subq. collection_is_public,
              subq.collection_institution_ref,subq.collection_institution_formated_name,
              subq.collection_institution_formated_name_ts,subq.collection_institution_formated_name_indexed,subq.collection_institution_sub_type,
              subq.collection_main_manager_ref,subq.collection_main_manager_formated_name,
              subq.collection_main_manager_formated_name_ts,subq.collection_main_manager_formated_name_indexed,
              subq.collection_parent_ref,subq.collection_path,
              subq.expedition_ref,subq.expedition_name,subq.expedition_name_ts,subq.expedition_name_indexed,
              subq.station_visible,subq.gtu_ref,subq.gtu_code,subq.gtu_parent_ref,subq.gtu_path,subq.gtu_location,
              subq.gtu_from_date_mask,subq.gtu_from_date,subq.gtu_to_date_mask,subq.gtu_to_date,
              subq.gtu_tag_values_indexed,subq.gtu_country_tag_value,
              subq.taxon_ref,subq.taxon_name,subq.taxon_name_indexed,subq.taxon_name_order_by,subq.taxon_level_ref,subq.taxon_level_name,subq.taxon_status,
              subq.taxon_path,subq.taxon_parent_ref,subq.taxon_extinct,
              subq.chrono_ref,subq.chrono_name,subq.chrono_name_indexed,subq.chrono_name_order_by,subq.chrono_level_ref,subq.chrono_level_name,subq.chrono_status,
              subq.chrono_local,subq.chrono_color,
              subq.chrono_path,subq.chrono_parent_ref,
              subq.litho_ref,subq.litho_name,subq.litho_name_indexed,subq.litho_name_order_by,subq.litho_level_ref,subq.litho_level_name,subq.litho_status,
              subq.litho_local,subq.litho_color,
              subq.litho_path,subq.litho_parent_ref,
              subq.lithology_ref,subq.lithology_name,subq.lithology_name_indexed,subq.lithology_name_order_by,subq.lithology_level_ref,subq.lithology_level_name,subq.lithology_status,
              subq.lithology_local,subq.lithology_color,
              subq.lithology_path,subq.lithology_parent_ref,
              subq.mineral_ref,subq.mineral_name,subq.mineral_name_indexed,subq.mineral_name_order_by,subq.mineral_level_ref,subq.mineral_level_name,subq.mineral_status,
              subq.mineral_local,subq.mineral_color,
              subq.mineral_path,subq.mineral_parent_ref,
              subq.host_taxon_ref,subq.host_relationship,subq.host_taxon_name,subq.host_taxon_name_indexed,subq.host_taxon_name_order_by,subq.host_taxon_level_ref,subq.host_taxon_level_name,subq.host_taxon_status,
              subq.host_taxon_path,subq.host_taxon_parent_ref,subq.host_taxon_extinct,
              subq.host_specimen_ref,
              subq.acquisition_category,subq.acquisition_date_mask,subq.acquisition_date,
              subq.ig_ref,subq.ig_num,subq.ig_num_indexed,subq.ig_date_mask,subq.ig_date
            )
        FROM
          ( SELECT
            spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date
            FROM darwin_flat
            WHERE spec_ref = NEW.specimen_ref
            LIMIT 1
          ) subq
        WHERE individual_ref = NEW.id;

        /*Check if the specimen the individual is moved to has already individuals*/
        SELECT COUNT(*) INTO indCount FROM specimen_individuals WHERE specimen_ref = NEW.specimen_ref AND id != NEW.id;
        IF indCount = 0 THEN
          /*If not, remove the line with null individual*/
          DELETE FROM darwin_flat
          WHERE spec_ref = NEW.specimen_ref AND individual_ref IS NULL;
        END IF;

      END IF;
    END IF;

    /*And then update with_types value for specimen concerned*/
    UPDATE darwin_flat
    SET with_types = indType
    WHERE spec_ref = NEW.specimen_ref
      AND with_types != indType;

  END IF;
  IF TG_TABLE_NAME = 'specimen_parts' THEN
    IF TG_OP = 'INSERT' THEN

      /*Tell now there are parts for individuals*/
      UPDATE specimen_individuals
      SET with_parts = true
      WHERE id = NEW.specimen_individual_ref
        AND with_parts = false;
      /*Check if it's the first part inserted for the current individual or not*/
      SELECT COUNT(*) INTO partCount FROM specimen_parts WHERE specimen_individual_ref = NEW.specimen_individual_ref;
      IF partCount = 1 THEN
        /*If it's the case, it's only an update that is needed*/
        UPDATE darwin_flat
        SET
        (with_parts,
         part_ref, part, part_status,
         building, "floor", room, "row", shelf,
         container_type, container_storage, "container",
         sub_container_type, sub_container_storage, sub_container,
         part_count_min, part_count_max,
         specimen_status, "complete", surnumerary
        )
        =
        (true,
         NEW.id, NEW.specimen_part, NEW.specimen_status,
         NEW.building, NEW.floor, NEW.room, NEW.row, NEW.shelf,
         NEW.container_type, NEW.container_storage, NEW.container,
         NEW.sub_container_type, NEW.sub_container_storage, NEW.sub_container,
         NEW.specimen_part_count_min, NEW.specimen_part_count_max,
         NEW.specimen_status, NEW.complete, NEW.surnumerary
        )
        WHERE individual_ref = NEW.specimen_individual_ref;
      ELSE
        /*Otherwise, the new individual is inserted with specimen data*/
        INSERT INTO darwin_flat
        (spec_ref,category,
         collection_ref,collection_type,collection_code,collection_name,collection_is_public,
         collection_institution_ref,collection_institution_formated_name,
         collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
         collection_main_manager_ref,collection_main_manager_formated_name,
         collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
         collection_parent_ref,collection_path,
         expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
         station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
         gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
         gtu_tag_values_indexed,gtu_country_tag_value,
         taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
         taxon_path,taxon_parent_ref,taxon_extinct,
         chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
         chrono_local,chrono_color,
         chrono_path,chrono_parent_ref,
         litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
         litho_local,litho_color,
         litho_path,litho_parent_ref,
         lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
         lithology_local,lithology_color,
         lithology_path,lithology_parent_ref,
         mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
         mineral_local,mineral_color,
         mineral_path,mineral_parent_ref,
         host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
         host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
         host_specimen_ref,
         acquisition_category,acquisition_date_mask,acquisition_date,
         ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
         with_types, with_individuals,
         individual_ref,
         individual_type, individual_type_group, individual_type_search,
         individual_sex, individual_state, individual_stage,
         individual_social_status, individual_rock_form,
         individual_count_min, individual_count_max,
         with_parts,
         part_ref, part, part_status,
         building, "floor", room, "row", shelf,
         container_type, container_storage, "container",
         sub_container_type, sub_container_storage, sub_container,
         part_count_min, part_count_max,
         specimen_status, "complete", surnumerary
        )
        (SELECT
         spec_ref,category,
         collection_ref,collection_type,collection_code,collection_name,collection_is_public,
         collection_institution_ref,collection_institution_formated_name,
         collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
         collection_main_manager_ref,collection_main_manager_formated_name,
         collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
         collection_parent_ref,collection_path,
         expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
         station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
         gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
         gtu_tag_values_indexed,gtu_country_tag_value,
         taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
         taxon_path,taxon_parent_ref,taxon_extinct,
         chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
         chrono_local,chrono_color,
         chrono_path,chrono_parent_ref,
         litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
         litho_local,litho_color,
         litho_path,litho_parent_ref,
         lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
         lithology_local,lithology_color,
         lithology_path,lithology_parent_ref,
         mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
         mineral_local,mineral_color,
         mineral_path,mineral_parent_ref,
         host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
         host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
         host_specimen_ref,
         acquisition_category,acquisition_date_mask,acquisition_date,
         ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
         with_types, with_individuals,
         individual_ref,
         individual_type, individual_type_group, individual_type_search,
         individual_sex, individual_state, individual_stage,
         individual_social_status, individual_rock_form,
         individual_count_min, individual_count_max,
         true,
         NEW.id, NEW.specimen_part, NEW.specimen_status,
         NEW.building, NEW.floor, NEW.room, NEW.row, NEW.shelf,
         NEW.container_type, NEW.container_storage, NEW.container,
         NEW.sub_container_type, NEW.sub_container_storage, NEW.sub_container,
         NEW.specimen_part_count_min, NEW.specimen_part_count_max,
         NEW.specimen_status, NEW.complete, NEW.surnumerary
         FROM darwin_flat
         WHERE individual_ref = NEW.specimen_individual_ref
         LIMIT 1
        );
      END IF;
    ELSIF TG_OP = 'UPDATE' THEN
      /*Update corresponding parts data in darwin_flat*/
      UPDATE darwin_flat
      SET
      (part_ref, part, part_status,
       building, "floor", room, "row", shelf,
       container_type, container_storage, "container",
       sub_container_type, sub_container_storage, sub_container,
       part_count_min, part_count_max,
       specimen_status, "complete", surnumerary
      )
      =
      (NEW.id, NEW.specimen_part, NEW.specimen_status,
       NEW.building, NEW.floor, NEW.room, NEW.row, NEW.shelf,
       NEW.container_type, NEW.container_storage, NEW.container,
       NEW.sub_container_type, NEW.sub_container_storage, NEW.sub_container,
       NEW.specimen_part_count_min, NEW.specimen_part_count_max,
       NEW.specimen_status, NEW.complete, NEW.surnumerary
      )
      WHERE part_ref = NEW.id;
      /*Check if the part is moved from one individual to an other*/
      IF OLD.specimen_individual_ref != NEW.specimen_individual_ref THEN
        /*Check if the part moved is the last one attached to the individual concerned*/
        SELECT COUNT(*) INTO partCount FROM specimen_parts WHERE specimen_individual_ref = OLD.specimen_individual_ref AND id != OLD.id;
        IF partCount = 0 THEN
          INSERT INTO darwin_flat
          ( spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
            with_types, with_individuals,
            individual_ref,
            individual_type, individual_type_group, individual_type_search,
            individual_sex, individual_state, individual_stage,
            individual_social_status, individual_rock_form,
            individual_count_min, individual_count_max
          )
          (SELECT
            spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
            with_types, with_individuals,
            individual_ref,
            individual_type, individual_type_group, individual_type_search,
            individual_sex, individual_state, individual_stage,
            individual_social_status, individual_rock_form,
            individual_count_min, individual_count_max
            FROM darwin_flat
            WHERE individual_ref = OLD.specimen_individual_ref
            LIMIT 1
          );
        END IF;
        /*Then update specimen and individuals data for the part moved*/
        UPDATE darwin_flat
        SET ( spec_ref,category,
              collection_ref,collection_type,collection_code,collection_name, collection_is_public,
              collection_institution_ref,collection_institution_formated_name,
              collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
              collection_main_manager_ref,collection_main_manager_formated_name,
              collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
              collection_parent_ref,collection_path,
              expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
              station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
              gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
              gtu_tag_values_indexed,gtu_country_tag_value,
              taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
              taxon_path,taxon_parent_ref,taxon_extinct,
              chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
              chrono_local,chrono_color,
              chrono_path,chrono_parent_ref,
              litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
              litho_local,litho_color,
              litho_path,litho_parent_ref,
              lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
              lithology_local,lithology_color,
              lithology_path,lithology_parent_ref,
              mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
              mineral_local,mineral_color,
              mineral_path,mineral_parent_ref,
              host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
              host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
              host_specimen_ref,
              acquisition_category,acquisition_date_mask,acquisition_date,
              ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
              with_types, with_individuals,
              individual_ref,
              individual_type, individual_type_group, individual_type_search,
              individual_sex, individual_state, individual_stage,
              individual_social_status, individual_rock_form,
              individual_count_min, individual_count_max
            )=
            ( subq.spec_ref,subq.category,
              subq.collection_ref,subq.collection_type,subq.collection_code,subq.collection_name,subq. collection_is_public,
              subq.collection_institution_ref,subq.collection_institution_formated_name,
              subq.collection_institution_formated_name_ts,subq.collection_institution_formated_name_indexed,subq.collection_institution_sub_type,
              subq.collection_main_manager_ref,subq.collection_main_manager_formated_name,
              subq.collection_main_manager_formated_name_ts,subq.collection_main_manager_formated_name_indexed,
              subq.collection_parent_ref,subq.collection_path,
              subq.expedition_ref,subq.expedition_name,subq.expedition_name_ts,subq.expedition_name_indexed,
              subq.station_visible,subq.gtu_ref,subq.gtu_code,subq.gtu_parent_ref,subq.gtu_path,subq.gtu_location,
              subq.gtu_from_date_mask,subq.gtu_from_date,subq.gtu_to_date_mask,subq.gtu_to_date,
              subq.gtu_tag_values_indexed,subq.gtu_country_tag_value,
              subq.taxon_ref,subq.taxon_name,subq.taxon_name_indexed,subq.taxon_name_order_by,subq.taxon_level_ref,subq.taxon_level_name,subq.taxon_status,
              subq.taxon_path,subq.taxon_parent_ref,subq.taxon_extinct,
              subq.chrono_ref,subq.chrono_name,subq.chrono_name_indexed,subq.chrono_name_order_by,subq.chrono_level_ref,subq.chrono_level_name,subq.chrono_status,
              subq.chrono_local,subq.chrono_color,
              subq.chrono_path,subq.chrono_parent_ref,
              subq.litho_ref,subq.litho_name,subq.litho_name_indexed,subq.litho_name_order_by,subq.litho_level_ref,subq.litho_level_name,subq.litho_status,
              subq.litho_local,subq.litho_color,
              subq.litho_path,subq.litho_parent_ref,
              subq.lithology_ref,subq.lithology_name,subq.lithology_name_indexed,subq.lithology_name_order_by,subq.lithology_level_ref,subq.lithology_level_name,subq.lithology_status,
              subq.lithology_local,subq.lithology_color,
              subq.lithology_path,subq.lithology_parent_ref,
              subq.mineral_ref,subq.mineral_name,subq.mineral_name_indexed,subq.mineral_name_order_by,subq.mineral_level_ref,subq.mineral_level_name,subq.mineral_status,
              subq.mineral_local,subq.mineral_color,
              subq.mineral_path,subq.mineral_parent_ref,
              subq.host_taxon_ref,subq.host_relationship,subq.host_taxon_name,subq.host_taxon_name_indexed,subq.host_taxon_name_order_by,subq.host_taxon_level_ref,subq.host_taxon_level_name,subq.host_taxon_status,
              subq.host_taxon_path,subq.host_taxon_parent_ref,subq.host_taxon_extinct,
              subq.host_specimen_ref,
              subq.acquisition_category,subq.acquisition_date_mask,subq.acquisition_date,
              subq.ig_ref,subq.ig_num,subq.ig_num_indexed,subq.ig_date_mask,subq.ig_date,
              subq.with_types, subq.with_individuals,
              subq.individual_ref,
              subq.individual_type, subq.individual_type_group, subq.individual_type_search,
              subq.individual_sex, subq.individual_state, subq.individual_stage,
              subq.individual_social_status, subq.individual_rock_form,
              subq.individual_count_min, subq.individual_count_max
            )
        FROM
          ( SELECT
            spec_ref,category,
            collection_ref,collection_type,collection_code,collection_name,collection_is_public,
            collection_institution_ref,collection_institution_formated_name,
            collection_institution_formated_name_ts,collection_institution_formated_name_indexed,collection_institution_sub_type,
            collection_main_manager_ref,collection_main_manager_formated_name,
            collection_main_manager_formated_name_ts,collection_main_manager_formated_name_indexed,
            collection_parent_ref,collection_path,
            expedition_ref,expedition_name,expedition_name_ts,expedition_name_indexed,
            station_visible,gtu_ref,gtu_code,gtu_parent_ref,gtu_path,gtu_location,
            gtu_from_date_mask,gtu_from_date,gtu_to_date_mask,gtu_to_date,
            gtu_tag_values_indexed,gtu_country_tag_value,
            taxon_ref,taxon_name,taxon_name_indexed,taxon_name_order_by,taxon_level_ref,taxon_level_name,taxon_status,
            taxon_path,taxon_parent_ref,taxon_extinct,
            chrono_ref,chrono_name,chrono_name_indexed,chrono_name_order_by,chrono_level_ref,chrono_level_name,chrono_status,
            chrono_local,chrono_color,
            chrono_path,chrono_parent_ref,
            litho_ref,litho_name,litho_name_indexed,litho_name_order_by,litho_level_ref,litho_level_name,litho_status,
            litho_local,litho_color,
            litho_path,litho_parent_ref,
            lithology_ref,lithology_name,lithology_name_indexed,lithology_name_order_by,lithology_level_ref,lithology_level_name,lithology_status,
            lithology_local,lithology_color,
            lithology_path,lithology_parent_ref,
            mineral_ref,mineral_name,mineral_name_indexed,mineral_name_order_by,mineral_level_ref,mineral_level_name,mineral_status,
            mineral_local,mineral_color,
            mineral_path,mineral_parent_ref,
            host_taxon_ref,host_relationship,host_taxon_name,host_taxon_name_indexed,host_taxon_name_order_by,host_taxon_level_ref,host_taxon_level_name,host_taxon_status,
            host_taxon_path,host_taxon_parent_ref,host_taxon_extinct,
            host_specimen_ref,
            acquisition_category,acquisition_date_mask,acquisition_date,
            ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date,
            with_types, with_individuals,
            individual_ref,
            individual_type, individual_type_group, individual_type_search,
            individual_sex, individual_state, individual_stage,
            individual_social_status, individual_rock_form,
            individual_count_min, individual_count_max
            FROM darwin_flat
            WHERE individual_ref = NEW.specimen_individual_ref
            LIMIT 1
          ) subq
        WHERE part_ref = NEW.id;

        /*Check if the individual the part is moved to has already parts*/
        SELECT COUNT(*) INTO partCount FROM specimen_parts WHERE specimen_individual_ref = NEW.specimen_individual_ref AND id != NEW.id;
        IF partCount = 0 THEN
          /*If not, remove the line with null part*/
          DELETE FROM darwin_flat
          WHERE individual_ref = NEW.specimen_individual_ref AND part_ref IS NULL;
        END IF;

      END IF;
    END IF;
  END IF;
  IF TG_TABLE_NAME = 'specimens' THEN
    IF COALESCE(NEW.ig_ref,0) = 0 THEN
      UPDATE darwin_flat
      SET (ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date) =
          (NULL, NULL, NULL, NULL, NULL)
      WHERE spec_ref = NEW.id;
    ELSE
      UPDATE darwin_flat
      SET (ig_ref, ig_num, ig_num_indexed, ig_date_mask, ig_date) =
          (NEW.ig_ref, subq.ig_num, subq.ig_num_indexed, subq.ig_date_mask, subq.ig_date)
          FROM
          (SELECT ig_num, ig_num_indexed, ig_date_mask, ig_date
           FROM igs
           WHERE id = NEW.ig_ref
          ) subq
      WHERE spec_ref = NEW.id;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION fct_delete_darwin_flat_ind_part() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  indCount INTEGER := 0;
  partCount INTEGER := 0;
  indType BOOLEAN := false;
BEGIN
  IF TG_TABLE_NAME = 'specimen_individuals' THEN
    SELECT COUNT(*) INTO indCount FROM specimen_individuals WHERE specimen_ref = OLD.specimen_ref;
    /*If it was the last individual deleted, then update individuals and parts fiedls with default values*/
    IF indCount = 0 THEN
      SELECT COUNT(*) INTO indCount FROM darwin_flat WHERE spec_ref = OLD.specimen_ref AND individual_ref != OLD.id;
      IF indCount = 0 THEN
        SELECT COUNT(*) INTO indCount FROM darwin_flat WHERE spec_ref = OLD.specimen_ref AND individual_ref = OLD.id;
        IF indCount > 1 THEN
          DELETE FROM darwin_flat WHERE ctid = ANY (ARRAY(SELECT ctid FROM darwin_flat WHERE individual_ref = OLD.id LIMIT (indCount - 1)));
        END IF;
        UPDATE darwin_flat
        SET
        (individual_ref,
         individual_type, individual_type_group, individual_type_search,
         individual_sex, individual_state, individual_stage,
         individual_social_status, individual_rock_form,
         individual_count_min, individual_count_max,
         with_types, with_individuals
        )
        =
        (DEFAULT,
         DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT,
         DEFAULT, DEFAULT,
         DEFAULT, DEFAULT
        )
        WHERE individual_ref = OLD.id;
      ELSE
        DELETE FROM darwin_flat WHERE individual_ref = OLD.id;
      END IF;
    ELSE
      DELETE FROM darwin_flat
      WHERE individual_ref = OLD.id;

      /*Check for the with_types and with_individuals update*/
      SELECT true INTO indType
      FROM specimen_individuals
      WHERE specimen_ref = OLD.specimen_ref
        AND type_group <> 'specimen' LIMIT 1;

      IF NOT FOUND THEN
        indType := false;
      END IF;

      UPDATE darwin_flat
      SET with_types = indType
      WHERE spec_ref = OLD.specimen_ref;

    END IF;
  ELSE /*Parts*/
    SELECT COUNT(*) INTO partCount FROM specimen_parts WHERE specimen_individual_ref = OLD.specimen_individual_ref;
    IF partCount = 0 THEN

      SELECT COUNT(*) INTO partCount FROM darwin_flat WHERE individual_ref = OLD.specimen_individual_ref;

      IF partCount > 1 THEN
        DELETE FROM darwin_flat WHERE part_ref = OLD.id;
      ELSE
        /*Update darwin flat*/
        UPDATE darwin_flat
        SET
        (with_parts,
         part_ref, part, part_status,
         building, "floor", room, "row", shelf,
         container_type, container_storage, "container",
         sub_container_type, sub_container_storage, "sub_container",
         part_count_min, part_count_max,
         specimen_status,"complete",surnumerary
        )
        =
        (DEFAULT,
         DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT, DEFAULT,
         DEFAULT, DEFAULT,
         DEFAULT, DEFAULT, DEFAULT
        )
        WHERE part_ref = OLD.id;

      END IF;
      /*and also specimen_individuals table*/
      UPDATE specimen_individuals
      SET with_parts = DEFAULT
      WHERE id = OLD.specimen_individual_ref
        AND with_parts = true;

    ELSE

      DELETE FROM darwin_flat
      WHERE part_ref = OLD.id;

    END IF;
  END IF;
  RETURN OLD;
END;
$$;

CREATE OR REPLACE FUNCTION convert_to_integer(v_input varchar) RETURNS INTEGER
AS $$
DECLARE v_int_value INTEGER DEFAULT 0;
BEGIN
    BEGIN
        v_int_value := v_input::INTEGER;
    EXCEPTION WHEN OTHERS THEN
/*        RAISE NOTICE 'Invalid integer value: "%".  Returning NULL.', v_input;*/
        RETURN 0;
    END;
RETURN v_int_value;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_searchCodes(VARIADIC varchar[]) RETURNS SETOF integer AS $$
DECLARE
  sqlString varchar := E'select record_id from codes';
  sqlWhere varchar := '';
  code_part varchar;
  code_from varchar;
  code_to varchar;
  code_category varchar;
  relation varchar;
  word varchar;
BEGIN
  FOR i in 1..array_upper( $1, 1 ) BY 5 LOOP
    code_category := $1[i];
    code_part := $1[i+1];
    code_from := $1[i+2];
    code_to := $1[i+3];
    relation := $1[i+4] ;

    IF relation != '' THEN
      sqlString := sqlString || ' where referenced_relation=' || quote_literal(relation) ;
    ELSE
      sqlString := sqlString || E' where referenced_relation=\'specimens\''  ;
    END IF ;

    sqlWhere := sqlWhere || ' (code_category = ' || quote_literal(code_category) ;

    IF code_from ~ '^[0-9]+$' and code_to ~ '^[0-9]+$' THEN
      sqlWhere := sqlWhere || ' AND code_num BETWEEN ' || quote_literal(code_from) || ' AND ' || quote_literal(code_to) ;
    END IF;

    IF code_part != '' THEN
      sqlWhere := sqlWhere || ' AND (';
      FOR word IN (SELECT words FROM regexp_split_to_table(code_part, E'\\s+') as words) LOOP
        sqlWhere := sqlWhere || E' full_code_order_by like \'%\' || fullToIndex(' || quote_literal(word) || E') || \'%\' OR';
      END LOOP;
      sqlWhere := substr(sqlWhere,0,length(sqlWhere)-2) || ')';
    END IF;

    sqlWhere := sqlWhere || ') OR ';

  END LOOP;

  sqlString := sqlString || ' AND (' || substr(sqlWhere,0, length(sqlWhere)-2) || ')';
  RAISE INFO 'Sql : %',sqlString ;
  RETURN QUERY EXECUTE sqlString;
END;
$$ LANGUAGE plpgSQL;


CREATE OR REPLACE FUNCTION fct_search_tools (IN str_ids varchar) RETURNS SETOF integer
language SQL STABLE
AS
$$
    select distinct(specimen_ref) from specimen_collecting_tools where collecting_tool_ref in (select X::int from regexp_split_to_table($1,',') as X);
$$;

CREATE OR REPLACE FUNCTION fct_search_methods (IN str_ids varchar) RETURNS SETOF integer
language SQL STABLE
AS
$$
    select distinct(specimen_ref) from specimen_collecting_methods where collecting_method_ref in (select X::int from regexp_split_to_table($1,',') as X);
$$;

CREATE OR REPLACE FUNCTION dummy( in anyelement, inout anyelement )
AS $$
  select $2;
$$LANGUAGE 'sql' STABLE RETURNS NULL ON NULL INPUT;

CREATE AGGREGATE dummy_first (anyelement)
(
  sfunc = dummy,
  stype = anyelement
);



CREATE OR REPLACE FUNCTION fct_search_authorized_encoding_collections (user_id integer) RETURNS SETOF integer
language SQL STABLE
AS
$$
    select collection_ref from collections_rights where user_ref = $1 and db_user_type >= 2;
$$;

CREATE OR REPLACE FUNCTION fct_search_authorized_view_collections (user_id integer) RETURNS SETOF integer
language SQL STABLE
AS
$$
    select collection_ref from collections_rights where user_ref = $1

    UNION

    select id as collection_ref from collections where is_public = true;
$$;

/*
  Check when doing an action on specimen that the user is allowed to do it
*/
CREATE OR REPLACE FUNCTION fct_chk_specimenCollectionAllowed() RETURNS TRIGGER
language plpgSQL
AS
$$
DECLARE
  user_id integer;
  db_user_type_cpy smallint;
BEGIN
  SELECT COALESCE(get_setting('darwin.userid'),'0')::integer INTO user_id;
  /*If no user id allows modification -> if we do a modif in SQL it should be possible*/
  IF user_id = 0 THEN
    IF TG_OP = 'DELETE' THEN
      RETURN OLD;
    END IF;
    RETURN NEW;
  END IF;
  /*If user_id <> 0, get db_user_type of user concerned*/
  SELECT db_user_type INTO db_user_type_cpy FROM users WHERE id = user_id;
  /*If admin allows whatever*/
  IF db_user_type_cpy = 8 THEN
    IF TG_OP = 'DELETE' THEN
      RETURN OLD;
    END IF;
    RETURN NEW;
  END IF;

  IF TG_TABLE_NAME = 'specimens' THEN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
      PERFORM true WHERE NEW.collection_ref::integer IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to insert into or update a specimen in this collection';
      END IF;
    ELSE /*Delete*/
      PERFORM true WHERE OLD.collection_ref::integer IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to delete a specimen from this collection';
      END IF;
    END IF;
  ELSIF TG_TABLE_NAME = 'specimen_individuals' THEN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
      PERFORM true WHERE (SELECT collection_ref::integer FROM darwin_flat WHERE spec_ref = NEW.specimen_ref LIMIT 1) IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to insert into or update an individual in this collection';
      END IF;
    ELSE /*Delete*/
      PERFORM true WHERE (SELECT collection_ref::integer FROM darwin_flat WHERE spec_ref = OLD.specimen_ref LIMIT 1) IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to delete an individual from this collection';
      END IF;
    END IF;
  ELSIF TG_TABLE_NAME = 'specimen_parts' THEN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
      PERFORM true WHERE (SELECT collection_ref::integer FROM darwin_flat WHERE individual_ref = NEW.specimen_individual_ref LIMIT 1) IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to insert into or update a part in this collection';
      END IF;
    ELSE /*Delete*/
      PERFORM true WHERE (SELECT collection_ref::integer FROM darwin_flat WHERE individual_ref = OLD.specimen_individual_ref LIMIT 1) IN (SELECT * FROM fct_search_authorized_encoding_collections(user_id));
      IF NOT FOUND THEN
        RAISE EXCEPTION 'You don''t have the rights to delete a part from this collection';
      END IF;
    END IF;
  END IF;
  IF TG_OP = 'DELETE' THEN
    RETURN OLD;
  END IF;
  RETURN NEW;
END;
$$;

CREATE OR REPLACE FUNCTION fct_cpy_location() RETURNS trigger
as $$
BEGIN
  IF TG_OP = 'UPDATE' THEN
    IF NEW.longitude IS DISTINCT FROM OLD.longitude OR NEW.latitude IS DISTINCT FROM OLD.latitude OR NEW.lat_long_accuracy IS DISTINCT FROM OLD.lat_long_accuracy THEN
      NEW.location = --GeomFromText( 'POINT(' || NEW.longitude || ' ' || NEW.latitude || ')', 4326);
        ST_Transform(ST_Buffer(ST_Transform(GeomFromEWKT('SRID=4326;POINT(' || NEW.longitude || ' ' || NEW.latitude || ')'),3021),NEW.lat_long_accuracy, 16),4326);
    END IF;
  ELSE
    NEW.location =  ST_Transform(ST_Buffer(ST_Transform(GeomFromEWKT('SRID=4326;POINT(' || NEW.longitude || ' ' || NEW.latitude || ')'),3021),NEW.lat_long_accuracy, 16),4326);
  END IF;

  RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION fct_filter_encodable_row(ids varchar, col_name varchar, user_id integer) RETURNS SETOF integer
AS $$
DECLARE
  ref refcursor;
  rec RECORD;
BEGIN
        OPEN ref FOR EXECUTE 'SELECT distinct(' || quote_ident(col_name) || ') as result ' ||
          ' FROM darwin_flat ' ||
          'WHERE '|| quote_ident(col_name) || ' in (select X::int from regexp_split_to_table(' || quote_literal($1) || ', '','' ) as X) ' ||
          'AND collection_ref in (select X FROM fct_search_authorized_encoding_collections(' || user_id || ') as X)';

        LOOP
        FETCH ref INTO rec;
            IF  NOT FOUND THEN
                EXIT;  -- exit loop
            END IF;

        return next rec.result;

        END LOOP;

        CLOSE ref;

END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION fct_cpy_updateMyWidgetsColl() RETURNS TRIGGER
language plpgsql
AS
$$
DECLARE
  booContinue boolean := false;
BEGIN
  IF TG_TABLE_NAME = 'collections_rights' THEN
    IF TG_OP = 'DELETE' THEN
      booContinue := true;
    ELSE
      IF OLD.collection_ref != NEW.collection_ref OR OLD.user_ref != NEW.user_ref THEN
        booContinue := true;
      END IF;
    END IF;
    IF booContinue THEN
      /*!!! What's done is only removing the old collection reference from list of collections set in widgets !!!
        !!! We considered the add of widgets available for someone in a collection still be a manual action !!!
      */
      UPDATE my_widgets
      SET collections = regexp_replace(collections, E'\,' || OLD.collection_ref || E'\,', E'\,', 'g')
      WHERE user_ref = OLD.user_ref
        AND collections ~ (E'\,' || OLD.collection_ref || E'\,');
    END IF;
  END IF;
  IF TG_OP = 'DELETE' THEN
    RETURN OLD;
  END IF;
  RETURN NEW;
END;
$$;

/*Check that when specifying a parent collection the institution given is the same as the one used for parent*/
CREATE OR REPLACE FUNCTION fct_chk_parentCollInstitution() RETURNS TRIGGER
language plpgSQL
AS
$$
DECLARE
  institutionRef integer;
  booContinue boolean := false;
BEGIN
  IF TG_OP = 'INSERT' THEN
    booContinue := true;
  ELSIF TG_OP = 'UPDATE' THEN
    IF NEW.institution_ref != OLD.institution_ref OR NEW.parent_ref IS DISTINCT FROM OLD.parent_ref THEN
      booContinue := true;
    END IF;
  END IF;
  IF booContinue THEN
    IF NEW.parent_ref IS NOT NULL THEN
      SELECT institution_ref INTO institutionRef FROM collections WHERE id = NEW.parent_ref;
      IF institutionRef != NEW.institution_ref THEN
        RAISE EXCEPTION 'You tried to insert or update a collection with an other institution than the one given for the parent collection';
      END IF;
    END IF;
  END IF;
  RETURN NEW;
END;
$$;

/*When updating institution reference of a collection -> impact this modification on all children collections*/
CREATE OR REPLACE FUNCTION fct_cpy_updateCollInstitutionCascade() RETURNS TRIGGER
language plpgsql
AS
$$
BEGIN
  IF NEW.institution_ref != OLD.institution_ref THEN
    UPDATE collections
    SET institution_ref = NEW.institution_ref
    WHERE id != NEW.id
      AND parent_ref = NEW.id;
  END IF;
  RETURN NEW;
END;
$$;

/*When unpromoting a user we have to remove the non availble columns from preferences*/
CREATE OR REPLACE FUNCTION fct_unpromotion_impact_prefs() RETURNS TRIGGER
language plpgSQL
AS
$$
DECLARE
  saved_search_row RECORD;
BEGIN
  IF NEW.db_user_type IS DISTINCT FROM OLD.db_user_type AND NEW.db_user_type = 1 THEN
    UPDATE preferences
    SET pref_value = subq.fields_available
    FROM (select array_to_string(array(select fields_list 
                                       from regexp_split_to_table((SELECT pref_value 
                                                                   FROM preferences 
                                                                   WHERE user_ref = NEW.id 
                                                                     AND pref_key = 'search_cols_part' 
                                                                   LIMIT 1
                                                                  ), E'\\|') as fields_list
                                       where fields_list not in ('building', 'floor', 'room', 'row', 'shelf', 'container', 'container_type', 'container_storage', 'sub_container', 'sub_container_type', 'sub_container_storage')
                                      ),'|'
                                ) as fields_available
         ) subq
    WHERE user_ref = NEW.id
      AND pref_key = 'search_cols_part';
    FOR saved_search_row IN SELECT id, visible_fields_in_result FROM my_saved_searches WHERE user_ref = NEW.id AND subject = 'part' LOOP
      UPDATE my_saved_searches
      SET visible_fields_in_result = subq.fields_available
      FROM (select array_to_string(array(select fields_list
                                         from regexp_split_to_table(saved_search_row.visible_fields_in_result, E'\\|') as fields_list 
                                         where fields_list not in ('building', 'floor', 'room', 'row', 'shelf', 'container', 'container_type', 'container_storage', 'sub_container', 'sub_container_type', 'sub_container_storage')
                                        ),'|'
                                  ) as fields_available
          ) subq
      WHERE id = saved_search_row.id;
    END LOOP;
  END IF;
  RETURN NEW;
END;
$$;
