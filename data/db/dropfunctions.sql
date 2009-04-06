\set log_error_verbosity terse

DROP FUNCTION IF EXISTS fct_clr_incrementMainCode() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_specimensMainCode() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_idToCode() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_one_pref_language(person people_languages.people_ref%TYPE, prefered people_languages.prefered_language%TYPE, table_prefix varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_one_pref_language(person people_languages.people_ref%TYPE, prefered people_languages.prefered_language%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_fullToIndex() CASCADE;
DROP FUNCTION IF EXISTS fullToIndex(to_indexed varchar) CASCADE;
DROP FUNCTION IF EXISTS fct_chk_PeopleIsMoral(people_ref people.id%TYPE) CASCADE;
DROP FUNCTION IF EXISTS fct_clr_specialstatus() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_fullToIndexDates() CASCADE;

DROP FUNCTION IF EXISTS fct_compose_date() CASCADE;
DROP FUNCTION IF EXISTS fct_compose_timestamp() CASCADE;

DROP FUNCTION IF EXISTS fct_clear_referencedRecord() CASCADE;
DROP FUNCTION IF EXISTS fct_clear_referencedPeople() CASCADE;

DROP FUNCTION IF EXISTS fct_remove_array_elem(IN in_array anyarray, IN elem anyelement,OUT out_array anyarray) CASCADE;
DROP FUNCTION IF EXISTS fct_explode_array(in_array anyarray) CASCADE;

DROP FUNCTION IF EXISTS fct_cas_userType() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_AreAuthors() CASCADE;

DROP FUNCTION IF EXISTS fct_cpy_FormattedName() CASCADE;
DROP FUNCTION IF EXISTS fct_clr_title() CASCADE;
DROP FUNCTION IF EXISTS fct_clr_SavedSpecimense() CASCADE;

/*** All hierarchical catalogues functions ***/
DROP FUNCTION IF EXISTS fct_get_hierarchy_from_parents(table_name varchar, id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_hierarchy_from_parents() CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_cascade_children_indexed_names (table_name varchar, new_level_ref template_classifications.level_ref%TYPE, new_name_indexed template_classifications.name_indexed%TYPE, new_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_name_updt_impact_children() CASCADE;
DROP FUNCTION IF EXISTS fct_chk_possible_upper_level (table_name varchar, new_parent_ref template_classifications.parent_ref%TYPE, new_level_ref template_classifications.level_ref%TYPE, new_id integer) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_update_children_when_parent_updated (table_name varchar, parent_id integer, parent_old_level template_classifications.level_ref%TYPE, parent_new_level template_classifications.level_ref%TYPE, parent_hierarchy_ref integer[], parent_hierarchy_indexed varchar[]) CASCADE;
DROP FUNCTION IF EXISTS fct_cpy_update_levels_or_parent_cascade() CASCADE;

