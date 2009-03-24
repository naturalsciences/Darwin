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
