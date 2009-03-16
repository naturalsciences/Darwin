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
