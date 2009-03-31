\unset ECHO
\i unit_launch.sql
SELECT plan(55);

SELECT diag('Chronostratigraphy level/parent update tests');

INSERT INTO chronostratigraphy (id, name, level_ref ) VALUES (1,'ÉLo Wÿorléds', 55);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'ÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (3, 'KÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'BÉLoWÿ', 58, 2);

SELECT lives_ok('UPDATE chronostratigraphy SET level_ref = 57, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 56 (era) to 57 (sub era) and attached to a new era - unit 3 -> allowed because direct children (unit 4 of system level) is attachable to a sub-era');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 2), 'New era_ref of unit 2 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 2), 'New era_indexed of unit 2 : kelowy');

SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 2), 'New sub_era_ref of unit 2 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 2), 'New sub_era_indexed of unit 2 : elowy');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 4), 'New era_ref of unit 4 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 4), 'New era_indexed of unit 4 : kelowy');
SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 4), 'New sub_era_ref of unit 4 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 4), 'New sub_era_indexed of unit 4 : elowy');
SELECT ok(4 = (SELECT system_ref FROM chronostratigraphy WHERE id = 4), 'New system_ref of unit 4 : 4');
SELECT ok('belowy' = (SELECT system_indexed FROM chronostratigraphy WHERE id = 4), 'New system_indexed of unit 4 : belowy');
SELECT ok(0 = (SELECT serie_ref FROM chronostratigraphy WHERE id = 4), 'New serie_ref of unit 4 : 0');
SELECT ok('' = (SELECT serie_indexed FROM chronostratigraphy WHERE id = 4), 'New serie_indexed of unit 4 : ''''');

SELECT throws_ok('UPDATE chronostratigraphy SET level_ref = 58 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Lithostratigraphy level/parent update tests');

INSERT INTO lithostratigraphy (id,name, level_ref) VALUES (1, 'Méalo-nÿeø@ß€', 64);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (2, 'Méalo-nÿeø@ß€A', 65, 1);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (3, 'Méalo-nÿeø@ß€B', 65, 1);
INSERT INTO lithostratigraphy (id,name, level_ref, parent_ref) VALUES (4, 'Méalo-nÿeø@ß€C', 66, 3);

SELECT lives_ok('UPDATE lithostratigraphy SET level_ref = 66, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 65 (formation) to 66 (member) and attached to a new formation - unit 3 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 2), 'New group_ref of unit 2: 1');
SELECT ok('mealonyeob' = (SELECT group_indexed FROM lithostratigraphy WHERE id = 2), 'New group_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT formation_ref FROM lithostratigraphy WHERE id = 2), 'New formation_ref of unit 2: 3');
SELECT ok('mealonyeobb' = (SELECT formation_indexed FROM lithostratigraphy WHERE id = 2), 'New formation_indexed of unit 2: mealonyeobb');
SELECT ok(2 = (SELECT member_ref FROM lithostratigraphy WHERE id = 2), 'New member_ref of unit 2: 2');
SELECT ok('mealonyeoba' = (SELECT member_indexed FROM lithostratigraphy WHERE id = 2), 'New member_indexed of unit 2: mealonyeoba');
SELECT ok(0 = (SELECT layer_ref FROM lithostratigraphy WHERE id = 2), 'New layer_ref of unit 2: 0');
SELECT ok('' = (SELECT layer_indexed FROM lithostratigraphy WHERE id = 2), 'New layer_ref of unit 2: ''''');

