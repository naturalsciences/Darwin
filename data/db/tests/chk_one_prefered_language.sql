-- Testing that only one prefered language is provided for people/users
\unset ECHO
\i unit_launch.sql
SELECT plan(26);
select count(*) from people_languages;
SELECT ok(fct_chk_one_pref_language(1, true), 'Table people_languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, false), 'Table people languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, true, 'users'), 'Table users_languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, false, 'users'), 'Table users_languages. No prefered language yet -> is ok');

SELECT lives_ok('INSERT INTO people_languages (people_ref) VALUES (1)', 'People: First data and not a prefered language -> should return ok');
SELECT lives_ok('INSERT INTO users_languages (users_ref) VALUES (1)', 'Users: First data and not a prefered language -> should return ok');

SELECT ok(fct_chk_one_pref_language(1, true), 'Table people_languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, false), 'Table people languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, true, 'users'), 'Table users_languages. No prefered language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1, false, 'users'), 'Table users_languages. No prefered language yet -> is ok');

SELECT lives_ok('INSERT INTO people_languages (people_ref, language_country, prefered_language) VALUES (1, ''fr_be'', true)', 'People: First prefered language inserted -> should be ok');
SELECT lives_ok('INSERT INTO users_languages (users_ref, language_country, prefered_language) VALUES (1, ''fr_be'', true)', 'Users: First prefered language inserted -> should be ok');

SELECT ok(not fct_chk_one_pref_language(1, true), 'Table people_languages. One prefered language yet -> should not be ok');
SELECT ok(fct_chk_one_pref_language(1, false), 'Table people languages. One prefered language yet -> should be ok');
SELECT ok(not fct_chk_one_pref_language(1, true, 'users'), 'Table users_languages. One prefered language yet -> should not be ok');
SELECT ok(fct_chk_one_pref_language(1, false, 'users'), 'Table users_languages. One prefered language yet -> should be ok');

SELECT throws_ok('INSERT INTO people_languages (people_ref, language_country, prefered_language) VALUES (1, ''nl_be'', true)', '23514');
SELECT throws_ok('INSERT INTO users_languages (users_ref, language_country, prefered_language) VALUES (1, ''fr_be'', true)', '23514');

SELECT throws_ok('UPDATE people_languages set prefered_language = true where language_country = ''en_gb'' ', '23514');
SELECT throws_ok('UPDATE users_languages set prefered_language = true where language_country = ''en_gb'' ', '23514');

SELECT lives_ok('UPDATE people_languages set prefered_language = false', 'People: Set all languages to false -> should be ok');
SELECT lives_ok('UPDATE users_languages set prefered_language = false', 'Users: Set all languages to false -> should be ok');
SELECT lives_ok('UPDATE people_languages set prefered_language = true where people_ref = 1 and language_country = ''en_gb'' ', 'People: Set english to true for user 1 -> should be ok');
SELECT lives_ok('UPDATE users_languages set prefered_language = false where users_ref = 1 and language_country = ''en_gb'' ', 'Users: Set english to true for user 1 -> should be ok');

SELECT lives_ok('INSERT INTO people_languages (people_ref, language_country, prefered_language) VALUES (2, ''fr_be'', true)', 'People: New person inserted with first prefered language inserted -> should be ok');
SELECT lives_ok('INSERT INTO users_languages (users_ref, language_country, prefered_language) VALUES (2, ''fr_be'', true)', 'Users: New user inserted with first prefered language inserted -> should be ok');

-- Finish the tests and clean up.
SELECT * FROM finish();
ROLLBACK;
