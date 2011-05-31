\unset ECHO
\i unit_launch.sql
SELECT plan(8);

select diag('Test of staging check');
insert into imports (id, user_ref, format, filename, collection_ref) VALUES (1,1,'brol','brol.xml',2);
insert into staging (id,import_ref, "level",taxon_name) VALUES (1,1,'specimens','Falco Peregrinus');
INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Falco Coco lus (Brolus 1972)', 1);
INSERT INTO taxonomy (id, name, level_ref) VALUES (2, 'Falco Peregrinus', 1);
select ok(true = (select fct_imp_taxonomy(s.*) from staging s));

select is(2 , (select taxon_ref from staging s where id = 1));

insert into staging (id,import_ref, "level",taxon_name) VALUES (2,1,'specimens','Falco Brolus');

select is(1 ,(select min(fct_imp_taxonomy(s.*)::int) from staging s));

select is(null, (select  taxon_ref from staging s where id = 2));

insert into staging (id,import_ref, "level",taxon_name) VALUES (3,1,'specimens','Falco coco');

select is(1 , (select min(fct_imp_taxonomy(s.*)::int) from staging s));

select is(1, (select taxon_ref from staging s where id = 3));


INSERT INTO taxonomy (id, name, level_ref) VALUES (3, 'Falco Coco lus (Brolus 1974)', 1);

UPDATE staging set taxon_ref = null where id = 3;


select is(1 , (select min(fct_imp_taxonomy(s.*)::int) from staging s));

select is(null, (select taxon_ref from staging s where id = 3));


--select * from staging;
--select * from taxonomy;

SELECT * FROM finish();
ROLLBACK;
