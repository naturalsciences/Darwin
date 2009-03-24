\unset ECHO
\i unit_launch.sql
SELECT plan(183);

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



INSERT INTO catalogue_properties (table_name, record_id, property_type, property_unit, property_min, property_min_unified, date_from, date_to) VALUES ('taxa',0,'Ph', 'm','{7}','{7}',TIMESTAMP '0001-01-01 00:00:00', TIMESTAMP '0001-01-01 00:00:00');
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

insert into people (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, birth_date, sort_string, end_date ) VALUES
(3, true, 'The Expert', 'theexpert', to_tsvector('The Expert'),  'The Expert', '0001-01-01', 'theexpert', DATE '0001-01-01');
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

insert into users (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date, gender, sort_string) VALUES (3, true, 'Bill Maréchal', 'marechalbill', to_tsvector('Maréchal Bill'), 'Maréchal', 'Bill', NOW(), 'M', 'billmarechal');

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

SELECT diag('Copy Hierarchy from parent Trigger: Mineralogy');


INSERT INTO mineralogy (id, name, code, level_ref, parent_ref) VALUES (2, 'ALé bou/ caiéoui', 1, 71, 1);
INSERT INTO mineralogy (id, name, code, level_ref, parent_ref) VALUES (3, 'BLé bou/ caiéoui', 2, 72, 2);
INSERT INTO mineralogy (id, name, code, level_ref, parent_ref) VALUES (4, 'CLé bou/ caiéoui', 3, 73, 3);
INSERT INTO mineralogy (id, name, code, level_ref, parent_ref) VALUES (5, 'DLé bou/ caiéoui', 4, 74, 4);
SELECT ok( 1 = (SELECT unit_class_ref FROM mineralogy WHERE id = 2), 'Class reference of mineralogic unit N°2: 1');
SELECT ok( 'leboucaieoui' = (SELECT unit_class_indexed FROM mineralogy WHERE id=2),'Class name of mineralogic unit N°2: leboucaieoui');
SELECT ok( 2 = (SELECT unit_division_ref FROM mineralogy WHERE id = 2), 'Division reference of mineralogic unit N°2: 2');
SELECT ok( 'aleboucaieoui' = (SELECT unit_division_indexed FROM mineralogy WHERE id=2),'Division name of mineralogic unit N°2: aleboucaieoui');
SELECT ok( 3 = (SELECT unit_family_ref FROM mineralogy WHERE id = 3), 'Family reference of mineralogic unit N°3: 3');
SELECT ok( 'bleboucaieoui' = (SELECT unit_family_indexed FROM mineralogy WHERE id=3),'Family name of mineralogic unit N°3: bleboucaieoui');
SELECT ok( 4 = (SELECT unit_group_ref FROM mineralogy WHERE id = 4), 'Group reference of mineralogic unit N°4: 4');
SELECT ok( 'cleboucaieoui' = (SELECT unit_group_indexed FROM mineralogy WHERE id=4),'Group name of mineralogic unit N°4: cleboucaieoui');
SELECT ok( 5 = (SELECT unit_variety_ref FROM mineralogy WHERE id = 5), 'Variety reference of mineralogic unit N°5: 5');
SELECT ok( 'dleboucaieoui' = (SELECT unit_variety_indexed FROM mineralogy WHERE id=5),'Variety name of mineralogic unit N°5: dleboucaieoui');

SELECT diag('Copy Hierarchy from parent Trigger: Taxa');


INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (2, 'AMéàleis Gùbularis&', 2, 1);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (3, 'BMéàleis Gùbularis&', 3, 2);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (4, 'CMéàleis Gùbularis&', 4, 3);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (5, 'DMéàleis Gùbularis&', 5, 4);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (6, 'EMéàleis Gùbularis&', 6, 5);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (7, 'FMéàleis Gùbularis&', 7, 6);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (8, 'GMéàleis Gùbularis&', 8, 7);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (9, 'HMéàleis Gùbularis&', 9, 8);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (10, 'IMéàleis Gùbularis&', 10, 9);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (11, 'JMéàleis Gùbularis&', 11, 10);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (12, 'KMéàleis Gùbularis&', 12, 11);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (13, 'LMéàleis Gùbularis&', 13, 12);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (14, 'MMéàleis Gùbularis&', 14, 13);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (15, 'NMéàleis Gùbularis&', 15, 14);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (16, 'OMéàleis Gùbularis&', 16, 15);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (17, 'PMéàleis Gùbularis&', 17, 16);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (18, 'QMéàleis Gùbularis&', 18, 17);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (19, 'RMéàleis Gùbularis&', 19, 18);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (20, 'SMéàleis Gùbularis&', 20, 19);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (21, 'TMéàleis Gùbularis&', 21, 20);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (22, 'UMéàleis Gùbularis&', 22, 21);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (23, 'VMéàleis Gùbularis&', 23, 22);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (24, 'WMéàleis Gùbularis&', 24, 23);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (25, 'XMéàleis Gùbularis&', 25, 24);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (26, 'YMéàleis Gùbularis&', 26, 25);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (27, 'ZMéàleis Gùbularis&', 27, 26);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (28, 'AAMéàleis Gùbularis&', 28, 27);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (29, 'BBMéàleis Gùbularis&', 29, 28);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (30, 'CCMéàleis Gùbularis&', 30, 29);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (31, 'DDMéàleis Gùbularis&', 31, 30);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (32, 'EEMéàleis Gùbularis&', 32, 31);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (33, 'FFMéàleis Gùbularis&', 33, 32);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (34, 'GGMéàleis Gùbularis&', 34, 33);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (35, 'HHMéàleis Gùbularis&', 35, 34);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (36, 'IIMéàleis Gùbularis&', 36, 35);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (37, 'JJMéàleis Gùbularis&', 37, 36);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (38, 'KKMéàleis Gùbularis&', 38, 37);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (39, 'LLMéàleis Gùbularis&', 39, 38);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (40, 'MMMéàleis Gùbularis&', 40, 39);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (41, 'NNMéàleis Gùbularis&', 41, 40);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (42, 'OOMéàleis Gùbularis&', 42, 41);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (43, 'PPMéàleis Gùbularis&', 43, 42);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (44, 'QQMéàleis Gùbularis&', 44, 43);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (45, 'RRMéàleis Gùbularis&', 45, 44);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (46, 'SSMéàleis Gùbularis&', 46, 45);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (47, 'TTMéàleis Gùbularis&', 47, 46);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (48, 'UUMéàleis Gùbularis&', 48, 47);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (49, 'VVMéàleis Gùbularis&', 49, 48);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (50, 'WWMéàleis Gùbularis&', 50, 49);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (51, 'XXMéàleis Gùbularis&', 51, 50);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (52, 'YYMéàleis Gùbularis&', 52, 51);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (53, 'ZZMéàleis Gùbularis&', 53, 52);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (54, 'ABCMéàleis Gùbularis&', 54, 53);

