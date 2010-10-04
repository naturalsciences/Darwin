-- Testing that only one preferred language is provided for people/users
\unset ECHO
\i unit_launch.sql
SELECT plan(24);
--select count(*) from people_languages;
SELECT ok(fct_chk_one_pref_language(1,1, true), 'Table people_languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1,1, false), 'Table people languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1,1, true, 'users'), 'Table users_languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(1,1, false, 'users'), 'Table users_languages. No preferred language yet -> is ok');

SELECT lives_ok('INSERT INTO people_languages (id, people_ref) VALUES (10,1)', 'People: First data and not a preferred language -> should return ok');
SELECT lives_ok('INSERT INTO users_languages (id, users_ref) VALUES (11,1)', 'Users: First data and not a preferred language -> should return ok');

SELECT ok(fct_chk_one_pref_language(10,1, true), 'Table people_languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(10,1, false), 'Table people languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(11,1, true, 'users'), 'Table users_languages. No preferred language yet -> is ok');
SELECT ok(fct_chk_one_pref_language(11,1, false, 'users'), 'Table users_languages. No preferred language yet -> is ok');

SELECT lives_ok('INSERT INTO people_languages (id, people_ref, language_country, preferred_language) VALUES (13,1, ''fr_be'', true)', 'People: First preferred language inserted -> should be ok');
SELECT lives_ok('INSERT INTO users_languages (id, users_ref, language_country, preferred_language) VALUES (14,1, ''fr_be'', true)', 'Users: First preferred language inserted -> should be ok');

SELECT ok(not fct_chk_one_pref_language(1, 1, true), 'Table people_languages. One preferred language yet -> should not be ok');
SELECT ok(fct_chk_one_pref_language(13, 1, false), 'Table people languages. One preferred language yet -> should be ok');
SELECT ok(not fct_chk_one_pref_language(1, 1, true, 'users'), 'Table users_languages. One preferred language yet -> should not be ok');
SELECT ok(fct_chk_one_pref_language(14, 1, false, 'users'), 'Table users_languages. One preferred language yet -> should be ok');

SELECT throws_ok('INSERT INTO people_languages (people_ref, language_country, preferred_language) VALUES (1, ''nl_be'', true)', '23514');
SELECT throws_ok('INSERT INTO users_languages (users_ref, language_country, preferred_language) VALUES (1, ''fr_be'', true)', '23514');

-- SELECT throws_ok('UPDATE people_languages set preferred_language = true where language_country = ''en_gb'' ', '23514');
-- SELECT throws_ok('UPDATE users_languages set preferred_language = true where language_country = ''en_gb'' ', '23514');

SELECT lives_ok('UPDATE people_languages set preferred_language = false', 'People: Set all languages to false -> should be ok');
SELECT lives_ok('UPDATE users_languages set preferred_language = false', 'Users: Set all languages to false -> should be ok');
SELECT lives_ok('UPDATE people_languages set preferred_language = true where people_ref = 1 and language_country = ''en_gb'' ', 'People: Set english to true for user 1 -> should be ok');
SELECT lives_ok('UPDATE users_languages set preferred_language = false where users_ref = 1 and language_country = ''en_gb'' ', 'Users: Set english to true for user 1 -> should be ok');

SELECT lives_ok('INSERT INTO people_languages (people_ref, language_country, preferred_language) VALUES (2, ''fr_be'', true)', 'People: New person inserted with first preferred language inserted -> should be ok');
SELECT lives_ok('INSERT INTO users_languages (users_ref, language_country, preferred_language) VALUES (2, ''fr_be'', true)', 'Users: New user inserted with first preferred language inserted -> should be ok');

-- Finish the tests and clean up.
SELECT * FROM finish();
ROLLBACK;
