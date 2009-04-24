\unset ECHO
\i unit_launch.sql
SELECT plan(307);

SELECT diag('Chronostratigraphy level/parent update tests');

INSERT INTO chronostratigraphy (id, name, level_ref ) VALUES (1,'ÉLo Wÿorléds', 55);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'ÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (3, 'KÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'BÉLoWÿ', 58, 2);

SELECT ok('/' = (SELECT path FROM chronostratigraphy WHERE id = 1), 'Path of unit 1: /');
SELECT ok('/1/' = (SELECT path FROM chronostratigraphy WHERE id = 2), 'Path of unit 2: /1/');
SELECT ok('/1/' = (SELECT path FROM chronostratigraphy WHERE id = 3), 'Path of unit 3: /1/');
SELECT ok('/1/2/' = (SELECT path FROM chronostratigraphy WHERE id = 4), 'Path of unit 4: /1/2/');

SELECT lives_ok('UPDATE chronostratigraphy SET level_ref = 57, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 56 (era) to 57 (sub era) and attached to a new era - unit 3 -> allowed because direct children (unit 4 of system level) is attachable to a sub-era');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 2), 'New era_ref of unit 2 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 2), 'New era_indexed of unit 2 : kelowy');
SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 2), 'New sub_era_ref of unit 2 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 2), 'New sub_era_indexed of unit 2 : elowy');
SELECT ok('/1/3/' = (SELECT path FROM chronostratigraphy WHERE id = 2), 'Path of unit 2: /1/3/');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 4), 'New era_ref of unit 4 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 4), 'New era_indexed of unit 4 : kelowy');
SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 4), 'New sub_era_ref of unit 4 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 4), 'New sub_era_indexed of unit 4 : elowy');
SELECT ok(4 = (SELECT system_ref FROM chronostratigraphy WHERE id = 4), 'New system_ref of unit 4 : 4');
SELECT ok('belowy' = (SELECT system_indexed FROM chronostratigraphy WHERE id = 4), 'New system_indexed of unit 4 : belowy');
SELECT ok(0 = (SELECT serie_ref FROM chronostratigraphy WHERE id = 4), 'New serie_ref of unit 4 : 0');
SELECT ok('' = (SELECT serie_indexed FROM chronostratigraphy WHERE id = 4), 'New serie_indexed of unit 4 : ''''');
SELECT ok('/1/3/2/' = (SELECT path FROM chronostratigraphy WHERE id = 4), 'Path of unit 4: /1/3/2/');

SELECT throws_ok('UPDATE chronostratigraphy SET level_ref = 58 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Lithostratigraphy level/parent update tests');

INSERT INTO lithostratigraphy (id,name, level_ref) VALUES (1, 'Méalo-nÿeø@ß€', 64);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (2, 'Méalo-nÿeø@ß€A', 65, 1);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (3, 'Méalo-nÿeø@ß€B', 65, 1);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (4, 'Méalo-nÿeø@ß€C', 66, 3);

SELECT ok('/' = (SELECT path FROM lithostratigraphy WHERE id = 1), 'Path of unit 1: /');
SELECT ok('/1/' = (SELECT path FROM lithostratigraphy WHERE id = 2), 'Path of unit 2: /1/');
SELECT ok('/1/' = (SELECT path FROM lithostratigraphy WHERE id = 3), 'Path of unit 3: /1/');
SELECT ok('/1/3/' = (SELECT path FROM lithostratigraphy WHERE id = 4), 'Path of unit 4: /1/3/');

SELECT lives_ok('UPDATE lithostratigraphy SET level_ref = 66, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 65 (formation) to 66 (member) and attached to a new formation - unit 3 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 2), 'New group_ref of unit 2: 1');
SELECT ok('mealonyeob' = (SELECT group_indexed FROM lithostratigraphy WHERE id = 2), 'New group_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT formation_ref FROM lithostratigraphy WHERE id = 2), 'New formation_ref of unit 2: 3');
SELECT ok('mealonyeobb' = (SELECT formation_indexed FROM lithostratigraphy WHERE id = 2), 'New formation_indexed of unit 2: mealonyeobb');
SELECT ok(2 = (SELECT member_ref FROM lithostratigraphy WHERE id = 2), 'New member_ref of unit 2: 2');
SELECT ok('mealonyeoba' = (SELECT member_indexed FROM lithostratigraphy WHERE id = 2), 'New member_indexed of unit 2: mealonyeoba');
SELECT ok(0 = (SELECT layer_ref FROM lithostratigraphy WHERE id = 2), 'New layer_ref of unit 2: 0');
SELECT ok('' = (SELECT layer_indexed FROM lithostratigraphy WHERE id = 2), 'New layer_ref of unit 2: ''''');
SELECT ok('/1/3/' = (SELECT path FROM lithostratigraphy WHERE id = 2), 'Path of unit 2: /1/3/');

