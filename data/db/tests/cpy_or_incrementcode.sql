\unset ECHO
\i unit_launch.sql
SELECT plan(13);

-- TESTING incrementation of spec code for specimens
INSERT INTO specimens (id, collection_ref) VALUES (1,1);
SELECT ok( 0 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=1) ,'No code inserted if collection not incremented');

INSERT INTO specimens (id, collection_ref) VALUES (2,2);

SELECT ok( 1 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=2),'First code instered' );
SELECT ok( 1 = (SELECT code FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=2),'1st code created' );



INSERT INTO specimens (id, collection_ref, category) VALUES (3,2,'figurate');

SELECT ok( 1 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=3),'2e code inserted' );
SELECT ok( 2 = (SELECT code FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=3),'2e code incremented' );

UPDATE specimens_codes SET code_prefix='cds-', code_suffix='mol' WHERE code_category= 'main' AND specimen_ref=3;

INSERT INTO specimens (id, collection_ref, category) VALUES (4,2,'observation');
SELECT ok( 'cds-' = (SELECT code_prefix FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=4),'prefix copied' );
SELECT ok( 'mol' = (SELECT code_suffix FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=4),'suffix copied' );


-- TESTING Copying of the Spec code for parts


INSERT INTO specimen_individuals (id, specimen_ref, type) VALUES (1,1,'holotype');
INSERT INTO specimen_parts (id, specimen_individual_ref, specimen_part) VALUES (1, 1, 'head');
SELECT ok( 0 = (SELECT count(*) FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=1),'Copy when no main_code' );

INSERT INTO specimen_individuals (id, specimen_ref, type) VALUES (2,3,'topotype');
INSERT INTO specimen_parts (id, specimen_individual_ref, specimen_part) VALUES (2, 2, 'head');

SELECT ok( 1 = (SELECT count(*) FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=2),'Copy where main_code_cpy = true' );
SELECT ok( 2 = (SELECT code FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=2),'Check code copied' );

SELECT ok( 'cds-' = (SELECT code_prefix FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=2),'prefix copied' );
SELECT ok( 'mol' = (SELECT code_suffix FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=2),'suffix copied' );

INSERT INTO specimens (id, collection_ref) VALUES (5,3);
INSERT INTO specimen_individuals (id, specimen_ref, type) VALUES (3,5,'holotype');
INSERT INTO specimen_parts (id, specimen_individual_ref, specimen_part) VALUES (3, 3, 'head');
SELECT ok( 0 = (SELECT count(*) FROM specimen_parts_codes WHERE code_category = 'main' AND specimen_part_ref=3),'Copy where main_code_cpy = true but no main_codes' );

-- Finish the tests and clean up.
SELECT * FROM finish();
ROLLBACK;