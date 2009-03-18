alter table people_languages add constraint chk_chk_people_languages_prefered_one check (fct_chk_one_pref_language(people_ref, prefered_language));
alter table users_languages add constraint chk_chk_users_languages_prefered_one check (fct_chk_one_pref_language(users_ref, prefered_language, 'users'));

