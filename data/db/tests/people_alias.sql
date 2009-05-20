\unset ECHO
\i unit_launch.sql
SELECT plan(9);

INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (2, 'AMéàleis Gùbularis&', 2, 1);

SELECT ok (true = (SELECT fct_chk_Is_FirstLevel('taxonomy',1)),' Check if Taxa is first level');
SELECT ok (false = (SELECT fct_chk_Is_FirstLevel('taxonomy',2)),' Check if Taxa is not first level');

SELECT throws_ok('INSERT INTO people_aliases (table_name, record_id, person_ref, collection_ref, person_name)
	VALUES (''taxonomy'', 2, 1, null, ''Jozééé'')');

SELECT lives_ok('INSERT INTO people_aliases (table_name, record_id, person_ref, collection_ref, person_name)
	VALUES (''taxonomy'', 1, 1, null, ''Jozééé'')','Insert into taxonomy');

SELECT lives_ok('INSERT INTO people_aliases (table_name, record_id, person_ref, collection_ref, person_name)
	VALUES (''mineralogy'', 0, 1, null, ''Jozééé'')','Insert into mineralogy');
	
SELECT diag('Checking db_people_type');
UPDATE people SET db_people_type = 6 WHERE id=2;
UPDATE people SET db_people_type = 6 WHERE id=1;

INSERT INTO catalogue_people (table_name, record_id, people_ordered_ids_list)
	VALUES ('taxonomy', '1', ARRAY[1,2]);

SELECT throws_ok('UPDATE people SET db_people_type = 4 WHERE id=2');

UPDATE catalogue_people SET people_ordered_ids_list = ARRAY[1] WHERE table_name='taxonomy' AND record_id=1;

SELECT lives_ok('UPDATE people SET db_people_type = 4 WHERE id=2');

SELECT diag('Checking IF all author are authors :)');

SELECT throws_ok('UPDATE catalogue_people SET people_ordered_ids_list = ARRAY[1,2] WHERE table_name=''taxonomy'' AND record_id=1');

UPDATE people SET db_people_type = 6 WHERE id=2;
SELECT lives_ok('UPDATE catalogue_people SET people_ordered_ids_list = ARRAY[1,2] WHERE table_name=''taxonomy'' AND record_id=1');


SELECT * FROM finish();
ROLLBACK;