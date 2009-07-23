-- Testing the copy code for GTU
\unset ECHO
\i unit_launch.sql
SELECT plan(5);

SELECT diag('Clear Referenced record with record_id and table_name');

INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);
INSERT INTO comments (table_name, record_id, notion_concerned, comment,comment_ts)
	VALUES ('taxonomy',1,'name','Roooh c''est TOF ça',to_tsvector(''));
INSERT INTO comments (table_name, record_id, notion_concerned, comment,comment_ts)
	VALUES ('taxonomy',0,'name','béh c''est vide...',to_tsvector(''));
SELECT ok ( 2 = (SELECT count(*) from comments));

DELETE FROM taxonomy where id=1;

SELECT ok ( 1 = (SELECT count(*) from comments),'Verify if the record is well deleted');
SELECT ok ( 0 = (SELECT record_id from comments),'the deleted record is the right one');


INSERT INTO specimens (id, collection_ref) VALUES (1,1);
INSERT INTO specimens (id, collection_ref) VALUES (2,2);

INSERT INTO my_saved_specimens (user_ref, name, specimen_ids) VALUES (1,'Ma liste','1,2');

DELETE FROM specimens WHERE id=2;
SELECT ok ( '1' = (SELECT specimen_ids from my_saved_specimens),'specimens_ids has a specimen less');
DELETE FROM specimens WHERE id=1;
SELECT ok ( '' = (SELECT specimen_ids from my_saved_specimens),'specimens_ids has no specimens more');

SELECT * FROM finish();
ROLLBACK;