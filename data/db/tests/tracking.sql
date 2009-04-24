\unset ECHO
\i unit_launch.sql
SELECT plan(1);
INSERT INTO users_tables_fields_tracked(table_name, field_name, user_ref) VALUES ('taxonomy', 'parenf_ref', 2);

INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);

SELECT ok(1 = (SELECT count(*) FROM users_tracking WHERE table_name='taxonomy' AND record_id=1 AND user_ref=2),'INSERT A tracking line');
SELECT * FROM users_tracking_records;

SELECT * FROM finish();
ROLLBACK;