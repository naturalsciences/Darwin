-- Testing the main manager update for collections
\unset ECHO
\i unit_launch.sql
SELECT plan(6);

INSERT INTO users(id, family_name, formated_name, db_user_type) VALUES (100000, 'Jos Chevremont', 'Jos Chevremont', 4);
INSERT INTO people(id, is_physical, db_people_type, sub_type, family_name, formated_name) VALUES (100002, false, 1, 'Federal Institution', 'Institut des Cocinnelles', 'Institut des Cocinnelles');
INSERT INTO collections(id, code, name, institution_ref, main_manager_ref) VALUES (100000, 'Bulots', 'Bulots', 100002, 100000);
INSERT INTO collections(id, code, name, institution_ref, main_manager_ref, parent_ref) VALUES (100001, 'Bulots Af.', 'Bulots d''Afrique', 100002, 100000, 100000);

SELECT ok(100000 = (SELECT DISTINCT user_ref FROM collections_rights WHERE collection_ref = 100001),'Insertion of main manager as one with rights ok');

SELECT lives_ok('UPDATE collections_rights SET db_user_type = 8 WHERE collection_ref = 100001 and user_ref = 100000');
SELECT throws_ok('UPDATE collections_rights SET db_user_type = 2 WHERE collection_ref = 100001 and user_ref = 100000');
SELECT throws_ok('UPDATE collections_rights SET user_ref = 1 WHERE collection_ref = 100001 and user_ref = 100000');
SELECT throws_ok('DELETE FROM collections_rights WHERE collection_ref = 100001 and user_ref = 100000');
SELECT lives_ok('DELETE FROM collections WHERE id = 100001');

-- Finish the tests and clean up.
SELECT * FROM finish();
ROLLBACK;