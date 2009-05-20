\unset ECHO
\i unit_launch.sql
SELECT plan(20);

SELECT diag('Remove a people');

SELECT diag('Manual');
UPDATE people SET db_people_type = 6 WHERE id=2;
UPDATE people SET db_people_type = 6 WHERE id=1;
INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);

insert into people (id, db_people_type, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date, gender, end_date) VALUES (3,6, true, 'sdf', 'doesfdjohn', to_tsvector('sd'), 'qsd', 'qsd', DATE 'June 20, 1989', 'M',DATE 'January 1, 0000');
insert into people (id,db_people_type, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date, gender, end_date) VALUES (4,6, true, 'Doe Jsssohn', 'sssss', to_tsvector('Doe qsdqsd'), 'Dssoe', 'Johdn', DATE 'June 20, 1979', 'M', DATE 'January 1, 0000');

INSERT INTO expertises (table_name, record_id, expert_ref, defined_by_ordered_ids_list) VALUES ('taxonomy', 0, 2, array[1,5,4,3,2]);
SELECT ok(array[1,5,4,3,2] = (SELECT defined_by_ordered_ids_list FROM expertises),'Check if the array is well defined');

UPDATE expertises SET defined_by_ordered_ids_list = fct_remove_array_elem(defined_by_ordered_ids_list,5);
SELECT ok(array[1,4,3,2] = (SELECT defined_by_ordered_ids_list FROM expertises),'Check if the array is well defined');

DELETE FROM expertises WHERE  table_name='taxonomy' AND record_id=0;

SELECT diag('Auto');

insert into people (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date, gender, end_date) VALUES (5, true, 'Doe John', 'doejohn', to_tsvector('Doe John'), 'Doe', 'John', DATE 'June 20, 1979', 'M', DATE 'January 1, 0000');

INSERT INTO catalogue_relationships (table_name, record_id_1, record_id_2, defined_by_ordered_ids_list)
	VALUES ('taxonomy', 0, 1, ARRAY[1,2,3]);
	
INSERT INTO catalogue_people (table_name, record_id, people_ordered_ids_list, defined_by_ordered_ids_list) 
	VALUES ('taxonomy', 0, ARRAY[1,2,3], ARRAY[5,6]);
	
INSERT INTO catalogue_properties (table_name, record_id, property_type, property_unit, property_min, property_min_unified, date_from, date_to, defined_by_ordered_ids_list)
	VALUES ('taxonomy',0,'Ph', 'm','{7}','{7}',TIMESTAMP '0001-01-01 00:00:00', TIMESTAMP '0001-01-01 00:00:00',ARRAY[1,2,3] );
	
INSERT INTO expertises (table_name, record_id, expert_ref, defined_by_ordered_ids_list) 
	VALUES ('taxonomy', 0, 1, array[1,5,4,3,2]);
	
INSERT INTO identifications (table_name, record_id, notion_concerned, identifiers_ordered_ids_list, defined_by_ordered_ids_list)
	VALUES ('taxonomy', 0, 'Taxonomic identification', ARRAY[1,2,3], ARRAY[5,6]);
	
INSERT INTO class_vernacular_names (table_name, record_id, id, community,defined_by_ordered_ids_list)
	VALUES ('taxonomy',0,1,'testlang',ARRAY[1,2,3]);

INSERT INTO specimens (id, collection_ref) VALUES (1,1);
INSERT INTO specimens_accompanying (specimen_ref, taxon_ref, mineral_ref, defined_by_ordered_ids_list)
	VALUES (1, 0, 0, ARRAY[1,2,3]);

DELETE FROM people WHERE id=2;

SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM catalogue_relationships), ' On Catalogue_relationship');
SELECT ok(array[1,3] = (SELECT people_ordered_ids_list FROM catalogue_people), ' On catalogue_people (author list)');
SELECT ok(array[5,6] = (SELECT defined_by_ordered_ids_list FROM catalogue_people), ' On catalogue_people (author list ONLY)');

SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM catalogue_properties), ' On catalogue_properties');
SELECT ok(array[1,5,4,3] = (SELECT defined_by_ordered_ids_list FROM expertises), ' On expertises');
SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM class_vernacular_names), ' On class_vernacular_names');
SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM specimens_accompanying), ' On specimens_accompanying');

SELECT ok(array[1,3] = (SELECT identifiers_ordered_ids_list FROM identifications), ' On identifications (identifier list)');
SELECT ok(array[5,6] = (SELECT defined_by_ordered_ids_list FROM identifications), ' On identifications (identifier list ONLY)');


DELETE FROM people WHERE id=5;


SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM catalogue_relationships), ' On Catalogue_relationship');
SELECT ok(array[1,3] = (SELECT people_ordered_ids_list FROM catalogue_people), ' On catalogue_people (author list)');
SELECT ok(array[6] = (SELECT defined_by_ordered_ids_list FROM catalogue_people), ' On catalogue_people (author list ONLY)');

SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM catalogue_properties), ' On catalogue_properties');
SELECT ok(array[1,4,3] = (SELECT defined_by_ordered_ids_list FROM expertises), ' On expertises');
SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM class_vernacular_names), ' On class_vernacular_names');
SELECT ok(array[1,3] = (SELECT defined_by_ordered_ids_list FROM specimens_accompanying), ' On specimens_accompanying');

SELECT ok(array[1,3] = (SELECT identifiers_ordered_ids_list FROM identifications), ' On identifications (identifier list)');
SELECT ok(array[6] = (SELECT defined_by_ordered_ids_list FROM identifications), ' On identifications (identifier list ONLY)');
SELECT * FROM finish();
ROLLBACK;