\unset ECHO
\i unit_launch.sql
SELECT plan(1);

select diag('Test of staging check');
insert into imports (id, user_ref, format, filename, collection_ref) VALUES (1,1,'brol','brol.xml',2);
insert into staging (id,import_ref, "level") VALUES (1,1,'specimens');

select ok(1 = (select count(*) from staging));

SELECT * FROM finish();
ROLLBACK;