SELECT ok( 1 = (SELECT domain_ref FROM taxa WHERE id = 2), 'Domain reference of taxonomic unit N°2: 1');
SELECT ok( 'mealeisgubularis' = (SELECT domain_indexed FROM taxa WHERE id=2),'Domain name of taxonomic unit N°2: mealeisgubularis');
SELECT ok( 2 = (SELECT kingdom_ref FROM taxa WHERE id = 2), 'Kingdom reference of taxonomic unit N°2: 2');
SELECT ok( 'amealeisgubularis' = (SELECT kingdom_indexed FROM taxa WHERE id=2),'Kingdom name of taxonomic unit N°2: amealeisgubularis');
SELECT ok( 3 = (SELECT super_phylum_ref FROM taxa WHERE id = 3), 'Super phylum reference of taxonomic unit N°3: 3');
SELECT ok( 'bmealeisgubularis' = (SELECT super_phylum_indexed FROM taxa WHERE id=3),'Super phylum name of taxonomic unit N°3: bmealeisgubularis');
SELECT ok( 4 = (SELECT phylum_ref FROM taxa WHERE id = 4), 'Phylum reference of taxonomic unit N°4: 4');
SELECT ok( 'cmealeisgubularis' = (SELECT phylum_indexed FROM taxa WHERE id=4),'Phylum name of taxonomic unit N°4: cmealeisgubularis');
SELECT ok( 5 = (SELECT sub_phylum_ref FROM taxa WHERE id = 5), 'Sub phylum reference of taxonomic unit N°5: 5');
SELECT ok( 'dmealeisgubularis' = (SELECT sub_phylum_indexed FROM taxa WHERE id=5),'Sub phylum name of taxonomic unit N°5: dmealeisgubularis');
SELECT ok( 6 = (SELECT infra_phylum_ref FROM taxa WHERE id = 6), 'Infra phylum reference of taxonomic unit N°6: 6');
SELECT ok( 'emealeisgubularis' = (SELECT infra_phylum_indexed FROM taxa WHERE id=6),'Infra phylum name of taxonomic unit N°6: emealeisgubularis');
SELECT ok( 7 = (SELECT super_cohort_botany_ref FROM taxa WHERE id = 7), 'Botanical super cohort reference of taxonomic unit N°7: 7');
SELECT ok( 'fmealeisgubularis' = (SELECT super_cohort_botany_indexed FROM taxa WHERE id=7),'Botanical super cohort name of taxonomic unit N°7: fmealeisgubularis');
SELECT ok( 8 = (SELECT cohort_botany_ref FROM taxa WHERE id = 8), 'Botanical cohort reference of taxonomic unit N°8: 8');
SELECT ok( 'gmealeisgubularis' = (SELECT cohort_botany_indexed FROM taxa WHERE id=8),'Botanical cohort name of taxonomic unit N°8: gmealeisgubularis');
SELECT ok( 9 = (SELECT sub_cohort_botany_ref FROM taxa WHERE id = 9), 'Botanical sub cohort reference of taxonomic unit N°9: 9');
SELECT ok( 'hmealeisgubularis' = (SELECT sub_cohort_botany_indexed FROM taxa WHERE id=9),'Botanical sub cohort name of taxonomic unit N°9: hmealeisgubularis');
SELECT ok( 10 = (SELECT infra_cohort_botany_ref FROM taxa WHERE id = 10), 'Botanical infra cohort reference of taxonomic unit N°10: 10');
SELECT ok( 'imealeisgubularis' = (SELECT infra_cohort_botany_indexed FROM taxa WHERE id=10),'Botanical infra cohort name of taxonomic unit N°10: imealeisgubularis');
SELECT ok( 11 = (SELECT super_class_ref FROM taxa WHERE id = 11), 'Super class reference of taxonomic unit N°11: 11');
SELECT ok( 'jmealeisgubularis' = (SELECT super_class_indexed FROM taxa WHERE id=11),'Super class name of taxonomic unit N°11: jmealeisgubularis');
SELECT ok( 12 = (SELECT class_ref FROM taxa WHERE id = 12), 'Class reference of taxonomic unit N°12: 12');
SELECT ok( 'kmealeisgubularis' = (SELECT class_indexed FROM taxa WHERE id=12),'Class name of taxonomic unit N°12: kmealeisgubularis');
SELECT ok( 13 = (SELECT sub_class_ref FROM taxa WHERE id = 13), 'Sub class reference of taxonomic unit N°13: 13');
SELECT ok( 'lmealeisgubularis' = (SELECT sub_class_indexed FROM taxa WHERE id=13),'Sub class name of taxonomic unit N°13: lmealeisgubularis');
SELECT ok( 14 = (SELECT infra_class_ref FROM taxa WHERE id = 14), 'Infra class reference of taxonomic unit N°14: 14');
SELECT ok( 'mmealeisgubularis' = (SELECT infra_class_indexed FROM taxa WHERE id=14),'Infra class name of taxonomic unit N°14: mmealeisgubularis');
SELECT ok( 15 = (SELECT super_division_ref FROM taxa WHERE id = 15), 'Super division reference of taxonomic unit N°15: 15');
SELECT ok( 'nmealeisgubularis' = (SELECT super_division_indexed FROM taxa WHERE id=15),'Super division name of taxonomic unit N°15: nmealeisgubularis');
SELECT ok( 16 = (SELECT division_ref FROM taxa WHERE id = 16), 'Division reference of taxonomic unit N°16: 16');
SELECT ok( 'omealeisgubularis' = (SELECT division_indexed FROM taxa WHERE id=16),'Division name of taxonomic unit N°16: omealeisgubularis');
SELECT ok( 17 = (SELECT sub_division_ref FROM taxa WHERE id = 17), 'Sub division reference of taxonomic unit N°17: 17');
SELECT ok( 'pmealeisgubularis' = (SELECT sub_division_indexed FROM taxa WHERE id=17),'Sub division name of taxonomic unit N°17: pmealeisgubularis');
SELECT ok( 18 = (SELECT infra_division_ref FROM taxa WHERE id = 18), 'Infra division reference of taxonomic unit N°18: 18');
SELECT ok( 'qmealeisgubularis' = (SELECT infra_division_indexed FROM taxa WHERE id=18),'Infra division name of taxonomic unit N°18: qmealeisgubularis');
SELECT ok( 19 = (SELECT super_legion_ref FROM taxa WHERE id = 19), 'Super legion reference of taxonomic unit N°19: 19');
SELECT ok( 'rmealeisgubularis' = (SELECT super_legion_indexed FROM taxa WHERE id=19),'Super legion name of taxonomic unit N°19: rmealeisgubularis');
SELECT ok( 20 = (SELECT legion_ref FROM taxa WHERE id = 20), 'Legion reference of taxonomic unit N°20: 20');
SELECT ok( 'smealeisgubularis' = (SELECT legion_indexed FROM taxa WHERE id=20),'Legion name of taxonomic unit N°20: smealeisgubularis');
SELECT ok( 21 = (SELECT sub_legion_ref FROM taxa WHERE id = 21), 'Sub legion reference of taxonomic unit N°21: 21');
SELECT ok( 'tmealeisgubularis' = (SELECT sub_legion_indexed FROM taxa WHERE id=21),'Sub legion name of taxonomic unit N°21: tmealeisgubularis');
SELECT ok( 22 = (SELECT infra_legion_ref FROM taxa WHERE id = 22), 'Infra legion reference of taxonomic unit N°22: 22');
SELECT ok( 'umealeisgubularis' = (SELECT infra_legion_indexed FROM taxa WHERE id=22),'Infra legion name of taxonomic unit N°22: umealeisgubularis');
SELECT ok( 23 = (SELECT super_cohort_zoology_ref FROM taxa WHERE id = 23), 'Zoological super cohort reference of taxonomic unit N°23: 23');
SELECT ok( 'vmealeisgubularis' = (SELECT super_cohort_zoology_indexed FROM taxa WHERE id=23),'Zoological super cohort name of taxonomic unit N°23: vmealeisgubularis');
SELECT ok( 24 = (SELECT cohort_zoology_ref FROM taxa WHERE id = 24), 'Zoological cohort reference of taxonomic unit N°24: 24');
SELECT ok( 'wmealeisgubularis' = (SELECT cohort_zoology_indexed FROM taxa WHERE id=24),'Zoological cohort name of taxonomic unit N°24: wmealeisgubularis');
SELECT ok( 25 = (SELECT sub_cohort_zoology_ref FROM taxa WHERE id = 25), 'Zoological sub cohort reference of taxonomic unit N°25: 25');
SELECT ok( 'xmealeisgubularis' = (SELECT sub_cohort_zoology_indexed FROM taxa WHERE id=25),'Zoological sub cohort name of taxonomic unit N°25: xmealeisgubularis');
SELECT ok( 26 = (SELECT infra_cohort_zoology_ref FROM taxa WHERE id = 26), 'Zoological infra cohort reference of taxonomic unit N°26: 26');
SELECT ok( 'ymealeisgubularis' = (SELECT infra_cohort_zoology_indexed FROM taxa WHERE id=26),'Zoological infra cohort name of taxonomic unit N°26: ymealeisgubularis');
SELECT ok( 27 = (SELECT super_order_ref FROM taxa WHERE id = 27), 'Super order reference of taxonomic unit N°27: 27');
SELECT ok( 'zmealeisgubularis' = (SELECT super_order_indexed FROM taxa WHERE id=27),'Super order name of taxonomic unit N°27: zmealeisgubularis');
SELECT ok( 28 = (SELECT order_ref FROM taxa WHERE id = 28), 'Order reference of taxonomic unit N°28: 28');
SELECT ok( 'aamealeisgubularis' = (SELECT order_indexed FROM taxa WHERE id=28),'Order name of taxonomic unit N°28: aamealeisgubularis');
SELECT ok( 29 = (SELECT sub_order_ref FROM taxa WHERE id = 29), 'Sub order reference of taxonomic unit N°29: 29');
SELECT ok( 'bbmealeisgubularis' = (SELECT sub_order_indexed FROM taxa WHERE id=29),'Sub order name of taxonomic unit N°29: bbmealeisgubularis');
SELECT ok( 30 = (SELECT infra_order_ref FROM taxa WHERE id = 30), 'Infra order reference of taxonomic unit N°30: 30');
SELECT ok( 'ccmealeisgubularis' = (SELECT infra_order_indexed FROM taxa WHERE id=30),'Infra order name of taxonomic unit N°30: ccmealeisgubularis');
SELECT ok( 31 = (SELECT section_zoology_ref FROM taxa WHERE id = 31), 'Zoological section reference of taxonomic unit N°31: 31');
SELECT ok( 'ddmealeisgubularis' = (SELECT section_zoology_indexed FROM taxa WHERE id=31),'Zoological section name of taxonomic unit N°31: ddmealeisgubularis');
SELECT ok( 32 = (SELECT sub_section_zoology_ref FROM taxa WHERE id = 32), 'Zoological sub section reference of taxonomic unit N°32: 32');
SELECT ok( 'eemealeisgubularis' = (SELECT sub_section_zoology_indexed FROM taxa WHERE id=32),'Zoological sub section name of taxonomic unit N°32: eemealeisgubularis');
SELECT ok( 33 = (SELECT super_family_ref FROM taxa WHERE id = 33), 'Super family reference of taxonomic unit N°33: 33');
SELECT ok( 'ffmealeisgubularis' = (SELECT super_family_indexed FROM taxa WHERE id=33),'Super family name of taxonomic unit N°33: ffmealeisgubularis');
SELECT ok( 34 = (SELECT family_ref FROM taxa WHERE id = 34), 'Family reference of taxonomic unit N°34: 34');
SELECT ok( 'ggmealeisgubularis' = (SELECT family_indexed FROM taxa WHERE id=34),'Family name of taxonomic unit N°34: ggmealeisgubularis');
SELECT ok( 35 = (SELECT sub_family_ref FROM taxa WHERE id = 35), 'Sub family reference of taxonomic unit N°35: 35');
SELECT ok( 'hhmealeisgubularis' = (SELECT sub_family_indexed FROM taxa WHERE id=35),'Sub family name of taxonomic unit N°35: hhmealeisgubularis');
SELECT ok( 36 = (SELECT infra_family_ref FROM taxa WHERE id = 36), 'Infra family reference of taxonomic unit N°36: 36');
SELECT ok( 'iimealeisgubularis' = (SELECT infra_family_indexed FROM taxa WHERE id=36),'Infra family name of taxonomic unit N°36: iimealeisgubularis');
SELECT ok( 37 = (SELECT super_tribe_ref FROM taxa WHERE id = 37), 'Super tribe reference of taxonomic unit N°37: 37');
SELECT ok( 'jjmealeisgubularis' = (SELECT super_tribe_indexed FROM taxa WHERE id=37),'Super tribe name of taxonomic unit N°37: jjmealeisgubularis');
SELECT ok( 38 = (SELECT tribe_ref FROM taxa WHERE id = 38), 'Tribe reference of taxonomic unit N°38: 38');
SELECT ok( 'kkmealeisgubularis' = (SELECT tribe_indexed FROM taxa WHERE id=38),'Tribe name of taxonomic unit N°38: kkmealeisgubularis');
SELECT ok( 39 = (SELECT sub_tribe_ref FROM taxa WHERE id = 39), 'Sub tribe reference of taxonomic unit N°39: 39');
SELECT ok( 'llmealeisgubularis' = (SELECT sub_tribe_indexed FROM taxa WHERE id=39),'Sub tribe name of taxonomic unit N°39: llmealeisgubularis');
SELECT ok( 40 = (SELECT infra_tribe_ref FROM taxa WHERE id = 40), 'Infra tribe reference of taxonomic unit N°40: 40');
SELECT ok( 'mmmealeisgubularis' = (SELECT infra_tribe_indexed FROM taxa WHERE id=40),'Infra tribe name of taxonomic unit N°40: mmmealeisgubularis');
SELECT ok( 41 = (SELECT genus_ref FROM taxa WHERE id = 41), 'Genus reference of taxonomic unit N°41: 41');
SELECT ok( 'nnmealeisgubularis' = (SELECT genus_indexed FROM taxa WHERE id=41),'Genus name of taxonomic unit N°41: nnmealeisgubularis');
SELECT ok( 42 = (SELECT sub_genus_ref FROM taxa WHERE id = 42), 'Sub genus reference of taxonomic unit N°42: 42');
SELECT ok( 'oomealeisgubularis' = (SELECT sub_genus_indexed FROM taxa WHERE id=42),'Sub genus name of taxonomic unit N°42: oomealeisgubularis');
SELECT ok( 43 = (SELECT section_botany_ref FROM taxa WHERE id = 43), 'Botanical section reference of taxonomic unit N°43: 43');
SELECT ok( 'ppmealeisgubularis' = (SELECT section_botany_indexed FROM taxa WHERE id=43),'Botanical section name of taxonomic unit N°43: ppmealeisgubularis');
SELECT ok( 44 = (SELECT sub_section_botany_ref FROM taxa WHERE id = 44), 'Botanical sub section reference of taxonomic unit N°44: 44');
SELECT ok( 'qqmealeisgubularis' = (SELECT sub_section_botany_indexed FROM taxa WHERE id=44),'Botanical sub section name of taxonomic unit N°44: qqmealeisgubularis');
SELECT ok( 45 = (SELECT serie_ref FROM taxa WHERE id = 45), 'Serie reference of taxonomic unit N°45: 45');
SELECT ok( 'rrmealeisgubularis' = (SELECT serie_indexed FROM taxa WHERE id=45),'Serie name of taxonomic unit N°45: rrmealeisgubularis');
SELECT ok( 46 = (SELECT sub_serie_ref FROM taxa WHERE id = 46), 'Sub serie reference of taxonomic unit N°46: 46');
SELECT ok( 'ssmealeisgubularis' = (SELECT sub_serie_indexed FROM taxa WHERE id=46),'Sub serie name of taxonomic unit N°46: ssmealeisgubularis');
SELECT ok( 47 = (SELECT super_species_ref FROM taxa WHERE id = 47), 'Super species reference of taxonomic unit N°47: 47');
SELECT ok( 'ttmealeisgubularis' = (SELECT super_species_indexed FROM taxa WHERE id=47),'Super species name of taxonomic unit N°47: ttmealeisgubularis');
SELECT ok( 48 = (SELECT species_ref FROM taxa WHERE id = 48), 'Species reference of taxonomic unit N°48: 48');
SELECT ok( 'uumealeisgubularis' = (SELECT species_indexed FROM taxa WHERE id=48),'Species name of taxonomic unit N°48: uumealeisgubularis');
SELECT ok( 49 = (SELECT sub_species_ref FROM taxa WHERE id = 49), 'Sub species reference of taxonomic unit N°49: 49');
SELECT ok( 'vvmealeisgubularis' = (SELECT sub_species_indexed FROM taxa WHERE id=49),'Sub species name of taxonomic unit N°49: vvmealeisgubularis');
SELECT ok( 50 = (SELECT variety_ref FROM taxa WHERE id = 50), 'Variety reference of taxonomic unit N°50: 50');
SELECT ok( 'wwmealeisgubularis' = (SELECT variety_indexed FROM taxa WHERE id=50),'Variety name of taxonomic unit N°50: wwmealeisgubularis');
SELECT ok( 51 = (SELECT sub_variety_ref FROM taxa WHERE id = 51), 'Sub variety reference of taxonomic unit N°51: 51');
SELECT ok( 'xxmealeisgubularis' = (SELECT sub_variety_indexed FROM taxa WHERE id=51),'Sub variety name of taxonomic unit N°51: xxmealeisgubularis');
SELECT ok( 52 = (SELECT form_ref FROM taxa WHERE id = 52), 'Form reference of taxonomic unit N°52: 52');
SELECT ok( 'yymealeisgubularis' = (SELECT form_indexed FROM taxa WHERE id=52),'Form name of taxonomic unit N°52: yymealeisgubularis');
SELECT ok( 53 = (SELECT sub_form_ref FROM taxa WHERE id = 53), 'Sub form reference of taxonomic unit N°53: 53');
SELECT ok( 'zzmealeisgubularis' = (SELECT sub_form_indexed FROM taxa WHERE id=53),'Sub form name of taxonomic unit N°53: zzmealeisgubularis');
SELECT ok( 54 = (SELECT abberans_ref FROM taxa WHERE id = 54), 'Abberans reference of taxonomic unit N°54: 54');
SELECT ok( 'abcmealeisgubularis' = (SELECT abberans_indexed FROM taxa WHERE id=54),'Abberans name of taxonomic unit N°54: abcmealeisgubularis');

SELECT * FROM finish();
ROLLBACK;