SELECT lives_ok('UPDATE lithostratigraphy SET level_ref = 67, parent_ref = 2 WHERE id = 4', 'Level of unit 4 have been changed from 66 (member) to 67 (layer) and attached to a new member - unit 2 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 4), 'New group_ref of unit 4: 1');
SELECT ok('mealonyeob' = (SELECT group_indexed FROM lithostratigraphy WHERE id = 4), 'New group_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT formation_ref FROM lithostratigraphy WHERE id = 4), 'New formation_ref of unit 4: 3');
SELECT ok('mealonyeobb' = (SELECT formation_indexed FROM lithostratigraphy WHERE id = 4), 'New formation_indexed of unit 4: mealonyeobb');
SELECT ok(2 = (SELECT member_ref FROM lithostratigraphy WHERE id = 4), 'New member_ref of unit 4: 2');
SELECT ok('mealonyeoba' = (SELECT member_indexed FROM lithostratigraphy WHERE id = 4), 'New member_indexed of unit 4: mealonyeoba');
SELECT ok(4 = (SELECT layer_ref FROM lithostratigraphy WHERE id = 4), 'New layer_ref of unit 4: 4');
SELECT ok('mealonyeobc' = (SELECT layer_indexed FROM lithostratigraphy WHERE id = 4), 'New layer_ref of unit 4: mealonyeobc');
SELECT ok('/1/3/2/' = (SELECT path FROM lithostratigraphy WHERE id = 4), 'Path of unit 4: /1/3/2/');

SELECT throws_ok('UPDATE lithostratigraphy SET level_ref = 65, parent_ref = 1 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Mineralogy level/parent update tests');

INSERT INTO mineralogy (id,name, code, level_ref) VALUES (1, 'Méalo-nÿeø@ß€', 'A', 70);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (2, 'Méalo-nÿeø@ß€A', 'AA', 71, 1);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (3, 'Méalo-nÿeø@ß€B', 'B', 71, 1);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (4, 'Méalo-nÿeø@ß€C', 'C', 72, 3);

SELECT ok('/' = (SELECT path FROM mineralogy WHERE id = 1), 'Path of unit 1: /');
SELECT ok('/1/' = (SELECT path FROM mineralogy WHERE id = 2), 'Path of unit 2: /1/');
SELECT ok('/1/' = (SELECT path FROM mineralogy WHERE id = 3), 'Path of unit 3: /1/');
SELECT ok('/1/3/' = (SELECT path FROM mineralogy WHERE id = 4), 'Path of unit 4: /1/3/');

SELECT lives_ok('UPDATE mineralogy SET level_ref = 72, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 71 (unit_division) to 72 (unit_family) and attached to a new unit_division - unit 3 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT unit_class_ref FROM mineralogy WHERE id = 2), 'New unit_class_ref of unit 2: 1');
SELECT ok('mealonyeob' = (SELECT unit_class_indexed FROM mineralogy WHERE id = 2), 'New unit_class_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT unit_division_ref FROM mineralogy WHERE id = 2), 'New unit_division_ref of unit 2: 3');
SELECT ok('mealonyeobb' = (SELECT unit_division_indexed FROM mineralogy WHERE id = 2), 'New unit_division_indexed of unit 2: mealonyeobb');
SELECT ok(2 = (SELECT unit_family_ref FROM mineralogy WHERE id = 2), 'New unit_family_ref of unit 2: 2');
SELECT ok('mealonyeoba' = (SELECT unit_family_indexed FROM mineralogy WHERE id = 2), 'New unit_family_indexed of unit 2: mealonyeoba');
SELECT ok(0 = (SELECT unit_group_ref FROM mineralogy WHERE id = 2), 'New unit_group_ref of unit 2: 0');
SELECT ok('' = (SELECT unit_group_indexed FROM mineralogy WHERE id = 2), 'New unit_group_ref of unit 2: ''''');
SELECT ok('/1/3/' = (SELECT path FROM mineralogy WHERE id = 2), 'Path of unit 2: /1/3/');

SELECT lives_ok('UPDATE mineralogy SET level_ref = 73, parent_ref = 2 WHERE id = 4', 'Level of unit 4 have been changed from 72 (unit_family) to 73 (unit_group) and attached to a new unit_family - unit 2 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT unit_class_ref FROM mineralogy WHERE id = 4), 'New unit_class_ref of unit 4: 1');
SELECT ok('mealonyeob' = (SELECT unit_class_indexed FROM mineralogy WHERE id = 4), 'New unit_class_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT unit_division_ref FROM mineralogy WHERE id = 4), 'New unit_division_ref of unit 4: 3');
SELECT ok('mealonyeobb' = (SELECT unit_division_indexed FROM mineralogy WHERE id = 4), 'New unit_division_indexed of unit 4: mealonyeobb');
SELECT ok(2 = (SELECT unit_family_ref FROM mineralogy WHERE id = 4), 'New unit_family_ref of unit 4: 2');
SELECT ok('mealonyeoba' = (SELECT unit_family_indexed FROM mineralogy WHERE id = 4), 'New unit_family_indexed of unit 4: mealonyeoba');
SELECT ok(4 = (SELECT unit_group_ref FROM mineralogy WHERE id = 4), 'New unit_group_ref of unit 4: 4');
SELECT ok('mealonyeobc' = (SELECT unit_group_indexed FROM mineralogy WHERE id = 4), 'New unit_group_ref of unit 4: mealonyeobc');
SELECT ok('/1/3/2/' = (SELECT path FROM mineralogy WHERE id = 4), 'Path of unit 4: /1/3/2/');

SELECT throws_ok('UPDATE mineralogy SET level_ref = 71, parent_ref = 1 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Taxonomy level/parent update tests');

INSERT INTO taxonomy (id, name, level_ref) VALUES (1, 'TOP', 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (2, 'A', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (3, 'AA', 4, 2);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (4, 'AAA', 12, 3);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (5, 'AAAA', 28, 4);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (6, 'AAAAA', 34, 5);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (7, 'AAAAAA', 41, 6);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (8, 'AAAAAAA', 42, 7);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (9, 'AAAAAAAA', 48, 8);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (10, 'AAAAAAAAA', 49, 9);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (11, 'B', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (12, 'C', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (13, 'CA', 4, 12);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (14, 'D', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (15, 'DA', 4, 14);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (16, 'DAA', 12, 15);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (17, 'E', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (18, 'EA', 4, 17);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (19, 'EAA', 12, 18);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (20, 'EAAA', 28, 19);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (21, 'KINGDOM', 2, 1);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (22, 'PHYLUM', 4, 21);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (23, 'CLASS', 12, 22);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (24, 'ORDER', 28, 23);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (25, 'FAMILY', 34, 24);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (26, 'GENUS', 41, 25);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (27, 'SUBGENUS', 42, 26);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (28, 'SECTIONBOTA', 43, 27);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (29, 'SUBSECTIONBOTA', 44, 28);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (30, 'SERIE', 45, 29);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (31, 'SUPERSPECIES', 47, 30);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (32, 'SPECIES', 48, 31);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (33, 'GENUSDUB', 41, 25);

SELECT ok('/' = (SELECT path FROM taxonomy WHERE id = 1), 'Path of unit 1: /');
SELECT ok('/1/' = (SELECT path FROM taxonomy WHERE id = 2), 'Path of unit 2: /1/');
SELECT ok('/1/2/' = (SELECT path FROM taxonomy WHERE id = 3), 'Path of unit 3: /1/2/');
SELECT ok('/1/2/3/' = (SELECT path FROM taxonomy WHERE id = 4), 'Path of unit 4: /1/2/3/');
SELECT ok('/1/2/3/4/' = (SELECT path FROM taxonomy WHERE id = 5), 'Path of unit 5: /1/2/3/4/');
SELECT ok('/1/2/3/4/5/' = (SELECT path FROM taxonomy WHERE id = 6), 'Path of unit 6: /1/2/3/4/5/');
SELECT ok('/1/2/3/4/5/6/' = (SELECT path FROM taxonomy WHERE id = 7), 'Path of unit 7: /1/2/3/4/5/6/');
SELECT ok('/1/2/3/4/5/6/7/' = (SELECT path FROM taxonomy WHERE id = 8), 'Path of unit 8: /1/2/3/4/5/6/7/');
SELECT ok('/1/2/3/4/5/6/7/8/' = (SELECT path FROM taxonomy WHERE id = 9), 'Path of unit 9: /1/2/3/4/5/6/7/8/');
SELECT ok('/1/2/3/4/5/6/7/8/9/' = (SELECT path FROM taxonomy WHERE id = 10), 'Path of unit 10: /1/2/3/4/5/6/7/8/9/');

SELECT lives_ok('UPDATE taxonomy SET parent_ref = 11 WHERE id = 3', 'Unit 3 (phylum) moved from parent unit 2 to parent unit 11');

SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 3), 'New kingdom_ref of unit 3: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 3), 'New kingdom_indexed of unit 3: b');
SELECT ok(3 = (SELECT phylum_ref FROM taxonomy WHERE id = 3), 'New phylum_ref of unit 3: 3');
SELECT ok('aa' = (SELECT phylum_indexed FROM taxonomy WHERE id = 3), 'New phylum_indexed of unit 3: aa');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 4), 'New kingdom_ref of unit 4: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 4), 'New kingdom_indexed of unit 4: b');
SELECT ok(3 = (SELECT phylum_ref FROM taxonomy WHERE id = 4), 'New phylum_ref of unit 4: 3');
SELECT ok('aa' = (SELECT phylum_indexed FROM taxonomy WHERE id = 4), 'New phylum_indexed of unit 4: aa');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 5), 'New kingdom_ref of unit 5: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 5), 'New kingdom_indexed of unit 5: b');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 6), 'New kingdom_ref of unit 6: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 6), 'New kingdom_indexed of unit 6: b');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 7), 'New kingdom_ref of unit 7: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 7), 'New kingdom_indexed of unit 7: b');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 8), 'New kingdom_ref of unit 8: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 8), 'New kingdom_indexed of unit 8: b');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 9), 'New kingdom_ref of unit 9: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 9), 'New kingdom_indexed of unit 9: b');
SELECT ok(11 = (SELECT kingdom_ref FROM taxonomy WHERE id = 10), 'New kingdom_ref of unit 10: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 10), 'New kingdom_indexed of unit 10: b');
SELECT ok(3 = (SELECT phylum_ref FROM taxonomy WHERE id = 10), 'New phylum_ref of unit 10: 3');
SELECT ok('aa' = (SELECT phylum_indexed FROM taxonomy WHERE id = 10), 'New phylum_indexed of unit 10: aa');

SELECT ok('/1/' = (SELECT path FROM taxonomy WHERE id = 11), 'Path of unit 11: /1/');
SELECT ok('/1/11/' = (SELECT path FROM taxonomy WHERE id = 3), 'Path of unit 3: /1/11/');
SELECT ok('/1/11/3/' = (SELECT path FROM taxonomy WHERE id = 4), 'Path of unit 4: /1/11/3/');
SELECT ok('/1/11/3/4/' = (SELECT path FROM taxonomy WHERE id = 5), 'Path of unit 5: /1/11/3/4/');
SELECT ok('/1/11/3/4/5/' = (SELECT path FROM taxonomy WHERE id = 6), 'Path of unit 6: /1/11/3/4/5/');
SELECT ok('/1/11/3/4/5/6/' = (SELECT path FROM taxonomy WHERE id = 7), 'Path of unit 7: /1/11/3/4/5/6/');
SELECT ok('/1/11/3/4/5/6/7/' = (SELECT path FROM taxonomy WHERE id = 8), 'Path of unit 8: /1/11/3/4/5/6/7/');
SELECT ok('/1/11/3/4/5/6/7/8/' = (SELECT path FROM taxonomy WHERE id = 9), 'Path of unit 9: /1/11/3/4/5/6/7/8/');
SELECT ok('/1/11/3/4/5/6/7/8/9/' = (SELECT path FROM taxonomy WHERE id = 10), 'Path of unit 10: /1/11/3/4/5/6/7/8/9/');

SELECT lives_ok('UPDATE taxonomy SET parent_ref = 13 WHERE id = 4', 'Unit 4 (class) moved from parent unit 3 to parent unit 13');

SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 4), 'New kingdom_ref of unit 4: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 4), 'New kingdom_indexed of unit 4: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 4), 'New phylum_ref of unit 4: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 4), 'New phylum_indexed of unit 4: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 5), 'New kingdom_ref of unit 5: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 5), 'New kingdom_indexed of unit 5: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 5), 'New phylum_ref of unit 5: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 5), 'New phylum_indexed of unit 5: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 6), 'New kingdom_ref of unit 6: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 6), 'New kingdom_indexed of unit 6: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 6), 'New phylum_ref of unit 6: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 6), 'New phylum_indexed of unit 6: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 7), 'New kingdom_ref of unit 7: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 7), 'New kingdom_indexed of unit 7: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 7), 'New phylum_ref of unit 7: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 7), 'New phylum_indexed of unit 7: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 8), 'New kingdom_ref of unit 8: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 8), 'New kingdom_indexed of unit 8: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 8), 'New phylum_ref of unit 8: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 8), 'New phylum_indexed of unit 8: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 9), 'New kingdom_ref of unit 9: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 9), 'New kingdom_indexed of unit 9: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 9), 'New phylum_ref of unit 9: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 9), 'New phylum_indexed of unit 9: ca');
SELECT ok(12 = (SELECT kingdom_ref FROM taxonomy WHERE id = 10), 'New kingdom_ref of unit 10: 12');
SELECT ok('c' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 10), 'New kingdom_indexed of unit 10: c');
SELECT ok(13 = (SELECT phylum_ref FROM taxonomy WHERE id = 10), 'New phylum_ref of unit 10: 13');
SELECT ok('ca' = (SELECT phylum_indexed FROM taxonomy WHERE id = 10), 'New phylum_indexed of unit 10: ca');

SELECT lives_ok('UPDATE taxonomy SET parent_ref = 16 WHERE id = 5', 'Unit 5 moved from parent unit 4 to parent unit 16');

SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 5), 'New kingdom_ref of unit 5: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 5), 'New kingdom_indexed of unit 5: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 5), 'New phylum_ref of unit 5: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 5), 'New phylum_indexed of unit 5: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 5), 'New class_ref of unit 5: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 5), 'New class_indexed of unit 5: daa');
SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 6), 'New kingdom_ref of unit 6: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 6), 'New kingdom_indexed of unit 6: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 6), 'New phylum_ref of unit 6: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 6), 'New phylum_indexed of unit 6: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 6), 'New class_ref of unit 6: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 6), 'New class_indexed of unit 6: daa');
SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 7), 'New kingdom_ref of unit 7: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 7), 'New kingdom_indexed of unit 7: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 7), 'New phylum_ref of unit 7: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 7), 'New phylum_indexed of unit 7: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 7), 'New class_ref of unit 7: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 7), 'New class_indexed of unit 7: daa');
SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 8), 'New kingdom_ref of unit 8: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 8), 'New kingdom_indexed of unit 8: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 8), 'New phylum_ref of unit 8: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 8), 'New phylum_indexed of unit 8: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 8), 'New class_ref of unit 8: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 8), 'New class_indexed of unit 8: daa');
SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 9), 'New kingdom_ref of unit 9: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 9), 'New kingdom_indexed of unit 9: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 9), 'New phylum_ref of unit 9: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 9), 'New phylum_indexed of unit 9: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 9), 'New class_ref of unit 9: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 9), 'New class_indexed of unit 9: daa');
SELECT ok(14 = (SELECT kingdom_ref FROM taxonomy WHERE id = 10), 'New kingdom_ref of unit 10: 14');
SELECT ok('d' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 10), 'New kingdom_indexed of unit 10: d');
SELECT ok(15 = (SELECT phylum_ref FROM taxonomy WHERE id = 10), 'New phylum_ref of unit 10: 15');
SELECT ok('da' = (SELECT phylum_indexed FROM taxonomy WHERE id = 10), 'New phylum_indexed of unit 10: da');
SELECT ok(16 = (SELECT class_ref FROM taxonomy WHERE id = 10), 'New class_ref of unit 10: 16');
SELECT ok('daa' = (SELECT class_indexed FROM taxonomy WHERE id = 10), 'New class_indexed of unit 10: daa');

SELECT lives_ok('UPDATE taxonomy SET parent_ref = 20 WHERE id = 6', 'Unit 6 moved from parent unit 5 to parent unit 20');

SELECT ok(17 = (SELECT kingdom_ref FROM taxonomy WHERE id = 6), 'New kingdom_ref of unit 6: 17');
SELECT ok('e' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 6), 'New kingdom_indexed of unit 6: e');
SELECT ok(18 = (SELECT phylum_ref FROM taxonomy WHERE id = 6), 'New phylum_ref of unit 6: 18');
SELECT ok('ea' = (SELECT phylum_indexed FROM taxonomy WHERE id = 6), 'New phylum_indexed of unit 6: ea');
SELECT ok(19 = (SELECT class_ref FROM taxonomy WHERE id = 6), 'New class_ref of unit 6: 19');
SELECT ok('eaa' = (SELECT class_indexed FROM taxonomy WHERE id = 6), 'New class_indexed of unit 6: eaa');
SELECT ok(20 = (SELECT order_ref FROM taxonomy WHERE id = 6), 'New class_ref of unit 6: 20');
SELECT ok('eaaa' = (SELECT order_indexed FROM taxonomy WHERE id = 6), 'New class_indexed of unit 6: eaaa');
SELECT ok(8 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 9), 'New sub_genus_ref of unit 9: 8');
SELECT ok('aaaaaaa' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 9), 'New sub_genus_indexed of unit 9: aaaaaaa');

SELECT lives_ok('UPDATE taxonomy SET level_ref = 41, parent_ref = 6 WHERE id = 8', 'Unit 8 moved from parent unit 7 to parent unit 6 and changed from level 42 (sub_genus) to level 41 (genus)');

SELECT ok(8 = (SELECT genus_ref FROM taxonomy WHERE id = 8), 'New genus_ref of unit 8: 8');
SELECT ok('aaaaaaa' = (SELECT genus_indexed FROM taxonomy WHERE id = 8), 'New genus_indexed of unit 8: aaaaaaa');
SELECT ok(0 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 8), 'New sub_genus_ref of unit 9: 0');
SELECT ok('' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 8), 'New sub_genus_indexed of unit 9: ''''');
SELECT ok(8 = (SELECT genus_ref FROM taxonomy WHERE id = 9), 'New genus_ref of unit 9: 8');
SELECT ok('aaaaaaa' = (SELECT genus_indexed FROM taxonomy WHERE id = 9), 'New genus_indexed of unit 9: aaaaaaa');
SELECT ok(9 = (SELECT species_ref FROM taxonomy WHERE id = 9), 'New species_ref of unit 9: 9');
SELECT ok('aaaaaaaa' = (SELECT species_indexed FROM taxonomy WHERE id = 9), 'New species_indexed of unit 9: aaaaaaaa');

SELECT lives_ok('UPDATE taxonomy SET name = ''NEOSUBGENUS'', level_ref = 42, parent_ref = 33 WHERE id = 29', 'Unit 29 moved from parent unit 28 to parent unit 33 and changed from level 44 (sub_section_botany) to level 42 (sub_genus)');

SELECT ok(33 = (SELECT genus_ref FROM taxonomy WHERE id = 29), 'New genus_ref of unit 29: 33');
SELECT ok('genusdub' = (SELECT genus_indexed FROm taxonomy WHERE id = 29), 'New genus_indexed of unit 29: genusdub');
SELECT ok(29 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 29), 'New sub_genus_ref of unit 29: 29');
SELECT ok('neosubgenus' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 29), 'New sub_genus_indexed of unit 29: neosubgenus');
SELECT ok(0 = (SELECT section_botany_ref FROM taxonomy WHERE id = 29), 'New section_botany_ref of unit 29: 0');
SELECT ok('' = (SELECT section_botany_indexed FROM taxonomy WHERE id = 29), 'New section_botany_indexed of unit 29: ''''');
SELECT ok(0 = (SELECT sub_section_botany_ref FROM taxonomy WHERE id = 29), 'New sub_section_botany_ref of unit 29: 0');
SELECT ok('' = (SELECT sub_section_botany_indexed FROM taxonomy WHERE id = 29), 'New sub_section_botany_indexed of unit 29: ''''');
SELECT ok(33 = (SELECT genus_ref FROM taxonomy WHERE id = 30), 'New genus_ref of unit 30: 33');
SELECT ok('genusdub' = (SELECT genus_indexed FROM taxonomy WHERE id = 30), 'New genus_indexed of unit 30: genusdub');
SELECT ok(29 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 30), 'New sub_genus_ref of unit 30: 29');
SELECT ok('neosubgenus' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 30), 'New sub_genus_indexed of unit 30: neosubgenus');
SELECT ok(0 = (SELECT section_botany_ref FROM taxonomy WHERE id = 30), 'New section_botany_ref of unit 30: 0');
SELECT ok('' = (SELECT section_botany_indexed FROM taxonomy WHERE id = 30), 'New section_botany_indexed of unit 30: ''''');
SELECT ok(0 = (SELECT sub_section_botany_ref FROM taxonomy WHERE id = 30), 'New sub_section_botany_ref of unit 30: 0');
SELECT ok('' = (SELECT sub_section_botany_indexed FROM taxonomy WHERE id = 30), 'New sub_section_botany_indexed of unit 30: ''''');
SELECT ok(30 = (SELECT serie_ref FROm taxonomy WHERE id = 30), 'New serie_ref of unit 30: 30');
SELECT ok('serie' = (SELECT serie_indexed FROM taxonomy WHERE id = 30), 'New serie_indexed of unit 30: serie');
SELECT ok(0 = (SELECT species_ref FROM taxonomy WHERE id = 30), 'New species_ref of unit 30: 0');
SELECT ok('' = (SELECT species_indexed FROM taxonomy WHERE id = 30), 'New species_indexed of unit 30: ''''');
SELECT ok(33 = (SELECT genus_ref FROM taxonomy WHERE id = 30), 'New genus_ref of unit 30: 33');
SELECT ok('genusdub' = (SELECT genus_indexed FROm taxonomy WHERE id = 30), 'New genus_indexed of unit 30: genusdub');
SELECT ok(29 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 30), 'New sub_genus_ref of unit 30: 29');
SELECT ok('neosubgenus' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 30), 'New sub_genus_indexed of unit 30: neosubgenus');
SELECT ok(0 = (SELECT section_botany_ref FROM taxonomy WHERE id = 30), 'New section_botany_ref of unit 30: 0');
SELECT ok('' = (SELECT section_botany_indexed FROM taxonomy WHERE id = 30), 'New section_botany_indexed of unit 30: ''''');
SELECT ok(0 = (SELECT sub_section_botany_ref FROM taxonomy WHERE id = 30), 'New sub_section_botany_ref of unit 30: 0');
SELECT ok('' = (SELECT sub_section_botany_indexed FROM taxonomy WHERE id = 30), 'New sub_section_botany_indexed of unit 30: ''''');
SELECT ok(30 = (SELECT serie_ref FROm taxonomy WHERE id = 30), 'New serie_ref of unit 30: 30');
SELECT ok('serie' = (SELECT serie_indexed FROM taxonomy WHERE id = 30), 'New serie_indexed of unit 30: serie');
SELECT ok(0 = (SELECT super_species_ref FROM taxonomy WHERE id = 30), 'New super_species_ref of unit 30: 31');
SELECT ok('' = (SELECT super_species_indexed FROM taxonomy WHERE id = 30), 'New super_species_indexed of unit 30: superspecies');
SELECT ok(33 = (SELECT genus_ref FROM taxonomy WHERE id = 32), 'New genus_ref of unit 32: 33');
SELECT ok('genusdub' = (SELECT genus_indexed FROm taxonomy WHERE id = 32), 'New genus_indexed of unit 32: genusdub');
SELECT ok(29 = (SELECT sub_genus_ref FROM taxonomy WHERE id = 32), 'New sub_genus_ref of unit 32: 29');
SELECT ok('neosubgenus' = (SELECT sub_genus_indexed FROM taxonomy WHERE id = 32), 'New sub_genus_indexed of unit 32: neosubgenus');
SELECT ok(0 = (SELECT section_botany_ref FROM taxonomy WHERE id = 32), 'New section_botany_ref of unit 32: 0');
SELECT ok('' = (SELECT section_botany_indexed FROM taxonomy WHERE id = 32), 'New section_botany_indexed of unit 32: ''''');
SELECT ok(0 = (SELECT sub_section_botany_ref FROM taxonomy WHERE id = 32), 'New sub_section_botany_ref of unit 32: 0');
SELECT ok('' = (SELECT sub_section_botany_indexed FROM taxonomy WHERE id = 32), 'New sub_section_botany_indexed of unit 32: ''''');
SELECT ok(30 = (SELECT serie_ref FROm taxonomy WHERE id = 32), 'New serie_ref of unit 32: 30');
SELECT ok('serie' = (SELECT serie_indexed FROM taxonomy WHERE id = 32), 'New serie_indexed of unit 32: serie');
SELECT ok(31 = (SELECT super_species_ref FROM taxonomy WHERE id = 32), 'New super_species_ref of unit 32: 31');
SELECT ok('superspecies' = (SELECT super_species_indexed FROM taxonomy WHERE id = 32), 'New super_species_indexed of unit 32: superspecies');
SELECT ok(32 = (SELECT species_ref FROM taxonomy WHERE id = 32), 'New species_ref of unit 32: 32');
SELECT ok('species' = (SELECT species_indexed FROM taxonomy WHERE id = 32), 'New species_indexed of unit 32: species');

SELECT ok('/1/21/22/23/24/25/' = (SELECT path FROm taxonomy WHERE id = 33), 'Path of unit 33: /1/21/22/23/24/25/');
SELECT ok('/1/21/22/23/24/25/33/' = (SELECT path from taxonomy WHERE id = 29), 'Path of unit 29: /1/21/22/23/24/25/33/');
SELECT ok('/1/21/22/23/24/25/33/29/' = (SELECT path from taxonomy WHERE id = 30), 'Path of unit 30: /1/21/22/23/24/25/33/29/');
SELECT ok('/1/21/22/23/24/25/33/29/30/' = (SELECT path from taxonomy WHERE id = 31), 'Path of unit 31: /1/21/22/23/24/25/33/29/30/');
SELECT ok('/1/21/22/23/24/25/33/29/30/31/' = (SELECT path from taxonomy WHERE id = 32), 'Path of unit 32: /1/21/22/23/24/25/33/29/30/31/');

INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (34, 'THEDOMAIN', 1, 0);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (35, 'THEKINGDOM', 2, 34);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (36, 'THESUPERPHYL', 3, 35);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (37, 'THEPHYLUM', 4, 36);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (38, 'THESUBPHYLUM', 5, 37);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (39, 'THECOHORTBOTANY', 8, 38);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (40, 'THESUBCOHORTBOTANY', 9, 39);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (41, 'THESUPERCLASS', 11, 40);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (42, 'THECLASS', 12, 41);
INSERT INTO taxonomy (id, name, level_ref, parent_ref) VALUES (43, 'THESECPHYLUM', 4, 36);

SELECT lives_ok('UPDATE taxonomy SET level_ref = 5, parent_ref = 43 WHERE id = 40', 'Unit 40 moved from parent unit 39 to parent unit 43 and changed from level 9 (sub_cohort_botany) to level 5 (sub_phylum)');

SELECT ok(34 = (SELECT domain_ref FROM taxonomy WHERE id = 40), 'New domain_ref of unit 40: 34');
SELECT ok('thedomain' = (SELECT domain_indexed FROM taxonomy WHERE id = 40), 'New domain_indexed of unit 40: thedomain');
SELECT ok(35 = (SELECT kingdom_ref FROM taxonomy WHERE id = 40), 'New kingdom_ref of unit 40: 35');
SELECT ok('thekingdom' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 40), 'New kingdom_indexed of unit 40: thekingdom');
SELECT ok(36 = (SELECT super_phylum_ref FROM taxonomy WHERE id = 40), 'New super_phylum_ref of unit 40: 36');
SELECT ok('thesuperphyl' = (SELECT super_phylum_indexed FROM taxonomy WHERE id = 40), 'New super_phylum_indexed of unit 40: thesuperphyl');
SELECT ok(43 = (SELECT phylum_ref FROM taxonomy WHERE id = 40), 'New phylum_ref of unit 40: 43');
SELECT ok('thesecphylum' = (SELECT phylum_indexed FROM taxonomy WHERE id = 40), 'New phylum_indexed of unit 40: thesecphylum');
SELECT ok(40 = (SELECT sub_phylum_ref FROM taxonomy WHERE id = 40), 'New sub_phylum_ref of unit 40: 40');
SELECT ok('thesubcohortbotany' = (SELECT sub_phylum_indexed FROM taxonomy WHERE id = 40), 'New sub_phylum_indexed of unit 40: thesubcohortbotany');
SELECT ok(0 = (SELECT super_class_ref FROM taxonomy WHERE id = 40), 'New super_class_ref of unit 40: 0');
SELECT ok('' = (SELECT super_class_indexed FROM taxonomy WHERE id = 40), 'New super_class_indexed of unit 40: '''' ');
SELECT ok(34 = (SELECT domain_ref FROM taxonomy WHERE id = 41), 'New domain_ref of unit 41: 34');
SELECT ok('thedomain' = (SELECT domain_indexed FROM taxonomy WHERE id = 41), 'New domain_indexed of unit 41: thedomain');
SELECT ok(35 = (SELECT kingdom_ref FROM taxonomy WHERE id = 41), 'New kingdom_ref of unit 41: 35');
SELECT ok('thekingdom' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 41), 'New kingdom_indexed of unit 41: thekingdom');
SELECT ok(36 = (SELECT super_phylum_ref FROM taxonomy WHERE id = 41), 'New super_phylum_ref of unit 41: 36');
SELECT ok('thesuperphyl' = (SELECT super_phylum_indexed FROM taxonomy WHERE id = 41), 'New super_phylum_indexed of unit 41: thesuperphyl');
SELECT ok(43 = (SELECT phylum_ref FROM taxonomy WHERE id = 41), 'New phylum_ref of unit 41: 43');
SELECT ok('thesecphylum' = (SELECT phylum_indexed FROM taxonomy WHERE id = 41), 'New phylum_indexed of unit 41: thesecphylum');
SELECT ok(40 = (SELECT sub_phylum_ref FROM taxonomy WHERE id = 41), 'New sub_phylum_ref of unit 41: 40');
SELECT ok('thesubcohortbotany' = (SELECT sub_phylum_indexed FROM taxonomy WHERE id = 41), 'New sub_phylum_indexed of unit 41: thesubcohortbotany');
SELECT ok(0 = (SELECT cohort_botany_ref FROM taxonomy WHERE id = 41), 'New cohort_botany_ref of unit 41: 0');
SELECT ok('' = (SELECT cohort_botany_indexed FROM taxonomy WHERE id = 41), 'New cohort_botany_indexed of unit 41: '''' ');
SELECT ok(0 = (SELECT sub_cohort_botany_ref FROM taxonomy WHERE id = 41), 'New sub_cohort_botany_ref of unit 41: 0');
SELECT ok('' = (SELECT sub_cohort_botany_indexed FROM taxonomy WHERE id = 41), 'New sub_cohort_botany_indexed of unit 41: '''' ');
SELECT ok(41 = (SELECT super_class_ref FROM taxonomy WHERE id = 41), 'New super_class_ref of unit 41: 41');
SELECT ok('thesuperclass' = (SELECT super_class_indexed FROM taxonomy WHERE id = 41), 'New super_class_indexed of unit 41: thesuperclass ');
SELECT ok(0 = (SELECT class_ref FROM taxonomy WHERE id = 41), 'New class_ref of unit 41: 0');
SELECT ok('' = (SELECT class_indexed FROM taxonomy WHERE id = 41), 'New class_indexed of unit 41: '''' ');
SELECT ok(34 = (SELECT domain_ref FROM taxonomy WHERE id = 42), 'New domain_ref of unit 42: 34');
SELECT ok('thedomain' = (SELECT domain_indexed FROM taxonomy WHERE id = 42), 'New domain_indexed of unit 42: thedomain');
SELECT ok(35 = (SELECT kingdom_ref FROM taxonomy WHERE id = 42), 'New kingdom_ref of unit 42: 35');
SELECT ok('thekingdom' = (SELECT kingdom_indexed FROM taxonomy WHERE id = 42), 'New kingdom_indexed of unit 42: thekingdom');
SELECT ok(36 = (SELECT super_phylum_ref FROM taxonomy WHERE id = 42), 'New super_phylum_ref of unit 42: 36');
SELECT ok('thesuperphyl' = (SELECT super_phylum_indexed FROM taxonomy WHERE id = 42), 'New super_phylum_indexed of unit 42: thesuperphyl');
SELECT ok(43 = (SELECT phylum_ref FROM taxonomy WHERE id = 42), 'New phylum_ref of unit 42: 43');
SELECT ok('thesecphylum' = (SELECT phylum_indexed FROM taxonomy WHERE id = 42), 'New phylum_indexed of unit 42: thesecphylum');
SELECT ok(40 = (SELECT sub_phylum_ref FROM taxonomy WHERE id = 42), 'New sub_phylum_ref of unit 42: 40');
SELECT ok('thesubcohortbotany' = (SELECT sub_phylum_indexed FROM taxonomy WHERE id = 42), 'New sub_phylum_indexed of unit 42: thesubcohortbotany');
SELECT ok(0 = (SELECT cohort_botany_ref FROM taxonomy WHERE id = 42), 'New cohort_botany_ref of unit 42: 0');
SELECT ok('' = (SELECT cohort_botany_indexed FROM taxonomy WHERE id = 42), 'New cohort_botany_indexed of unit 42: '''' ');
SELECT ok(0 = (SELECT sub_cohort_botany_ref FROM taxonomy WHERE id = 42), 'New sub_cohort_botany_ref of unit 42: 0');
SELECT ok('' = (SELECT sub_cohort_botany_indexed FROM taxonomy WHERE id = 42), 'New sub_cohort_botany_indexed of unit 42: '''' ');
SELECT ok(41 = (SELECT super_class_ref FROM taxonomy WHERE id = 42), 'New super_class_ref of unit 42: 41');
SELECT ok('thesuperclass' = (SELECT super_class_indexed FROM taxonomy WHERE id = 42), 'New super_class_indexed of unit 42: thesuperclass ');
SELECT ok(42 = (SELECT class_ref FROM taxonomy WHERE id = 42), 'New class_ref of unit 42: 42');
SELECT ok('theclass' = (SELECT class_indexed FROM taxonomy WHERE id = 42), 'New class_indexed of unit 42: theclass ');

SELECT diag('Update Multimedia');

INSERT INTO multimedia(id, title) VALUES (1,'Testing');
INSERT INTO multimedia(id, title) VALUES (2,'TestingBis');
INSERT INTO multimedia(id, title,parent_ref) VALUES (3,'Testing child and parent',1);
INSERT INTO multimedia(id, title,parent_ref) VALUES (4,'Testing testing child',3);

SELECT ok('/' = (SELECT path from multimedia WHERE id=1), 'Path is right initialised');
SELECT ok('/' = (SELECT path from multimedia WHERE id=2), 'Path is right initialised with other');
SELECT ok('/1/' = (SELECT path from multimedia WHERE id=3), 'Path take parent_ref');
SELECT ok('/1/3/' = (SELECT path from multimedia WHERE id=4), 'Path add parent to previous path');

UPDATE multimedia SET parent_ref=2 WHERE id=3;

SELECT ok('/2/' = (SELECT path from multimedia WHERE id=3), 'Path is updated');
SELECT ok('/2/3/' = (SELECT path from multimedia WHERE id=4),'Path is updated for children too');

UPDATE multimedia SET parent_ref=NULL WHERE id=3;
SELECT ok('/' = (SELECT path from multimedia WHERE id=3),'Path is set to / with null parent');
SELECT ok('/3/' = (SELECT path from multimedia WHERE id=4), 'Childrens path is updated too');


SELECT * FROM finish();
ROLLBACK;
