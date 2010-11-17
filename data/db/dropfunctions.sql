\set log_error_verbosity terse

/*** All length conversion functions ***/

DROP FUNCTION IF EXISTS fct_cpy_unified_values() CASCADE;
DROP FUNCTION IF EXISTS convert_to_unified (IN property varchar, IN property_unit varchar, IN property_type varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_length_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_temperature_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_time_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_speed_conversion (IN property real, IN property_unit catalogue_properties.property_unit%TYPE) CASCADE;

/*** Tracking and logging functions ***/

DROP FUNCTION IF EXISTS fct_trk_log_table() CASCADE;

/*** All hierarchical catalogues functions ***/

DROP FUNCTION IF EXISTS fct_cpy_update_path() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_update_levels_or_parent_cascade() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_update_children_when_parent_updated (referenced_relation varchar, parent_id integer, parent_old_level template_classifications.level_ref%TYPE, parent_new_level template_classifications.level_ref%TYPE, parent_hierarchy_ref integer[], parent_hierarchy_indexed tsvector[]) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_possible_upper_level (referenced_relation varchar, new_parent_ref template_classifications.parent_ref%TYPE, new_level_ref template_classifications.level_ref%TYPE, new_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_name_updt_impact_children() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_cascade_children_indexed_names (referenced_relation varchar, new_level_ref template_classifications.level_ref%TYPE, new_name_indexed template_classifications.name_indexed%TYPE, new_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_hierarchy_from_parents() CASCADE;
DROP FUNCTION IF EXISTS fct_get_hierarchy_from_parents(referenced_relation varchar, id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_gtuTags() CASCADE;
DROP FUNCTION IF EXISTS lineToTagRows(line text) CASCADE;

/*** Others ***/

DROP FUNCTION IF EXISTS fct_cpy_path() CASCADE;

DROP FUNCTION IF EXISTS fct_cpy_FormattedName() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_ReferencedRecord(referenced_relation varchar,record_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_AreRole() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_peopleType() CASCADE;
DROP FUNCTION IF EXISTS fct_cas_userType() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_toFullText() CASCADE;
DROP FUNCTION IF EXISTS fct_array_find(IN in_array varchar, IN elem anyelement,OUT item_order integer) CASCADE;
DROP FUNCTION IF EXISTS fct_array_find(IN in_array anyarray, IN elem anyelement,OUT item_order integer) CASCADE;
DROP FUNCTION IF EXISTS fct_explode_array(in_array anyarray) CASCADE;
DROP FUNCTION IF EXISTS fct_remove_array_elem(IN in_array anyarray, IN elem anyelement,OUT out_array anyarray) CASCADE;
DROP FUNCTION IF EXISTS fct_clear_referencedRecord() CASCADE;
DROP FUNCTION IF EXISTS fct_compose_date(day integer, month integer, year integer) CASCADE;
DROP FUNCTION IF EXISTS fct_compose_timestamp(day integer, month integer, year integer, hour integer, minute integer, second integer) CASCADE;
DROP FUNCTION IF EXISTS fct_clr_specialstatus() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_PeopleIsMoral(people_ref people.id%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_fullToIndex() CASCADE;
DROP FUNCTION IF EXISTS fullToIndex(to_indexed varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_one_pref_language(id people_languages.id%TYPE,person people_languages.people_ref%TYPE, preferred people_languages.preferred_language%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_one_pref_language(id people_languages.id%TYPE,person people_languages.people_ref%TYPE, preferred people_languages.preferred_language%TYPE, table_prefix varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_idToCode() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_specimensMainCode() CASCADE;
DROP FUNCTION IF EXISTS fct_trg_word() CASCADE;
DROP FUNCTION IF EXISTS search_words_to_query(tbl_name words.referenced_relation%TYPE, fld_name words.field_name%TYPE, value varchar, op varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_nbr_in_synonym() CASCADE;
DROP FUNCTION IF EXISTS fct_nbr_in_relation() CASCADE;
DROP FUNCTION IF EXISTS datesOverlaps(start1 date, end1 date, start2 date, end2 date) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_onceinpath(path varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_word(tbl_name character varying, fld_name character varying, word_ts tsvector) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateHosts() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateSpecHostImpact() CASCADE;
DROP FUNCTION IF EXISTS concat(text, text) CASCADE;
DROP FUNCTION IF EXISTS concat(text, text, text) CASCADE;
DROP FUNCTION IF EXISTS ts_stat(tsvector, OUT word text, OUT ndoc integer, OUT nentry integer) CASCADE;
DROP FUNCTION IF EXISTS sha1(bytea) CASCADE;
DROP FUNCTION IF EXISTS get_setting(IN param text, OUT value text) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateCollectionAdmin() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateCollectionRights() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateUserRights() CASCADE;
DROP FUNCTION IF EXISTS getGtusForTags(in_array anyarray) CASCADE;
DROP FUNCTION IF EXISTS getTagsIndexedAsArray(IN tagList varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_update_darwin_flat() CASCADE;
DROP FUNCTION IF EXISTS fct_darwin_flat_individuals_after_del() CASCADE;
DROP FUNCTION IF EXISTS fct_searchCodes(VARIADIC varchar[])  CASCADE;
DROP FUNCTION IF EXISTS fct_clr_savedspecimens() CASCADE;
DROP FUNCTION IF EXISTS fct_search_methods(str_ids varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_search_tools(str_ids varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_delete_darwin_flat_ind_part() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_location() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_specimenCollectionAllowed() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateMyWidgetsColl() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_parentCollInstitution() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_updateCollInstitutionCascade() CASCADE;
DROP FUNCTION IF EXISTS fct_unpromotion_impact_prefs() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_canUpdateCollectionsRights() CASCADE;

DROP AGGREGATE array_accum (anyelement);
DROP AGGREGATE dummy_first(anyelement);
DROP FUNCTION IF EXISTS dummy( in anyelement, inout anyelement ) CASCADE;
DROP FUNCTION IF EXISTS convert_to_integer(v_input varchar) CASCADE;

DROP FUNCTION IF EXISTS fct_search_authorized_encoding_collections (user_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_search_authorized_view_collections (user_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_filter_encodable_row(ids varchar, col_name varchar, user_id integer) CASCADE;