-- Testing the copy code for GTU
\unset ECHO
\i unit_launch.sql
SELECT plan(3);

SELECT diag('Clear Referenced record with record_id and table_name');

INSERT INTO taxa (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);
INSERT INTO comments (table_name, record_id, notion_concerned, comment,comment_ts)
	VALUES ('taxa',1,'name','Roooh c''est TOF ça',to_tsvector(''));
INSERT INTO comments (table_name, record_id, notion_concerned, comment,comment_ts)
	VALUES ('taxa',0,'name','béh c''est vide...',to_tsvector(''));
SELECT ok ( 2 = (SELECT count(*) from comments));

DELETE FROM taxa where id=1;

SELECT ok ( 1 = (SELECT count(*) from comments),'Verify if the record is well deleted');
SELECT ok ( 0 = (SELECT record_id from comments),'the deleted record is the right one');

-- Finish the tests and clean up.
SELECT * FROM finish();
ROLLBACK;