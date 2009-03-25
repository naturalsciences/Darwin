\unset ECHO
\i unit_launch.sql
SELECT plan(9);

SELECT diag('Chronostratigraphy level/parent update tests');

SELECT lives_ok('INSERT INTO chronostratigraphy (id, name, level_ref ) VALUES (1,''ÉLo Wÿorléds'', 55)', 'Insertion of unit 1 with level eon (55) linked to unit 0 (without any level) allowed !');
SELECT lives_ok('INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (2, ''ÉLoWÿ'', 56, 1)', 'Insertion of unit 2 with level era (56) linked to unit 1 with level eon (55) OK');
SELECT lives_ok('INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (3, ''BÉLoWÿ'', 58, 2)', 'Insertion of unit 3 with level system (58) linked to unit 2 with level era (56) OK');
INSERT INTO chronostratigraphy (id, name, level_ref, parent_ref) VALUES (4, 'KÉLoWÿ', 56, 1);

SELECT ok(true = (SELECT fct_chk_possible_upper_level('chronostratigraphy', 2, 57, 3)), 'Move unit 3 (of level 58 (system)) to level 57 (sub era) allowed -> parent is an era !');
SELECT ok(true = (SELECT fct_chk_possible_upper_level('chronostratigraphy', 2, 57, 4)), 'Move unit 3 (of level 57 (sub era)) to parent 4 (era) allowed !');
SELECT ok(false = (SELECT fct_chk_possible_upper_level('chronostratigraphy', 2, 59, 3)), 'Move unit 3 (of level 58 (system)) to level 59 (serie) not allowed -> parent is an era !');
SELECT throws_ok('UPDATE chronostratigraphy SET level_ref = 59 WHERE id = 3', 23514);
SELECT ok(false = (SELECT fct_chk_possible_upper_level('chronostratigraphy', 1, 58, 3)), 'Move unit 3 to parent unit 1 (eon) not allowed -> A sub-era cannot be linked to an eon !');
SELECT throws_ok('UPDATE chronostratigraphy SET parent_ref = 1 WHERE id = 3', 23514);

SELECT * FROM finish();
ROLLBACK;
