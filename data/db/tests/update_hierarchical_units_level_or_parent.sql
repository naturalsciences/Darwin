\unset ECHO
\i unit_launch.sql
SELECT plan(14);

SELECT diag('Chronostratigraphy level/parent update tests');

INSERT INTO chronostratigraphy (id, name, level_ref ) VALUES (1,'ÉLo Wÿorléds', 55);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, 'ÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (3, 'KÉLoWÿ', 56, 1);
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'BÉLoWÿ', 58, 2);

SELECT lives_ok('UPDATE chronostratigraphy SET level_ref = 57, parent_ref = 3 WHERE id = 2', 'Level of unit 2 have been changed from 56 (era) to 57 (sub era) and attached to a new era - unit 3 -> allowed because direct children (unit 4 of system level) is attachable to a sub-era');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 2), 'New era_ref of unit 2 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 2), 'New era_ref of unit 2 : kelowy');

SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 2), 'sub_era_ref of unit 2 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 2), 'sub_era_ref of unit 2 : elowy');

SELECT ok(3 = (SELECT era_ref FROM chronostratigraphy WHERE id = 4), 'New era_ref of unit 4 : 3');
SELECT ok('kelowy' = (SELECT era_indexed FROM chronostratigraphy WHERE id = 4), 'New era_ref of unit 4 : kelowy');
SELECT ok(2 = (SELECT sub_era_ref FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : 2');
SELECT ok('elowy' = (SELECT sub_era_indexed FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : elowy');
SELECT ok(4 = (SELECT system_ref FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : 4');
SELECT ok('belowy' = (SELECT system_indexed FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : belowy');
SELECT ok(0 = (SELECT serie_ref FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : 0');
SELECT ok('' = (SELECT serie_indexed FROM chronostratigraphy WHERE id = 4), 'sub_era_ref of unit 4 : ''''');

SELECT throws_ok('UPDATE chronostratigraphy SET level_ref = 58 WHERE id = 2', 'Update of unit level break "possible_upper_levels" rule of direct children related. No modification of level for current unit allowed.');

SELECT * FROM finish();
ROLLBACK;
