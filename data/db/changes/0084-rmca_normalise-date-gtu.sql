----- these 4 functions modified to redirect the date from ABCD
-----from GTUs to Specimens 
CREATE OR REPLACE FUNCTION fct_update_specimens_flat_related()
  RETURNS trigger AS
$BODY$
DECLARE
  indCount INTEGER := 0;
  indType BOOLEAN := false;
  tmp_user text;
BEGIN
 SELECT COALESCE(get_setting('darwin.userid'),'0') INTO tmp_user;
  PERFORM set_config('darwin.userid', '-1', false) ;

  IF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'expeditions' THEN
    IF NEW.name_indexed IS DISTINCT FROM OLD.name_indexed THEN
      UPDATE specimens
      SET (expedition_name, expedition_name_indexed) =
          (NEW.name, NEW.name_indexed)
      WHERE expedition_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'collections' THEN
    IF OLD.collection_type IS DISTINCT FROM NEW.collection_type
    OR OLD.code IS DISTINCT FROM NEW.code
    OR OLD.name IS DISTINCT FROM NEW.name
    OR OLD.is_public IS DISTINCT FROM NEW.is_public
    OR OLD.path IS DISTINCT FROM NEW.path
    THEN
      UPDATE specimens
      SET (collection_type, collection_code, collection_name, collection_is_public,
          collection_parent_ref, collection_path
          ) =
          (NEW.collection_type, NEW.code, NEW.name, NEW.is_public,
           NEW.parent_ref, NEW.path
          )
      WHERE collection_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'gtu' THEN
   /* UPDATE specimens
    SET (gtu_code, gtu_from_date, gtu_from_date_mask,
         gtu_to_date, gtu_to_date_mask,
         gtu_elevation, gtu_elevation_accuracy,
         gtu_tag_values_indexed, gtu_location
        ) =
        (NEW.code, NEW.gtu_from_date, NEW.gtu_from_date_mask,
         NEW.gtu_to_date, NEW.gtu_to_date_mask,
         NEW.elevation, NEW.elevation_accuracy,
         NEW.tag_values_indexed, NEW.location
        )
    WHERE gtu_ref = NEW.id;*/
    --ftheeten 206 017 07
    UPDATE specimens
    SET (gtu_code,
         gtu_elevation, gtu_elevation_accuracy,
         gtu_tag_values_indexed, gtu_location
        ) =
        (NEW.code, 
         NEW.elevation, NEW.elevation_accuracy,
         NEW.tag_values_indexed, NEW.location
        )
    WHERE gtu_ref = NEW.id;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'igs' THEN
    IF NEW.ig_num_indexed IS DISTINCT FROM OLD.ig_num_indexed OR NEW.ig_date IS DISTINCT FROM OLD.ig_date THEN
      UPDATE specimens
      SET (ig_num, ig_num_indexed, ig_date, ig_date_mask) =
          (NEW.ig_num, NEW.ig_num_indexed, NEW.ig_date, NEW.ig_date_mask)
      WHERE ig_ref = NEW.id;
    END IF;
  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'taxonomy' THEN
    UPDATE specimens
    SET (taxon_name, taxon_name_indexed,
         taxon_level_ref, taxon_level_name,
         taxon_status, taxon_path, taxon_parent_ref, taxon_extinct
        ) =
        (NEW.name, NEW.name_indexed,
         NEW.level_ref, subq.level_name,
         NEW.status, NEW.path, NEW.parent_ref, NEW.extinct
        )
        FROM
        (SELECT level_name
         FROM catalogue_levels
         WHERE id = NEW.level_ref
        ) subq
    WHERE taxon_ref = NEW.id;

  ELSIF TG_OP = 'UPDATE' AND TG_TABLE_NAME = 'chronostratigraphy' THEN
    UPDATE specimens
    SET (chrono_name, chrono_name_indexed,
         chrono_level_ref, chrono_level_name,
         chrono_status,
         chrono_local, chrono_color,
         chrono_path, chrono_parent_ref
        ) =
        (NEW.name, NEW.name_indexed,
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
    UPDATE specimens
    SET (litho_name, litho_name_indexed,
         litho_level_ref, litho_level_name,
         litho_status,
         litho_local, litho_color,
         litho_path, litho_parent_ref
        ) =
        (NEW.name, NEW.name_indexed,
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
    UPDATE specimens
    SET (lithology_name, lithology_name_indexed,
         lithology_level_ref, lithology_level_name,
         lithology_status,
         lithology_local, lithology_color,
         lithology_path, lithology_parent_ref
        ) =
        (NEW.name, NEW.name_indexed,
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
    UPDATE specimens
    SET (mineral_name, mineral_name_indexed,
         mineral_level_ref, mineral_level_name,
         mineral_status,
         mineral_local, mineral_color,
         mineral_path, mineral_parent_ref
        ) =
        (NEW.name, NEW.name_indexed,
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

  ELSIF TG_TABLE_NAME = 'tag_groups' THEN
    IF TG_OP = 'INSERT' THEN
      IF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'country' THEN
        UPDATE specimens
        SET gtu_country_tag_value = case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value,
            gtu_country_tag_indexed = lineToTagArray(case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value)
        WHERE gtu_ref = NEW.gtu_ref;
      ELSIF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'province' THEN
        UPDATE specimens
        SET gtu_province_tag_value = case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value,
            gtu_province_tag_indexed = lineToTagArray(case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value)
        WHERE gtu_ref = NEW.gtu_ref;
      ELSIF NEW.sub_group_name_indexed NOT IN ('country','province') THEN
      /*Trigger trg_cpy_gtutags_taggroups has already occured and values from tags table should be correct... but really need a check !*/
        UPDATE specimens
        SET gtu_others_tag_value = (select array_to_string(array(select tag from tags where gtu_ref = NEW.gtu_ref and sub_group_type not in ('country', 'province')), ';')),
            gtu_others_tag_indexed = (select array(select distinct fullToIndex(tag) from tags where gtu_ref = NEW.gtu_ref and sub_group_type not in ('country', 'province')))
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
    ELSIF TG_OP = 'UPDATE' THEN
      IF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'country' AND NEW.sub_group_name_indexed != 'country' THEN
        UPDATE specimens
        SET gtu_country_tag_value = NULL,
            gtu_country_tag_indexed = NULL
        WHERE gtu_ref = NEW.gtu_ref;
      ELSIF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'province' AND NEW.sub_group_name_indexed != 'province' THEN
        UPDATE specimens
        SET gtu_province_tag_value = NULL,
            gtu_province_tag_indexed = NULL
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
      IF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'country' THEN
        UPDATE specimens
        SET gtu_country_tag_value = case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value,
            gtu_country_tag_indexed = lineToTagArray(case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END ||NEW.tag_value)
        WHERE gtu_ref = NEW.gtu_ref;
      ELSIF NEW.group_name_indexed = 'administrativearea' AND NEW.sub_group_name_indexed = 'province' THEN
        UPDATE specimens
        SET gtu_province_tag_value = case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value,
            gtu_province_tag_indexed = lineToTagArray(case when NEW.international_name != '' THEN NEW.international_name || ';' ELSE '' END || NEW.tag_value)
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
      IF NEW.sub_group_name_indexed NOT IN ('country','province') THEN
      /*Trigger trg_cpy_gtutags_taggroups has already occured and values from tags table should be correct... but really need a check !*/
        UPDATE specimens
        SET gtu_others_tag_value = (select array_to_string(array(select tag from tags where gtu_ref = NEW.gtu_ref and sub_group_type not in ('country', 'province')), ';')),
            gtu_others_tag_indexed = (select array(select distinct fullToIndex(tag) from tags where gtu_ref = NEW.gtu_ref and sub_group_type not in ('country', 'province')))
        WHERE gtu_ref = NEW.gtu_ref;
      END IF;
    ELSIF TG_OP = 'DELETE' THEN
      IF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'country' THEN
        UPDATE specimens
        SET gtu_country_tag_value = NULL,
            gtu_country_tag_indexed = NULL
        WHERE gtu_ref = OLD.gtu_ref;
      ELSIF OLD.group_name_indexed = 'administrativearea' AND OLD.sub_group_name_indexed = 'province' THEN
        UPDATE specimens
        SET gtu_province_tag_value = NULL,
            gtu_province_tag_indexed = NULL
        WHERE gtu_ref = OLD.gtu_ref;
      ELSE
        /*Trigger trg_cpy_gtutags_taggroups has already occured and values from tags table should be correct... but really need a check !*/
        UPDATE specimens
        SET gtu_others_tag_value = (select array_to_string(array(select tag from tags where gtu_ref = OLD.gtu_ref and sub_group_type not in ('country', 'province')), ';')),
            gtu_others_tag_indexed = (select array(select distinct fullToIndex(tag) from tags where gtu_ref = OLD.gtu_ref and sub_group_type not in ('country', 'province')))
        WHERE gtu_ref = OLD.gtu_ref;
      END IF;
    END IF;
  END IF;
  PERFORM set_config('darwin.userid', tmp_user, false) ;
  RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fct_update_specimens_flat_related()
  OWNER TO darwin2;
  
  
  ------------------------------------------
  
  CREATE OR REPLACE FUNCTION fct_update_specimen_flat()
  RETURNS trigger AS
$BODY$
DECLARE
  cnt integer;
  old_val specimens%ROWTYPE;
  new_val specimens%ROWTYPE;
BEGIN

    IF TG_OP = 'UPDATE' THEN
      old_val = OLD;
      new_val = NEW;
    ELSE --INSERT
      new_val = NEW;
    END IF;

    IF old_val.taxon_ref IS DISTINCT FROM new_val.taxon_ref THEN
      SELECT  name, name_indexed, level_ref, level_name, status, path, parent_ref, extinct
        INTO NEW.taxon_name, NEW.taxon_name_indexed, NEW.taxon_level_ref, NEW.taxon_level_name, NEW.taxon_status,
          NEW.taxon_path, NEW.taxon_parent_ref, NEW.taxon_extinct
        FROM taxonomy c
        INNER JOIN catalogue_levels l on c.level_ref = l.id
        WHERE c.id = new_val.taxon_ref;
    END IF;

    IF old_val.chrono_ref IS DISTINCT FROM new_val.chrono_ref THEN
      SELECT  name, name_indexed, level_ref, level_name, status, local_naming, color, path, parent_ref
      INTO NEW.chrono_name, NEW.chrono_name_indexed, NEW.chrono_level_ref, NEW.chrono_level_name, NEW.chrono_status,
          NEW.chrono_local, NEW.chrono_color, NEW.chrono_path, NEW.chrono_parent_ref
        FROM chronostratigraphy c
        INNER JOIN catalogue_levels l on c.level_ref = l.id
        WHERE c.id = new_val.chrono_ref;
    END IF;

    IF old_val.litho_ref IS DISTINCT FROM new_val.litho_ref THEN
      SELECT  name, name_indexed, level_ref, level_name, status, local_naming, color, path, parent_ref
        INTO NEW.litho_name, NEW.litho_name_indexed, NEW.litho_level_ref, NEW.litho_level_name, NEW.litho_status,
          NEW.litho_local, NEW.litho_color, NEW.litho_path, NEW.litho_parent_ref
        FROM lithostratigraphy c
        INNER JOIN catalogue_levels l on c.level_ref = l.id
        WHERE c.id = new_val.litho_ref;
    END IF;

    IF old_val.lithology_ref IS DISTINCT FROM new_val.lithology_ref THEN
      SELECT  name, name_indexed, level_ref, level_name, status, local_naming, color, path, parent_ref
        INTO NEW.lithology_name, NEW.lithology_name_indexed, NEW.lithology_level_ref, NEW.lithology_level_name, NEW.lithology_status,
          NEW.lithology_local, NEW.lithology_color, NEW.lithology_path, NEW.lithology_parent_ref
        FROM lithology c
        INNER JOIN catalogue_levels l on c.level_ref = l.id
        WHERE c.id = new_val.lithology_ref;
    END IF;

    IF old_val.mineral_ref IS DISTINCT FROM new_val.mineral_ref THEN
      SELECT  name, name_indexed, level_ref, level_name, status, local_naming, color, path, parent_ref
        INTO NEW.mineral_name, NEW.mineral_name_indexed, NEW.mineral_level_ref, NEW.mineral_level_name, NEW.mineral_status,
          NEW.mineral_local, NEW.mineral_color, NEW.mineral_path, NEW.mineral_parent_ref
        FROM mineralogy c
        INNER JOIN catalogue_levels l on c.level_ref = l.id
        WHERE c.id = new_val.mineral_ref;
    END IF;


    IF old_val.expedition_ref IS DISTINCT FROM new_val.expedition_ref THEN
      SELECT  name, name_indexed
        INTO NEW.expedition_name, NEW.expedition_name_indexed
        FROM expeditions c
        WHERE c.id = new_val.expedition_ref;
    END IF;

    IF old_val.collection_ref IS DISTINCT FROM new_val.collection_ref THEN
      SELECT collection_type, code, name, is_public, parent_ref, path
        INTO NEW.collection_type, NEW.collection_code, NEW.collection_name, NEW.collection_is_public,
          NEW.collection_parent_ref, NEW.collection_path
        FROM collections c
        WHERE c.id = new_val.collection_ref;
    END IF;

    IF old_val.ig_ref IS DISTINCT FROM new_val.ig_ref THEN
      SELECT  ig_num, ig_num_indexed, ig_date, ig_date_mask
        INTO NEW.ig_num, NEW.ig_num_indexed, NEW.ig_date, NEW.ig_date_mask
        FROM igs c
        WHERE c.id = new_val.ig_ref;
    END IF;

    IF old_val.gtu_ref IS DISTINCT FROM new_val.gtu_ref THEN
      /*SELECT  code, gtu_from_date, gtu_from_date_mask,
         gtu_to_date, gtu_to_date_mask,
         elevation, elevation_accuracy,
         tag_values_indexed, location,

         taggr_countries.tag_value, lineToTagArray(taggr_countries.tag_value),
         taggr_provinces.tag_value, lineToTagArray(taggr_provinces.tag_value),
         (select array_to_string(array(select tag from tags where gtu_ref = c.id and sub_group_type not in ('country', 'province')), ';')) as other_gtu_values,
         (select array(select distinct fullToIndex(tag) from tags where gtu_ref = c.id and sub_group_type not in ('country', 'province'))) as other_gtu_values_array

        INTO NEW.gtu_code, NEW.gtu_from_date, NEW.gtu_from_date_mask, NEW.gtu_to_date, NEW.gtu_to_date_mask,
         NEW.gtu_elevation, NEW.gtu_elevation_accuracy, NEW.gtu_tag_values_indexed, NEW.gtu_location,
         NEW.gtu_country_tag_value, NEW.gtu_country_tag_indexed, NEW.gtu_province_tag_value,
         NEW.gtu_province_tag_indexed, NEW.gtu_others_tag_value, NEW.gtu_others_tag_indexed
        FROM gtu c
          LEFT JOIN tag_groups taggr_countries ON c.id = taggr_countries.gtu_ref AND taggr_countries.group_name_indexed = 'administrativearea' AND taggr_countries.sub_group_name_indexed = 'country'
          LEFT JOIN tag_groups taggr_provinces ON c.id = taggr_provinces.gtu_ref AND taggr_provinces.group_name_indexed = 'administrativearea' AND taggr_provinces.sub_group_name_indexed = 'province'
        WHERE c.id = new_val.gtu_ref;*/
        --ftheeten 2016 07 07
        SELECT  code,
         elevation, elevation_accuracy,
         tag_values_indexed, location,

         taggr_countries.tag_value, lineToTagArray(taggr_countries.tag_value),
         taggr_provinces.tag_value, lineToTagArray(taggr_provinces.tag_value),
         (select array_to_string(array(select tag from tags where gtu_ref = c.id and sub_group_type not in ('country', 'province')), ';')) as other_gtu_values,
         (select array(select distinct fullToIndex(tag) from tags where gtu_ref = c.id and sub_group_type not in ('country', 'province'))) as other_gtu_values_array

        INTO NEW.gtu_code,
         NEW.gtu_elevation, NEW.gtu_elevation_accuracy, NEW.gtu_tag_values_indexed, NEW.gtu_location,
         NEW.gtu_country_tag_value, NEW.gtu_country_tag_indexed, NEW.gtu_province_tag_value,
         NEW.gtu_province_tag_indexed, NEW.gtu_others_tag_value, NEW.gtu_others_tag_indexed
        FROM gtu c
          LEFT JOIN tag_groups taggr_countries ON c.id = taggr_countries.gtu_ref AND taggr_countries.group_name_indexed = 'administrativearea' AND taggr_countries.sub_group_name_indexed = 'country'
          LEFT JOIN tag_groups taggr_provinces ON c.id = taggr_provinces.gtu_ref AND taggr_provinces.group_name_indexed = 'administrativearea' AND taggr_provinces.sub_group_name_indexed = 'province'
        WHERE c.id = new_val.gtu_ref;
    END IF;

  RETURN NEW;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fct_update_specimen_flat()
  OWNER TO darwin2;
--------------------------
CREATE OR REPLACE FUNCTION fct_importer_abcd(req_import_ref integer)
  RETURNS boolean AS
$BODY$
DECLARE
  userid integer;
  rec_id integer;
  people_id integer;
  all_line RECORD ;
  line staging;
  people_line RECORD;
  maintenance_line collection_maintenance;
  staging_line staging;
  id_to_delete integer ARRAY;
  id_to_keep integer ARRAY ;
  collection collections%ROWTYPE;
  code_count integer;
BEGIN
  SELECT * INTO collection FROM collections WHERE id = (SELECT collection_ref FROM imports WHERE id = req_import_ref AND is_finished = FALSE LIMIT 1);
  select user_ref into userid from imports where id=req_import_ref ;
  PERFORM set_config('darwin.userid',userid::varchar, false) ;
  INSERT INTO classification_keywords (referenced_relation, record_id, keyword_type, keyword)
          (
            SELECT DISTINCT ON (referenced_relation, taxon_ref, keyword_type, keyword_indexed)
                  'taxonomy',
                  taxon_ref,
                  keyword_type,
                  "keyword"
            FROM staging INNER JOIN classification_keywords as ckmain ON ckmain.referenced_relation = 'staging'
                                                                     AND staging.id = ckmain.record_id
                         INNER JOIN imports as i ON i.id = staging.import_ref
            WHERE import_ref = req_import_ref
              AND to_import=true
              AND status = ''::hstore
              AND i.is_finished =  FALSE
              AND NOT EXISTS (
                              SELECT 1
                              FROM classification_keywords
                              WHERE referenced_relation = 'taxonomy'
                                AND record_id = staging.taxon_ref
                                AND keyword_type = ckmain.keyword_type
                                AND keyword_indexed = ckmain.keyword_indexed
              )
          );
  EXECUTE 'DELETE FROM classification_keywords
           WHERE referenced_relation = ''staging''
             AND record_id IN (
                                SELECT s.id
                                FROM staging s INNER JOIN imports i ON  s.import_ref = i.id
                                WHERE import_ref = $1
                                  AND to_import=true
                                  AND status = ''''::hstore
                                  AND i.is_finished =  FALSE
                             )'
  USING req_import_ref;
  FOR all_line IN SELECT * from staging s INNER JOIN imports i on  s.import_ref = i.id
      WHERE import_ref = req_import_ref AND to_import=true and status = ''::hstore AND i.is_finished =  FALSE
  LOOP
    BEGIN
      -- I know it's dumb but....
      -- @ToDo: We need to correct this to avoid reselecting from the staging table !!!
      select * into staging_line from staging where id = all_line.id;
      PERFORM fct_imp_checker_igs(staging_line, true);
      PERFORM fct_imp_checker_expeditions(staging_line, true);
      PERFORM fct_imp_checker_gtu(staging_line, true);

      --RE SELECT WITH UPDATE
      select * into line from staging s INNER JOIN imports i on  s.import_ref = i.id where s.id=all_line.id;

    rec_id := nextval('specimens_id_seq');
    IF line.spec_ref IS NULL THEN
      INSERT INTO specimens (id, category, collection_ref, expedition_ref, gtu_ref, taxon_ref, litho_ref, chrono_ref, lithology_ref, mineral_ref,
          acquisition_category, acquisition_date_mask, acquisition_date, station_visible, ig_ref, type, sex, stage, state, social_status, rock_form,
          specimen_part, complete, institution_ref, building, floor, room, row, col, shelf, container, sub_container,container_type, sub_container_type,
          container_storage, sub_container_storage, surnumerary, specimen_status, specimen_count_min, specimen_count_max, object_name
		,
		--ftheeten 2016 07 11
        gtu_from_date_mask,
        gtu_from_date,
        gtu_to_date_mask,
        gtu_to_date
          )
      VALUES (rec_id, COALESCE(line.category,'physical') , all_line.collection_ref, line.expedition_ref, line.gtu_ref, line.taxon_ref, line.litho_ref, line.chrono_ref,
        line.lithology_ref, line.mineral_ref, COALESCE(line.acquisition_category,''), COALESCE(line.acquisition_date_mask,0), COALESCE(line.acquisition_date,'01/01/0001'),
        COALESCE(line.station_visible,true),  line.ig_ref, COALESCE(line.individual_type,'specimen'), COALESCE(line.individual_sex,'undefined'),
        COALESCE(line.individual_stage,'undefined'), COALESCE(line.individual_state,'not applicable'),COALESCE(line.individual_social_status,'not applicable'),
        COALESCE(line.individual_rock_form,'not applicable'), COALESCE(line.part,'specimen'), COALESCE(line.complete,true), line.institution_ref, line.building,
        line.floor, line.room, line.row,  line.col, line.shelf, line.container, line.sub_container,COALESCE(line.container_type,'container'),
        COALESCE(line.sub_container_type, 'container'), COALESCE(line.container_storage,''),COALESCE(line.sub_container_storage,''),
        COALESCE(line.surnumerary,false), COALESCE(line.specimen_status,''),COALESCE(line.part_count_min,1), COALESCE(line.part_count_max,line.part_count_min,1), line.object_name,
         --ftheeten 2016 07 11
             COALESCE(line.gtu_from_date_mask,0), COALESCE(line.gtu_from_date, '01/01/0001'),
          COALESCE(line.gtu_to_date_mask,0), COALESCE(line.gtu_to_date, '31/12/2038')
      );
      FOR maintenance_line IN SELECT * from collection_maintenance where referenced_relation = 'staging' AND record_id=line.id
      LOOP
        SELECT people_ref into people_id FROM staging_people where referenced_relation='collection_maintenance' AND record_id=maintenance_line.id ;
        UPDATE collection_maintenance set people_ref=people_id where id=maintenance_line.id ;
        DELETE FROM staging_people where referenced_relation='collection_maintenance' AND record_id=maintenance_line.id ;
      END LOOP;

      SELECT COUNT(*) INTO code_count FROM codes WHERE referenced_relation = 'staging' AND record_id = line.id AND code_category = 'main' AND code IS NOT NULL;
      IF code_count = 0 THEN
        PERFORM fct_after_save_add_code(all_line.collection_ref, rec_id);
      ELSE
        UPDATE codes SET referenced_relation = 'specimens', 
                         record_id = rec_id, 
                         code_prefix = CASE WHEN code_prefix IS NULL THEN collection.code_prefix ELSE code_prefix END,
                         code_prefix_separator = CASE WHEN code_prefix_separator IS NULL THEN collection.code_prefix_separator ELSE code_prefix_separator END,
                         code_suffix = CASE WHEN code_suffix IS NULL THEN collection.code_suffix ELSE code_suffix END,
                         code_suffix_separator = CASE WHEN code_suffix_separator IS NULL THEN collection.code_suffix_separator ELSE code_suffix_separator END
        WHERE referenced_relation = 'staging'
          AND record_id = line.id
          AND code_category = 'main';
      END IF;

      UPDATE template_table_record_ref SET referenced_relation ='specimens', record_id = rec_id where referenced_relation ='staging' and record_id = line.id;
      --UPDATE collection_maintenance SET referenced_relation ='specimens', record_id = rec_id where referenced_relation ='staging' and record_id = line.id;
      -- Import identifiers whitch identification have been updated to specimen
      INSERT INTO catalogue_people(id, referenced_relation, record_id, people_type, people_sub_type, order_by, people_ref)
        SELECT nextval('catalogue_people_id_seq'), s.referenced_relation, s.record_id, s.people_type, s.people_sub_type, s.order_by, s.people_ref 
        FROM staging_people s, identifications i 
        WHERE i.id = s.record_id AND s.referenced_relation = 'identifications' AND i.record_id = rec_id AND i.referenced_relation = 'specimens' ;
      DELETE FROM staging_people where id in (SELECT s.id FROM staging_people s, identifications i WHERE i.id = s.record_id AND s.referenced_relation = 'identifications' AND i.record_id = rec_id AND i.referenced_relation = 'specimens' ) ;
      -- Import collecting_methods
      INSERT INTO specimen_collecting_methods(id, specimen_ref, collecting_method_ref)
        SELECT nextval('specimen_collecting_methods_id_seq'), rec_id, collecting_method_ref 
        FROM staging_collecting_methods 
        WHERE staging_ref = line.id;
      
      DELETE FROM staging_collecting_methods where staging_ref = line.id;
      UPDATE staging set spec_ref=rec_id WHERE id=all_line.id;

      FOR people_line IN SELECT * from staging_people WHERE referenced_relation = 'specimens'
      LOOP
        INSERT INTO catalogue_people(id, referenced_relation, record_id, people_type, people_sub_type, order_by, people_ref)
        VALUES(nextval('catalogue_people_id_seq'),people_line.referenced_relation, people_line.record_id, people_line.people_type, people_line.people_sub_type, people_line.order_by, people_line.people_ref) ;
      END LOOP;
      DELETE FROM staging_people WHERE referenced_relation = 'specimens' ;
    END IF ;
    id_to_delete = array_append(id_to_delete,all_line.id) ;
    END;
  END LOOP;
  select fct_imp_checker_staging_relationship() into id_to_keep ;
  IF id_to_keep IS NOT NULL THEN
    DELETE from staging where (id = ANY (id_to_delete)) AND NOT (id = ANY (id_to_keep)) ;
  else
    DELETE from staging where (id = ANY (id_to_delete)) ;
  END IF ;
  IF EXISTS( select id FROM  staging WHERE import_ref = req_import_ref) THEN
    UPDATE imports set state = 'pending' where id = req_import_ref;
  ELSE
    UPDATE imports set state = 'finished', is_finished = true where id = req_import_ref;
  END IF;
  RETURN true;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fct_importer_abcd(integer)
  OWNER TO darwin2;
  

 ---------------------


CREATE OR REPLACE FUNCTION fct_imp_checker_gtu(line staging, import boolean DEFAULT false)
  RETURNS boolean AS
$BODY$
DECLARE
  ref_rec integer :=0;
  tags staging_tag_groups ;
BEGIN
  IF line.gtu_ref is not null THEN
    RETURN true;
  END IF;
  IF (line.gtu_code is null OR line.gtu_code  = '') AND (line.gtu_from_date is null OR line.gtu_code  = '') AND NOT EXISTS (select 1 from staging_tag_groups g where g.staging_ref = line.id ) THEN
    RETURN true;
  END IF;

    select id into ref_rec from gtu g where
      COALESCE(latitude,0) = COALESCE(line.gtu_latitude,0) AND
      COALESCE(longitude,0) = COALESCE(line.gtu_longitude,0) AND
      --ftheeten 2016 07 11
      --gtu_from_date = COALESCE(line.gtu_from_date, '01/01/0001') AND
      --gtu_to_date = COALESCE(line.gtu_to_date, '31/12/2038') AND
      fullToIndex(code) = fullToIndex(line.gtu_code)

      AND id != 0 LIMIT 1;



  IF NOT FOUND THEN
      IF import THEN
        INSERT into gtu
          (code, latitude, longitude, lat_long_accuracy, elevation, elevation_accuracy)
        VALUES
          (COALESCE(line.gtu_code,'import/'|| line.import_ref || '/' || line.id ), 
          --ftheeten 2016 07 1
          --COALESCE(line.gtu_from_date_mask,0), COALESCE(line.gtu_from_date, '01/01/0001'),
          --COALESCE(line.gtu_to_date_mask,0), COALESCE(line.gtu_to_date, '31/12/2038'), 
          line.gtu_latitude, line.gtu_longitude, line.gtu_lat_long_accuracy, line.gtu_elevation, line.gtu_elevation_accuracy)
        RETURNING id INTO line.gtu_ref;
        ref_rec := line.gtu_ref;
        FOR tags IN SELECT * FROM staging_tag_groups WHERE staging_ref = line.id LOOP
        BEGIN
          INSERT INTO tag_groups (gtu_ref, group_name, sub_group_name, tag_value)
            Values(ref_rec,tags.group_name, tags.sub_group_name, tags.tag_value );
        --  DELETE FROM staging_tag_groups WHERE staging_ref = line.id;
          EXCEPTION WHEN unique_violation THEN
            RAISE EXCEPTION 'An error occured: %', SQLERRM;
        END ;
        END LOOP ;
        PERFORM fct_imp_checker_staging_info(line, 'gtu');
      ELSE
        RETURN TRUE;
      END IF;
  END IF;

  UPDATE staging SET status = delete(status,'gtu'), gtu_ref = ref_rec where id=line.id;

  RETURN true;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION fct_imp_checker_gtu(staging, boolean)
  OWNER TO darwin2;
   
  
-----------------widget creation

  INSERT INTO my_widgets (user_ref, category, group_name, order_by, col_num, is_available, title_perso, all_public)
SELECT id, 'specimen_widget', 'gtuDate', 0,2, true, 'Gathering date', true FROM users where id NOT in (SELECT user_ref FROM my_widgets WHERE category='specimen_widget'  AND group_name ='Gathering date');


INSERT INTO my_widgets (user_ref, category, group_name, order_by, col_num, is_available, title_perso, all_public)
SELECT id, 'specimensearch_widget', 'gtuDate', 0,2, true, 'Gathering date', true FROM users where id NOT in (SELECT user_ref FROM my_widgets WHERE category='specimensearch_widget'  AND group_name ='Gathering date');


-----DB alignement of default values in table specimen_widget
UPDATE specimens SET gtu_from_date_mask=0 where gtu_from_date_mask is null;

ALTER TABLE specimens ALTER COLUMN gtu_from_date_mask SET NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_from_date_mask SET DEFAULT 0;
COMMENT ON COLUMN specimens.gtu_from_date_mask IS 'Mask Flag to know wich part of the date is effectively known: 32 for year, 16 for month and 8 for day, 4 for hour, 2 for minutes, 1 for seconds';

UPDATE specimens SET gtu_from_date='0001-01-01 00:00:00'::timestamp without time zone where gtu_from_date is null;
ALTER TABLE specimens ALTER COLUMN gtu_from_date SET NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_from_date SET DEFAULT '0001-01-01 00:00:00'::timestamp without time zone;
COMMENT ON COLUMN specimens.gtu_from_date IS 'composed from date of the GTU';

UPDATE specimens SET gtu_to_date_mask=0 where gtu_to_date_mask is null;
ALTER TABLE specimens ALTER COLUMN gtu_to_date_mask SET NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_to_date_mask SET DEFAULT 0;
COMMENT ON COLUMN specimens.gtu_to_date_mask IS 'Mask Flag to know wich part of the date is effectively known: 32 for year, 16 for month and 8 for day, 4 for hour, 2 for minutes, 1 for seconds';

UPDATE specimens SET gtu_to_date='2038-12-31 00:00:00'::timestamp without time zone where gtu_to_date is null;
ALTER TABLE specimens ALTER COLUMN gtu_to_date SET NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_to_date SET DEFAULT '2038-12-31 00:00:00'::timestamp without time zone;
COMMENT ON COLUMN specimens.gtu_to_date IS 'composed to date of the GTU';


ALTER TABLE specimens ALTER COLUMN gtu_to_date SET NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_to_date SET DEFAULT '2038-12-31 00:00:00'::timestamp without time zone;
COMMENT ON COLUMN specimens.gtu_to_date IS 'composed to date of the GTU';

----------------

--------------------re-normalize foreign key of specimens without the dates

---need to add a function sorting an array function first
---(to take the lower id of the foreign key when duplicates
CREATE OR REPLACE FUNCTION array_sort (ANYARRAY)
RETURNS ANYARRAY LANGUAGE SQL
AS $$
SELECT ARRAY(SELECT unnest($1) ORDER BY 1)
$$;


ALTER TABLE gtu ALTER COLUMN gtu_from_date_mask DROP NOT NULL;
ALTER TABLE gtu ALTER COLUMN gtu_from_date DROP NOT NULL;
ALTER TABLE gtu ALTER COLUMN gtu_to_date_mask DROP NOT NULL;
ALTER TABLE gtu ALTER COLUMN gtu_to_date DROP NOT NULL;

ALTER TABLE specimens ALTER COLUMN gtu_from_date_mask DROP NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_from_date DROP NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_to_date_mask DROP NOT NULL;
ALTER TABLE specimens ALTER COLUMN gtu_to_date DROP NOT NULL;

ALTER TABLE specimens disable trigger user;

UPDATe specimens set gtu_ref=(
SELECT ref_id FROM
(
SELECt distinct array_id[1] as ref_id,unnest(array_id) as replacement FROM(SELECT count(*) as cpt, array_sort(array_agg(id)) as array_id, code, tag_values_indexed, latitude, longitude, lat_long_accuracy, 
       location::varchar, elevation, elevation_accuracy, latitude_dms_degree, 
       latitude_dms_minutes, latitude_dms_seconds, latitude_dms_direction, 
       longitude_dms_degree, longitude_dms_minutes, longitude_dms_seconds, 
       longitude_dms_direction, latitude_utm, longitude_utm, utm_zone, 
       coordinates_source, elevation_unit 
  FROM gtu

  GROUP BY  code, tag_values_indexed, latitude, longitude, lat_long_accuracy, 
       location::varchar, elevation, elevation_accuracy, latitude_dms_degree, 
       latitude_dms_minutes, latitude_dms_seconds, latitude_dms_direction, 
       longitude_dms_degree, longitude_dms_minutes, longitude_dms_seconds, 
       longitude_dms_direction, latitude_utm, longitude_utm, utm_zone, 
       coordinates_source, elevation_unit  
) as a )
as b WHERE gtu_ref=replacement
) 
WHERE gtu_ref is not null;

ALTER TABLE specimens enable trigger user;


-------
----delete GTUs not bount to a specilan anymore
ALTER table gtu disable trigger user;
DELETE FROM gtu where id not in (select gtu_ref FROM specimens);
ALTER table gtu enable trigger user;

ALTER table tags disable trigger user;

DELETE FROM tags where gtu_ref not in (select if from gtu);

ALTEr table tags enable trigger user;

ALTEr table tag_groups disable trigger user;

DELETE FROM tag_groups where gtu_ref not in (select if from gtu);

ALTEr table tag_groups enable trigger user;


---------------
----

--note: normally the specimen has already a copy of the date of the GTUS (no need to update this










