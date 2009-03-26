alter table people_languages add constraint chk_chk_people_languages_prefered_one check (fct_chk_one_pref_language(people_ref, prefered_language));
alter table users_languages add constraint chk_chk_users_languages_prefered_one check (fct_chk_one_pref_language(users_ref, prefered_language, 'users'));
alter table collections add constraint chk_chk_InstitutionIsMoral check (fct_chk_PeopleIsMoral(institution_ref));
alter table chronostratigraphy add constraint chk_chk_possible_upper_level_chronostratigraphy check (fct_chk_possible_upper_level('chronostratigraphy', parent_ref, level_ref, id));
alter table lithostratigraphy add constraint chk_chk_possible_upper_level_lithostratigraphy check (fct_chk_possible_upper_level('lithostratigraphy', parent_ref, level_ref, id));
alter table mineralogy add constraint chk_chk_possible_upper_level_mineralogy check (fct_chk_possible_upper_level('mineralogy', parent_ref, level_ref, id));
/*
alter table lithology add constraint chk_chk_possible_upper_level_lithology check (fct_chk_possible_upper_level('lithology', parent_ref, level_ref, id));
*/
alter table taxa add constraint chk_chk_possible_upper_level_taxa check (fct_chk_possible_upper_level('taxa', parent_ref, level_ref, id));