SELECT lives_ok('UPDATE lithostratigraphy SET level_ref = 67, parent_ref = 2 WHERE id = 4', 'Level of unit 4 have been changed from 66 (member) to 67 (layer) and attached to a new member - unit 2 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT group_ref FROM lithostratigraphy WHERE id = 4), 'New group_ref of unit 4: 1');
SELECT ok('mealonyeob' = (SELECT group_indexed FROM lithostratigraphy WHERE id = 4), 'New group_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT formation_ref FROM lithostratigraphy WHERE id = 4), 'New formation_ref of unit 4: 3');
SELECT ok('mealonyeobb' = (SELECT formation_indexed FROM lithostratigraphy WHERE id = 4), 'New formation_indexed of unit 4: mealonyeobb');
SELECT ok(2 = (SELECT member_ref FROM lithostratigraphy WHERE id = 4), 'New member_ref of unit 4: 2');
SELECT ok('mealonyeoba' = (SELECT member_indexed FROM lithostratigraphy WHERE id = 4), 'New member_indexed of unit 4: mealonyeoba');
SELECT ok(4 = (SELECT layer_ref FROM lithostratigraphy WHERE id = 4), 'New layer_ref of unit 4: 4');
SELECT ok('mealonyeobc' = (SELECT layer_indexed FROM lithostratigraphy WHERE id = 4), 'New layer_ref of unit 4: mealonyeobc');

SELECT throws_ok('UPDATE lithostratigraphy SET level_ref = 65, parent_ref = 1 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Mineralogy level/parent update tests');

INSERT INTO mineralogy (id,name, code, level_ref) VALUES (1, 'Méalo-nÿeø@ß€', 'A', 70);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (2, 'Méalo-nÿeø@ß€A', 'AA', 71, 1);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (3, 'Méalo-nÿeø@ß€B', 'B', 71, 1);
INSERT INTO mineralogy (id,name, code, level_ref, parent_ref) VALUES (4, 'Méalo-nÿeø@ß€C', 'C', 72, 3);

SELECT lives_ok('UPDATE mineralogy SET level_ref = 72, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 71 (unit_division) to 72 (unit_family) and attached to a new unit_division - unit 3 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT unit_class_ref FROM mineralogy WHERE id = 2), 'New unit_class_ref of unit 2: 1');
SELECT ok('mealonyeob' = (SELECT unit_class_indexed FROM mineralogy WHERE id = 2), 'New unit_class_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT unit_division_ref FROM mineralogy WHERE id = 2), 'New unit_division_ref of unit 2: 3');
SELECT ok('mealonyeobb' = (SELECT unit_division_indexed FROM mineralogy WHERE id = 2), 'New unit_division_indexed of unit 2: mealonyeobb');
SELECT ok(2 = (SELECT unit_family_ref FROM mineralogy WHERE id = 2), 'New unit_family_ref of unit 2: 2');
SELECT ok('mealonyeoba' = (SELECT unit_family_indexed FROM mineralogy WHERE id = 2), 'New unit_family_indexed of unit 2: mealonyeoba');
SELECT ok(0 = (SELECT unit_group_ref FROM mineralogy WHERE id = 2), 'New unit_group_ref of unit 2: 0');
SELECT ok('' = (SELECT unit_group_indexed FROM mineralogy WHERE id = 2), 'New unit_group_ref of unit 2: ''''');

SELECT lives_ok('UPDATE mineralogy SET level_ref = 73, parent_ref = 2 WHERE id = 4', 'Level of unit 4 have been changed from 72 (unit_family) to 73 (unit_group) and attached to a new unit_family - unit 2 -> allowed because no children attached yet');

SELECT ok(1 = (SELECT unit_class_ref FROM mineralogy WHERE id = 4), 'New unit_class_ref of unit 4: 1');
SELECT ok('mealonyeob' = (SELECT unit_class_indexed FROM mineralogy WHERE id = 4), 'New unit_class_indexed of unit 2: mealonyeob');
SELECT ok(3 = (SELECT unit_division_ref FROM mineralogy WHERE id = 4), 'New unit_division_ref of unit 4: 3');
SELECT ok('mealonyeobb' = (SELECT unit_division_indexed FROM mineralogy WHERE id = 4), 'New unit_division_indexed of unit 4: mealonyeobb');
SELECT ok(2 = (SELECT unit_family_ref FROM mineralogy WHERE id = 4), 'New unit_family_ref of unit 4: 2');
SELECT ok('mealonyeoba' = (SELECT unit_family_indexed FROM mineralogy WHERE id = 4), 'New unit_family_indexed of unit 4: mealonyeoba');
SELECT ok(4 = (SELECT unit_group_ref FROM mineralogy WHERE id = 4), 'New unit_group_ref of unit 4: 4');
SELECT ok('mealonyeobc' = (SELECT unit_group_indexed FROM mineralogy WHERE id = 4), 'New unit_group_ref of unit 4: mealonyeobc');

SELECT throws_ok('UPDATE mineralogy SET level_ref = 71, parent_ref = 1 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT diag('Taxonomy level/parent update tests');

INSERT INTO taxa (id, name, level_ref) VALUES (1, 'TOP', 1);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (2, 'A', 2, 1);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (3, 'AA', 4, 2);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (4, 'AAA', 12, 3);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (5, 'AAAA', 28, 4);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (6, 'AAAAA', 34, 5);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (7, 'AAAAAA', 41, 6);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (8, 'AAAAAAA', 42, 7);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (9, 'AAAAAAAA', 48, 8);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (10, 'AAAAAAAAA', 49, 9);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (11, 'B', 2, 1);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (12, 'C', 2, 1);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (13, 'CA', 4, 12);
INSERT INTO taxa (id, name, level_ref, parent_ref) VALUES (14, 'AAAAAAAB', 48, 7);

SELECT lives_ok('UPDATE taxa SET parent_ref = 11 WHERE id = 3', 'Unit 3 moved from parent unit 2 to parent unit 11');
SELECT ok(11 = (SELECT kingdom_ref FROM taxa WHERE id = 3), 'New kingdom_ref of unit 3: 11');
SELECT ok('b' = (SELECT kingdom_indexed FROM taxa WHERE id = 3), 'New kingdom_indexed of unit 3: b');


SELECT * FROM finish();
ROLLBACK;
