\unset ECHO
\i unit_launch.sql
SELECT plan(2);

SELECT diag('Insert a record in catalogue people');
SELECT lives_ok('insert into catalogue_people(referenced_relation,record_id, people_type,people_sub_type, order_by,people_ref) VALUES(''specimens'', 3,''collector'','''',1,1)','Add a collector');
SELECT ok(1 = (SELECT count(*) FROM catalogue_people WHERE people_ref = 1 AND referenced_relation = 'specimens'));
SELECT * FROM finish();
ROLLBACK;
