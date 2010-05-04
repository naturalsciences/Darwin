alter table people_languages add constraint chk_chk_people_languages_preferred_one check (fct_chk_one_pref_language(id,people_ref, preferred_language));
alter table users_languages add constraint chk_chk_users_languages_preferred_one check (fct_chk_one_pref_language(id,users_ref, preferred_language, 'users'));
alter table collections add constraint chk_chk_InstitutionIsMoral check (fct_chk_PeopleIsMoral(institution_ref));
alter table chronostratigraphy add constraint chk_chk_possible_upper_level_chronostratigraphy check (fct_chk_possible_upper_level('chronostratigraphy', parent_ref, level_ref, id));
alter table lithostratigraphy add constraint chk_chk_possible_upper_level_lithostratigraphy check (fct_chk_possible_upper_level('lithostratigraphy', parent_ref, level_ref, id));
alter table mineralogy add constraint chk_chk_possible_upper_level_mineralogy check (fct_chk_possible_upper_level('mineralogy', parent_ref, level_ref, id));
/*
alter table lithology add constraint chk_chk_possible_upper_level_lithology check (fct_chk_possible_upper_level('lithology', parent_ref, level_ref, id));

alter table taxonomy add constraint chk_chk_possible_upper_level_taxa check (fct_chk_possible_upper_level('taxonomy', parent_ref, level_ref, id));
*/

ALTER TABLE template_table_record_ref add constraint fct_chk_ReferencedRecord_template_table_record_ref check (fct_chk_ReferencedRecord(referenced_relation,record_id));
ALTER TABLE catalogue_relationships add constraint fct_chk_ReferencedRecord_catalogue_relationships_rec1 check (fct_chk_ReferencedRecord(referenced_relation,record_id_1));
ALTER TABLE catalogue_relationships add constraint fct_chk_ReferencedRecord_catalogue_relationships_rec2 check (fct_chk_ReferencedRecord(referenced_relation,record_id_2));
ALTER TABLE catalogue_relationships add constraint chk_not_related_to_self check (record_id_1 != record_id_2);

ALTER TABLE multimedia add constraint fct_chk_onceInPath_multimedia CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE gtu add constraint fct_chk_onceInPath_gtu CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE collections add constraint fct_chk_onceInPath_collections CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE taxonomy add constraint fct_chk_onceInPath_taxonomy CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE chronostratigraphy add constraint fct_chk_onceInPath_chronostratigraphy CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE lithostratigraphy add constraint fct_chk_onceInPath_lithostratigraphy CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE mineralogy add constraint fct_chk_onceInPath_mineralogy CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE lithology add constraint fct_chk_onceInPath_lithology CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));
ALTER TABLE habitats add constraint fct_chk_onceInPath_habitats CHECK(fct_chk_onceInPath( COALESCE(path,'') || '/' || id));

