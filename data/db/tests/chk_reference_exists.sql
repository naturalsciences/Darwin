\unset ECHO
\i unit_launch.sql
SELECT plan(5);
INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);

SELECT throws_ok('INSERT INTO people_aliases (referenced_relation, record_id, person_ref, collection_ref, person_name)
	VALUES (''mineralogy'', 12, 1, null, ''Jozééé'')',23514);

SELECT lives_ok('INSERT INTO people_aliases (referenced_relation, record_id, person_ref, collection_ref, person_name)
	VALUES (''mineralogy'', 0, 1, null, ''Jozééé'')');

SELECT lives_ok('INSERT INTO users_tracking (referenced_relation, record_id,user_ref, modification_date_time)
				VALUES (''taxonomy'',1,1,NOW())');
SELECT lives_ok('INSERT INTO catalogue_relationships (referenced_relation, record_id_1, record_id_2)
	VALUES (''taxonomy'', 0, 1)');
SELECT throws_ok('INSERT INTO catalogue_relationships (referenced_relation, record_id_1, record_id_2)
	VALUES (''taxonomy'', 0, 69)');
SELECT * FROM finish();
ROLLBACK;
