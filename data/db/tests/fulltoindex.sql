\unset ECHO
\i unit_launch.sql
SELECT plan(39);

SELECT diag('FulltoIndex Function');
SELECT ok('msdfnjrt' = fullToIndex('MsdfnJrt'),'With Majuscule and minuscule');
SELECT ok('mealonyeob' = fullToIndex('Méalo-nÿeø@ß€'),'With Accents and special chars');
SELECT ok('elowyorleds' = fullToIndex('ÉLo Wÿorléds!'),'Majuscule, minuscule, spaces, accents and punctuation');
SELECT ok( 'aaaaaaceeeeiiiinoooooxouuuuy' = fullToIndex('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖ×ØÙÚÛÜÝ'),'a Bunch of weird chars');
SELECT ok( 'aaaaaaceeeeiiiinoooooouuuuy'= fullToIndex('àáâãäåçèéêëìíîïñòóôõöøùúûüý'),'another Bunch of lower weird chars');
SELECT ok( 'aeoeaeoe' = fullToIndex('æ œ Ӕ Œ'),'ligature ae-oe');
SELECT ok( 'sfdfdfggdfklmfklmgfdndgndgnfnnfnfngndfg' = fullToIndex('sfdfdfggdfklmfklmgfd,ndgndgnfnnfnfngndfgndgfndfnvbvloùsdop osdf,n'),'More than 40 chars');
SELECT ok( fullToIndex(null) is null,'With null argument');

SELECT diag('FulltoIndex Trigger');

