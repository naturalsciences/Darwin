\unset ECHO
\i unit_launch.sql
SELECT plan(78);

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


INSERT INTO catalogue_properties (table_name, record_id, property_type, property_unit, property_min, property_min_unified ) VALUES ('taxa',0,'Ph', 'm','{7}','{7}');
SELECT ok( '' = (SELECT property_sub_type_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_sub_type_indexed');
SELECT ok( '' = (SELECT property_method_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_method_indexed');
SELECT ok( '' = (SELECT property_tool_indexed FROM catalogue_properties WHERE record_id=0),'FulltoIndex on catalogue_properties null - property_tool_indexed');

INSERT INTO catalogue_properties (table_name, record_id, property_type, date_from, date_to, property_unit, property_min, property_min_unified, property_tool_indexed, property_method_indexed, property_sub_type_indexed ) VALUES ('taxa',0,'Temperature',NOW(),NOW(), 'degC','{42}','{42}', 'ham ér', 'cra hé', 'Lambert 72');
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

insert into people (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, sort_string, end_date_day_indexed, end_date_month_indexed, end_date_year_indexed ) VALUES
(3, true, 'The Expert', 'theexpert', to_tsvector('The Expert'),  'The Expert', 0, 0, 0, 'theexpert', 0, 0,0 );
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

insert into users (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, gender, sort_string) VALUES (3, true, 'Bill Maréchal', 'marechalbill', to_tsvector('Maréchal Bill'), 'Maréchal', 'Bill', 0, 0, 0, 'M', 'billmarechal');

SELECT ok( 'marechalbill' = (SELECT formated_name_indexed FROM users WHERE id=3),'FulltoIndex on user');

INSERT INTO class_vernacular_names (table_name, record_id, id, community) VALUES ('taxa',0,1,'testlang');
INSERT INTO vernacular_names (vernacular_class_ref, name, name_ts) VALUES (1,'Éléphant!',to_tsvector('Éléphant'));
SELECT ok( 'elephant' = (SELECT name_indexed FROM vernacular_names WHERE vernacular_class_ref=1),'FulltoIndex on vernacular_names');

SELECT diag('Copy Hierarchy from parent Trigger: Chronostratigraphy');


INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'ÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (3, 'BÉLoWÿ', 57, 2);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'CÉLoWÿ', 58, 3);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (5, 'DÉLoWÿ', 59, 4);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (6, 'EÉLoWÿ', 60, 5);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (7, 'FÉLoWÿ', 61, 6);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (8, 'GÉLoWÿ', 62, 7);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (9, 'HÉLoWÿ', 63, 8);

SELECT ok( 1 = (SELECT eon_ref FROM chronostratigraphy WHERE id=2),'Eon reference of chronostratigraphic unit N°2: 1');
SELECT ok( 'elowyorleds' = (SELECT eon_indexed FROM chronostratigraphy WHERE id=2),'Eon name of chronostratigraphic unit N°2: elowyorleds');
SELECT ok( 2 = (SELECT era_ref FROM chronostratigraphy WHERE id=2),'Era reference of chronostratigraphic unit N°2: 2');
SELECT ok( 'elowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id=2),'Era name of chronostratigraphic unit N°2: elowy');
SELECT ok( 3 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id=3),'Sub-Era reference of chronostratigraphic unit N°3: 3');
SELECT ok( 'belowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id=3),'Sub-Era name of chronostratigraphic unit N°3: belowy');
SELECT ok( 4 = (SELECT system_ref FROM chronostratigraphy WHERE id=4),'System reference of chronostratigraphic unit N°4: 4');
SELECT ok( 'celowy' = (SELECT system_indexed FROM chronostratigraphy WHERE id=4),'System name of chronostratigraphic unit N°4: celowy');
SELECT ok( 5 = (SELECT serie_ref FROM chronostratigraphy WHERE id=5),'Serie reference of chronostratigraphic unit N°5: 5');
SELECT ok( 'delowy' = (SELECT serie_indexed FROM chronostratigraphy WHERE id=5),'Serie name of chronostratigraphic unit N°5: delowy');
SELECT ok( 6 = (SELECT stage_ref FROM chronostratigraphy WHERE id=6),'Stage reference of chronostratigraphic unit N°6: 6');
SELECT ok( 'eelowy' = (SELECT stage_indexed FROM chronostratigraphy WHERE id=6),'Stage name of chronostratigraphic unit N°6: eelowy');
SELECT ok( 7 = (SELECT sub_stage_ref FROM chronostratigraphy WHERE id=7),'Sub-Stage reference of chronostratigraphic unit N°7: 7');
SELECT ok( 'felowy' = (SELECT sub_stage_indexed FROM chronostratigraphy WHERE id=7),'Sub-Stage name of chronostratigraphic unit N°7: felowy');
SELECT ok( 8 = (SELECT sub_level_1_ref FROM chronostratigraphy WHERE id=8),'Sub level 1 reference of chronostratigraphic unit N°8: 8');
SELECT ok( 'gelowy' = (SELECT sub_level_1_indexed FROM chronostratigraphy WHERE id=8),'Sub level 1 name of chronostratigraphic unit N°8: gelowy');
SELECT ok( 9 = (SELECT sub_level_2_ref FROM chronostratigraphy WHERE id=9),'Sub level 2 reference of chronostratigraphic unit N°9: 9');
SELECT ok( 'helowy' = (SELECT sub_level_2_indexed FROM chronostratigraphy WHERE id=9),'Sub level 2 name of chronostratigraphic unit N°9: helowy');

SELECT diag('Copy Hierarchy from parent Trigger: Lithostratigraphy');


INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'Méalo-nÿeø@ß€A', 65, 1);
INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (3, 'Méalo-nÿeø@ß€B', 66, 2);
INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'Méalo-nÿeø@ß€C', 67, 3);
INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (5, 'Méalo-nÿeø@ß€D', 68, 4);
INSERT INTO lithostratigraphy (id, name, level_ref, parent_ref) VALUES (6, 'Méalo-nÿeø@ß€F', 69, 5);
SELECT ok( 1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 2), 'Group reference of lithostratigraphic unit N°2: 1');
SELECT ok( 'mealonyeob' = (SELECT group_indexed FROM lithostratigraphy WHERE id=2),'Group name of lithostratigraphic unit N°2: mealonyeob');
SELECT ok( 2 = (SELECT formation_ref FROM lithostratigraphy WHERE id = 2), 'Formation reference of lithostratigraphic unit N°2: 2');
SELECT ok( 'mealonyeoba' = (SELECT formation_indexed FROM lithostratigraphy WHERE id=2),'Formation name of lithostratigraphic unit N°2: mealonyeoba');
SELECT ok( 3 = (SELECT member_ref FROM lithostratigraphy WHERE id = 3), 'Member reference of lithostratigraphic unit N°3: 3');
SELECT ok( 'mealonyeobb' = (SELECT member_indexed FROM lithostratigraphy WHERE id=3),'Member name of lithostratigraphic unit N°3: mealonyeobb');
SELECT ok( 4 = (SELECT layer_ref FROM lithostratigraphy WHERE id = 4), 'Layer reference of lithostratigraphic unit N°4: 4');
SELECT ok( 'mealonyeobc' = (SELECT layer_indexed FROM lithostratigraphy WHERE id=4),'Layer name of lithostratigraphic unit N°4: mealonyeobc');
SELECT ok( 5 = (SELECT sub_level_1_ref FROM lithostratigraphy WHERE id=5),'Sub level 1 reference of chronostratigraphic unit N°5: 5');
SELECT ok( 'mealonyeobd' = (SELECT sub_level_1_indexed FROM lithostratigraphy WHERE id=5),'Sub level 1 name of chronostratigraphic unit N°5: mealonyeobd');
SELECT ok( 6 = (SELECT sub_level_2_ref FROM lithostratigraphy WHERE id=6),'Sub level 2 reference of chronostratigraphic unit N°6: 6');
SELECT ok( 'mealonyeobf' = (SELECT sub_level_2_indexed FROM lithostratigraphy WHERE id=6),'Sub level 2 name of chronostratigraphic unit N°6: mealonyeobf');

SELECT diag('FulltoIndex Dates Trigger');


SELECT ok( 0 = (SELECT birth_date_day_indexed FROM users WHERE id=2),'DateIndexed on user birth_date_day_indexed');
SELECT ok( 0 = (SELECT birth_date_month_indexed FROM users WHERE id=2),'DateIndexed on user birth_date_day_indexed');
SELECT ok( 0 = (SELECT birth_date_year_indexed FROM users WHERE id=2),'DateIndexed on user birth_date_day_indexed');

SELECT ok( 0 = (SELECT birth_date_day_indexed FROM people WHERE id=2),'DateIndexed on people birth_date_day_indexed');
SELECT ok( 0 = (SELECT birth_date_month_indexed FROM people WHERE id=2),'DateIndexed on people birth_date_day_indexed');
SELECT ok( 0 = (SELECT birth_date_year_indexed FROM people WHERE id=2),'DateIndexed on people birth_date_day_indexed');
SELECT ok( 0 = (SELECT end_date_day_indexed FROM people WHERE id=2),'DateIndexed on people end_date_day_indexed');
SELECT ok( 0 = (SELECT end_date_month_indexed FROM people WHERE id=2),'DateIndexed on people end_date_month_indexed');
SELECT ok( 0 = (SELECT end_date_year_indexed FROM people WHERE id=2),'DateIndexed on people end_date_year_indexed');

SELECT ok( TIMESTAMP '4700-01-01 00:00:00+02BC' = (SELECT date_from_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Ph'),'DateIndexed on catalogue_properties date_from_indexed');
SELECT ok( TIMESTAMP '4700-01-01 00:00:00+02BC' = (SELECT date_to_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Ph'),'DateIndexed on catalogue_properties date_to_indexed');

SELECT ok( TIMESTAMP '4700-01-01 00:00:00+02BC' != (SELECT date_from_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Temperature'),'DateIndex on catalogue_properties not touch if  not null (from)');
SELECT ok( TIMESTAMP '4700-01-01 00:00:00+02BC' != (SELECT date_to_indexed FROM catalogue_properties WHERE record_id=0 AND property_type='Temperature'),'DateIndex on catalogue_properties not touch if  not null (to)');

SELECT * FROM finish();
ROLLBACK;
