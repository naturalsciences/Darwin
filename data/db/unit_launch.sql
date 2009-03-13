\set ECHO
\set QUIET 1
-- Turn off echo and keep things quiet.

-- Format the output for nice TAP.
\pset format unaligned
\pset tuples_only true
\pset pager

-- Revert all changes on failure.
\set ON_ERROR_ROLLBACK 1
\set ON_ERROR_STOP true
\set QUIET 1

BEGIN;

-- CREATE SCHEMA "unittest";
SET search_path TO "unittest"; 
SET client_min_messages TO warning; -- notice;


-- Load test functions
\i lib/pgtap.sql
-- Load type,tables, functions and triggers
\i createtypes.sql
\i createdomains.sql
\i createtables.sql
\i createfunctions.sql
\i createtriggers.sql

-- Load Fixtures data
\i tests/fixtures.sql
-- Plan the tests.
SELECT plan(72);

-- Run the tests.
SELECT has_table('template_tablefields_common','template_tablefields_common exists');
SELECT has_table('table_list','table_list exists');
SELECT has_table('field_list','field_list exists');
SELECT has_table('catalogue_relationships','catalogue_relationships exists');
SELECT has_table('template_table_record_ref','template_table_record_ref exists');
SELECT has_table('catalogue_authors','catalogue_authors exists');
SELECT has_table('catalogue_levels','catalogue_levels exists');
SELECT has_table('possible_upper_levels','possible_upper_levels exists');
SELECT has_table('comments','comments exists');
SELECT has_table('tags','tags exists');
SELECT has_table('tag_groups','tag_groups exists');
SELECT has_table('gtu','gtu exists');
SELECT has_table('catalogue_properties','catalogue_properties exists');
SELECT has_table('identifications','identifications exists');
SELECT has_table('expertises','expertises exists');
SELECT has_table('class_vernacular_names','class_vernacular_names exists');
SELECT has_table('vernacular_names','vernacular_names exists');
SELECT has_table('expeditions','expeditions exists');
SELECT has_table('template_people','template_people exists');
SELECT has_table('template_people_languages','template_people_languages exists');
SELECT has_table('people','people exists');
SELECT has_table('users','users exists');
SELECT has_table('people_languages','people_languages exists');
SELECT has_table('users_languages','users_languages exists');
SELECT has_table('multimedia','multimedia exists');
SELECT has_table('template_people_users_comm_common','template_people_users_comm_common exists');
SELECT has_table('template_people_users_rel_common','template_people_users_rel_common exists');
SELECT has_table('template_people_users_addr_common','template_people_users_addr_common exists');
SELECT has_table('people_relationships','people_relationships exists');
SELECT has_table('people_comm','people_comm exists');
SELECT has_table('people_addresses','people_addresses exists');
SELECT has_table('users_comm','users_comm exists');
SELECT has_table('users_addresses','users_addresses exists');
SELECT has_table('users_login_infos','users_login_infos exists');
SELECT has_table('template_people_users_multimedia','template_people_users_multimedia exists');
SELECT has_table('people_multimedia','people_multimedia exists');
SELECT has_table('users_multimedia','users_multimedia exists');
SELECT has_table('collections','collections exists');
SELECT has_table('template_collections_users','template_collections_users exists');
SELECT has_table('collections_admin','collections_admin exists');
SELECT has_table('collections_rights','collections_rights exists');
SELECT has_table('collections_fields_visibilities','collections_fields_visibilities exists');
SELECT has_table('users_coll_rights_asked','users_coll_rights_asked exists');
SELECT has_table('record_visibilities','record_visibilities exists');
SELECT has_table('users_workflow','users_workflow exists');
SELECT has_table('users_tables_fields_tracked','users_tables_fields_tracked exists');
SELECT has_table('users_tracking','users_tracking exists');
SELECT has_table('users_tracking_records','users_tracking_records exists');
SELECT has_table('collection_maintenance','collection_maintenance exists');
SELECT has_table('my_saved_searches','my_saved_searches exists');
SELECT has_table('my_preferences','my_preferences exists');
SELECT has_table('my_saved_specimens','my_saved_specimens exists');
SELECT has_table('template_classifications','template_classifications exists');
SELECT has_table('taxa','taxa exists');
SELECT has_table('people_taxonomic_names','people_taxonomic_names exists');
SELECT has_table('chronostratigraphy','chronostratigraphy exists');
SELECT has_table('lithostratigraphy','lithostratigraphy exists');
SELECT has_table('mineralogy','mineralogy exists');
SELECT has_table('lithology','lithology exists');
SELECT has_table('habitats','habitats exists');
SELECT has_table('multimedia_keywords','multimedia_keywords exists');
SELECT has_table('soortenregister','soortenregister exists');
SELECT has_table('specimens','specimens exists');
SELECT has_table('template_codes','template_codes exists');
SELECT has_table('specimens_codes','specimens_codes exists');
SELECT has_table('multimedia_codes','multimedia_codes exists');
SELECT has_table('specimen_individuals','specimen_individuals exists');
SELECT has_table('specimen_parts','specimen_parts exists');
SELECT has_table('specimen_parts_codes','specimen_parts_codes exists');
SELECT has_table('specimen_parts_insurances','specimen_parts_insurances exists');
SELECT has_table('associated_multimedia','associated_multimedia exists');
SELECT has_table('specimens_accompanying','specimens_accompanying exists');



-- Finish the tests and clean up.
SELECT * FROM finish();


--DROP SCHEMA "unittest" CASCADE;
ROLLBACK;