INSERT INTO catalogue_properties (table_name, record_id, property_type, date_from_indexed, date_to_indexed, property_unit, property_min, property_min_unified ) VALUES ('taxa',0,'Ph',NOW(),NOW(), 'm','{7}','{7}');
SELECT ok( '' = (SELECT property_sub_type_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_sub_type_indexed');
SELECT ok( '' = (SELECT property_method_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_method_indexed');
SELECT ok( '' = (SELECT property_tool_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_tool_indexed');

INSERT INTO catalogue_properties (table_name, record_id, property_type, date_from_indexed, date_to_indexed, property_unit, property_min, property_min_unified, property_tool_indexed, property_method_indexed, property_sub_type_indexed ) VALUES ('taxa',0,'Temperature',NOW(),NOW(), 'degC','{42}','{42}', 'ham ér', 'cra hé', 'Lambert 72');
SELECT ok( '' = (SELECT property_sub_type_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Temperature'),'FulltoIndex on catalogue_properties null - property_sub_type_indexed');
SELECT ok( '' = (SELECT property_method_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Temperature'),'FulltoIndex on catalogue_properties null - property_method_indexed');
SELECT ok( '' = (SELECT property_tool_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Temperature'),'FulltoIndex on catalogue_properties null - property_tool_indexed');

INSERT INTO chronostratigraphy (id, name, level_ref ) VALUES (1,'ÉLo Wÿorléds', 55);
SELECT ok( 'elowyorleds' = (SELECT name_indexed FROM chronostratigraphy WHERE id=1),'FulltoIndex on chronostratigraphy');

INSERT INTO expeditions (id, name,name_ts ) VALUES (1,'ÉLo Wÿorléds',to_tsvector('ÉLo Wÿorléds'));
SELECT ok( 'elowyorleds' = (SELECT name_indexed FROM expeditions WHERE id=1),'FulltoIndex on expeditions');


INSERT INTO habitats (id, code, description, description_ts) VALUES (1,'Lé Hâbitôt','',to_tsvector(''));
SELECT ok( 'lehabitot' = (SELECT code_indexed FROM habitats WHERE id=1),'FulltoIndex on habitats');

INSERT INTO identifications (table_name, record_id, notion_concerned, identifiers_ordered_ids_list, value_defined) VALUES ('taxa', 0, 'Expertise' ,'{}', 'Jé #spéè!');
SELECT ok( 'jespee' = (SELECT value_defined_indexed FROM identifications WHERE record_id=0),'FulltoIndex on identifications');

INSERT INTO identifications (table_name, record_id, notion_concerned, identifiers_ordered_ids_list, value_defined) VALUES ('taxa', 0, 'Taxonomic identification' ,'{}', null);
SELECT ok( '' = (SELECT value_defined_indexed FROM identifications WHERE record_id=0 AND notion_concerned='Taxonomic identification'),'FulltoIndex on identifications with null');

INSERT INTO lithology (id, name, level_ref) VALUES (1,'éLoow !', null);
SELECT ok( 'eloow' = (SELECT name_indexed FROM lithology WHERE id=1),'FulltoIndex on lithology');

INSERT INTO lithostratigraphy (id,name, level_ref) VALUES (1, 'Méalo-nÿeø@ß€', 64);
SELECT ok( 'mealonyeob' = (SELECT name_indexed FROM lithostratigraphy WHERE id=1),'FulltoIndex on lithostratigraphy');

INSERT INTO mineralogy (id, name, code, level_ref) VALUES (1, 'Lé bou/ caiéoui', 0, 70);
SELECT ok( 'leboucaieoui' = (SELECT name_indexed FROM mineralogy WHERE id=1),'FulltoIndex on mineralogy');

INSERT INTO multimedia (id, title, descriptive_ts) VALUES (1, 'À L''énorme Tické!', to_tsvector('À L''énorme Tické!'));
SELECT ok( 'alenormeticke' = (SELECT title_indexed FROM multimedia WHERE id=1),'FulltoIndex on multimedia');

INSERT INTO multimedia_keywords (object_ref,keyword) VALUES (1,'La ''mèr'' Nwàre') ;
SELECT ok( 'lamernware' = (SELECT keyword_indexed FROM multimedia_keywords WHERE object_ref=1),'FulltoIndex on multimedia_keywords');

INSERT INTO multimedia_codes (code_prefix,code, multimedia_ref) VALUES ('12é-MOL7385',6847,1);
SELECT ok( '12emol73856847' = (SELECT full_code_indexed FROM multimedia_codes WHERE multimedia_ref=1),'FulltoIndex on multimedia_codes');

insert into people (id, is_physical, formated_name, formated_name_ts, family_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, sort_string, end_date_day_indexed, end_date_month_indexed, end_date_year_indexed ) VALUES
(3, true, 'The Expert',to_tsvector('The Expert'),  'The Expert', 0, 0, 0, 'theexpert', 0, 0,0 );
SELECT ok( 'theexpert' = (SELECT formated_name_indexed FROM people WHERE id=3),'FulltoIndex on people');


INSERT INTO specimens (id, collection_ref) VALUES (1,1);
INSERT INTO specimens_codes (code_prefix,code, specimen_ref) VALUES ('12é-MOL7385',6847,1);
SELECT ok( '12emol73856847' = (SELECT full_code_indexed FROM specimens_codes WHERE specimen_ref=1),'FulltoIndex on specimens_codes');

INSERT INTO specimens_codes (code_category, code_prefix, code, specimen_ref) VALUES ('secondary','éà',null,1);
SELECT ok( 'ea' = (SELECT full_code_indexed FROM specimens_codes WHERE specimen_ref=1 AND code_category='secondary'),'FulltoIndex on specimens_codes with null(bis)');


INSERT INTO specimen_individuals (id, specimen_ref, type) VALUES (1,1,'holotype');
INSERT INTO specimen_parts (id, specimen_individual_ref, specimen_part) VALUES (1, 1, 'head');
INSERT INTO specimen_parts_codes (code_prefix,code, specimen_part_ref) VALUES ('12é-MOL7385',6847,1);
SELECT ok( '12emol73856847' = (SELECT full_code_indexed FROM specimen_parts_codes WHERE specimen_part_ref=1),'FulltoIndex on specimen_parts_codes');

INSERT INTO specimen_parts_codes (code_category, code_prefix, code, specimen_part_ref) VALUES ('secondary','éà',null,1);
SELECT ok( 'ea' = (SELECT full_code_indexed FROM specimen_parts_codes WHERE specimen_part_ref=1 AND code_category='secondary'),'FulltoIndex on specimen_parts_codes');


INSERT INTO tags (id, label) VALUES (1,'La ''mèr'' Nwàre') ;
SELECT ok( 'lamernware' = (SELECT label_indexed FROM tags WHERE id=1),'FulltoIndex on tags');

INSERT INTO tag_groups (id, tag_ref,group_name) VALUES (1, 1, 'Rév#ers');
SELECT ok( 'revers' = (SELECT group_name_indexed FROM tag_groups WHERE id=1),'FulltoIndex on tags_groups');


INSERT INTO taxa (id, name, level_ref) VALUES (1, 'Méàleis Gùbularis&', 1);
SELECT ok( 'mealeisgubularis' = (SELECT name_indexed FROM taxa WHERE id=1),'FulltoIndex on taxa name');

insert into users (id, is_physical, formated_name,formated_name_ts, family_name, given_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, gender, sort_string) VALUES (3, true, 'Bill Maréchal', to_tsvector('Maréchal Bill'), 'Maréchal', 'Bill', 0, 0, 0, 'M', 'billmarechal');

SELECT ok( 'billmarechal' = (SELECT formated_name_indexed FROM users WHERE id=3),'FulltoIndex on user');

INSERT INTO class_vernacular_names (table_name, record_id, id, community) VALUES ('taxa',0,1,'testlang');
INSERT INTO vernacular_names (vernacular_class_ref, name, name_ts) VALUES (1,'Éléphant!',to_tsvector('Éléphant'));
SELECT ok( 'elephant' = (SELECT name_indexed FROM vernacular_names WHERE vernacular_class_ref=1),'FulltoIndex on vernacular_names');

SELECT diag('Copy Hierarchy from parent Trigger');

INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'ÉLoWÿ', 56, 1);
SELECT ok( 1 = (SELECT eon_ref FROM chronostratigraphy WHERE id=2),'Eon reference of chronostratigraphic unit N°2: 1');
SELECT ok( 'elowyorleds' = (SELECT eon_indexed FROM chronostratigraphy WHERE id=2),'Eon name of chronostratigraphic unit N°2: elowyorleds');
SELECT ok( 2 = (SELECT era_ref FROM chronostratigraphy WHERE id=2),'Era reference of chronostratigraphic unit N°2: 2');
SELECT ok( 'elowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id=2),'Era name of chronostratigraphic unit N°2: elowy');

INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (2, '-nÿeø@ß€', 65, 1);
SELECT ok( 1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 2), 'Group reference of lithostratigraphic unit N°2: 1');
SELECT ok( 'mealonyeob' = (SELECT name_indexed FROM lithostratigraphy WHERE id=2),'Group name of lithostratigraphic unit N°2: mealonyeob');
SELECT ok( 2 = (SELECT group_ref FROM lithostratigraphy WHERE id = 2), 'Group reference of lithostratigraphic unit N°2: 1');
SELECT ok( 'mealonyeob' = (SELECT name_indexed FROM lithostratigraphy WHERE id=2),'Group name of lithostratigraphic unit N°2: mealonyeob');



SELECT * FROM finish();
ROLLBACK;
