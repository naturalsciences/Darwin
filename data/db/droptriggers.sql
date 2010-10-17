DROP TRIGGER trg_cpy_specimensMainCode_specimenPartCode ON specimen_parts;
DROP TRIGGER trg_cpy_idToCode_gtu ON gtu ;

DROP TRIGGER trg_cpy_fullToIndex_lithology ON lithology ;
DROP TRIGGER trg_cpy_fullToIndex_catalogueproperties ON catalogue_properties ;
DROP TRIGGER trg_cpy_fullToIndex_chronostratigraphy ON chronostratigraphy ;
DROP TRIGGER trg_cpy_fullToIndex_expeditions ON expeditions ;
DROP TRIGGER trg_cpy_fullToIndex_identifications ON identifications;
DROP TRIGGER trg_cpy_fullToIndex_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_fullToIndex_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_fullToIndex_multimedia ON multimedia;
DROP TRIGGER trg_cpy_fullToIndex_codes ON codes ;
DROP TRIGGER trg_cpy_fullToIndex_multimediakeywords ON multimedia_keywords;
DROP TRIGGER trg_cpy_fullToIndex_taggroups ON tag_groups;
DROP TRIGGER trg_cpy_fullToIndex_taxa ON taxonomy;
DROP TRIGGER trg_cpy_fullToIndex_classvernacularnames ON class_vernacular_names;
DROP TRIGGER trg_cpy_fullToIndex_vernacularnames ON vernacular_names;
DROP TRIGGER trg_cpy_fullToIndex_collecting_tools ON collecting_tools;
DROP TRIGGER trg_cpy_fullToIndex_collecting_methods ON collecting_methods;
DROP TRIGGER trg_clr_specialstatus_specimenindividuals ON specimen_individuals;

/*** REPERCUTION record_id ****/
DROP TRIGGER trg_clr_referenceRecord_cataloguerelationships ON catalogue_relationships;
DROP TRIGGER trg_clr_referenceRecord_cataloguepeople ON catalogue_people;
DROP TRIGGER trg_clr_referenceRecord_gtu ON gtu;
DROP TRIGGER trg_clr_referenceRecord_catalogueproperties ON catalogue_properties;
DROP TRIGGER trg_clr_referenceRecord_identifications ON identifications;
--DROP TRIGGER trg_clr_referenceRecord_expertises ON expertises;
DROP TRIGGER trg_clr_referenceRecord_vernacularnames ON vernacular_names;
DROP TRIGGER trg_clr_referenceRecord_expeditions ON expeditions;
DROP TRIGGER trg_clr_referenceRecord_people ON people;
DROP TRIGGER trg_clr_referenceRecord_users ON users;
DROP TRIGGER trg_clr_referenceRecord_multimedia ON multimedia;
DROP TRIGGER trg_clr_referenceRecord_collections ON collections;
/*DROP TRIGGER trg_clr_referenceRecord_userscollrightsasked ON users_coll_rights_asked;*/
DROP TRIGGER trg_clr_referenceRecord_mysavedsearches ON collection_maintenance;
DROP TRIGGER trg_clr_referenceRecord_taxa ON taxonomy;
DROP TRIGGER trg_clr_referenceRecord_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_clr_referenceRecord_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_clr_referenceRecord_mineralogy ON mineralogy;
DROP TRIGGER trg_clr_referenceRecord_lithology ON lithology;
DROP TRIGGER trg_clr_referenceRecord_habitats ON habitats;
DROP TRIGGER trg_clr_referenceRecord_soortenregister ON soortenregister;
DROP TRIGGER trg_clr_referenceRecord_specimens ON specimens;
DROP TRIGGER trg_clr_referenceRecord_specimenindividuals ON specimen_individuals;
DROP TRIGGER trg_clr_referenceRecord_specimenparts ON specimen_parts;
DROP TRIGGER trg_clr_referenceRecord_specimensaccompanying ON specimens_accompanying;

--DROP TRIGGER trg_clear_referencedPeople ON people;

DROP TRIGGER trg_cpy_toFullText_comments ON comments;
DROP TRIGGER trg_cpy_toFullText_identifications ON identifications;
DROP TRIGGER trg_cpy_toFullText_peopleaddresses ON people_addresses;
DROP TRIGGER trg_cpy_toFullText_usersaddresses ON users_addresses;
DROP TRIGGER trg_cpy_toFullText_multimedia ON multimedia;
DROP TRIGGER trg_cpy_toFullText_collectionmaintenance ON collection_maintenance;
DROP TRIGGER trg_cpy_toFullText_expeditions ON expeditions;
DROP TRIGGER trg_cpy_toFullText_habitats ON habitats;
DROP TRIGGER trg_cpy_toFullText_vernacularnames ON vernacular_names;

/*DROP TRIGGER trg_cas_userType_users ON users;*/
DROP TRIGGER trg_chk_peopleType ON people;
DROP TRIGGER trg_chk_AreRole ON catalogue_people;

DROP TRIGGER trg_cpy_FormattedName ON people;
DROP TRIGGER trg_cpy_FormattedName ON users;

DROP TRIGGER trg_cpy_gtuTags_TagGroups ON tag_groups;

/*** Copy Hierarchy from parents triggers ***/
DROP TRIGGER trg_cpy_hierarchy_from_parents_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_taxa ON taxonomy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_lithology ON lithology;

/*** Cascade of name update of a unit on all reference of this unit in children triggers ***/
DROP TRIGGER trg_cpy_name_updt_impact_children_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_name_updt_impact_children_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_name_updt_impact_children_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_name_updt_impact_children_taxa ON taxonomy;
DROP TRIGGER trg_cpy_name_updt_impact_children_lithology ON lithology;

/*** Cascade of level or parent_ref update of a unit on all children units triggers  ***/
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_taxa ON taxonomy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_lithology ON lithology;

/*** Hierarchical path update for catalogues ***/
DROP TRIGGER trg_cpy_update_path_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_update_path_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_update_path_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_update_path_taxonomy ON taxonomy;
DROP TRIGGER trg_cpy_update_path_lithology ON lithology;

/*** Specimen hosting ***/
DROP TRIGGER trg_cpy_updateHosts ON specimens;
DROP TRIGGER trg_cpy_updateSpecHostImpact ON specimens;

/*** Words triggers ***/
DROP TRIGGER trg_words_ts_cpy_collection_maintenance
	ON collection_maintenance;

DROP TRIGGER trg_words_ts_cpy_comments
	ON comments;

DROP TRIGGER trg_words_ts_cpy_vernacular_names
	ON vernacular_names;

DROP TRIGGER trg_words_ts_cpy_identification
	ON identifications;

DROP TRIGGER trg_words_ts_cpy_multimedia
	ON multimedia;

DROP TRIGGER trg_words_ts_cpy_people
	ON people;

DROP TRIGGER trg_words_ts_cpy_people_addresses
	ON people_addresses;

DROP TRIGGER trg_words_ts_cpy_users
	ON users;

DROP TRIGGER trg_words_ts_cpy_expeditions
	ON expeditions;

DROP TRIGGER trg_words_ts_cpy_habitats
	ON habitats;

DROP TRIGGER trg_words_ts_cpy_mineralogy
	ON mineralogy;

DROP TRIGGER trg_words_ts_cpy_chronostratigraphy
	ON chronostratigraphy;

DROP TRIGGER trg_words_ts_cpy_lithostratigraphy
	ON lithostratigraphy;

DROP TRIGGER trg_words_ts_cpy_lithology
	ON lithology;

DROP TRIGGER trg_words_ts_cpy_taxonomy
	ON taxonomy;

DROP TRIGGER trg_words_ts_cpy_codes
	ON codes;

/*** Tracking ***/

DROP TRIGGER trg_trk_log_table_taxonomy
	ON taxonomy;

DROP TRIGGER trg_trk_log_table_lithology
	ON lithology;

DROP TRIGGER trg_trk_log_table_chronostratigraphy
	ON chronostratigraphy;

DROP TRIGGER trg_trk_log_table_mineralogy
	ON mineralogy;

DROP TRIGGER trg_trk_log_table_people
	ON people;

DROP TRIGGER trg_trk_log_table_insurances
	ON insurances;

DROP TRIGGER trg_trk_log_table_specimens
	ON specimens;

DROP TRIGGER trg_trk_log_table_specimen_individuals
	ON specimen_individuals;

DROP TRIGGER trg_trk_log_table_specimen_parts
	ON specimen_parts;

DROP TRIGGER trg_trk_log_table_gtu
	ON gtu;

DROP TRIGGER trg_trk_log_table_tag_groups
	ON tag_groups;

DROP TRIGGER trg_trk_log_table_collections
	ON collections;

DROP TRIGGER trg_trk_log_table_comments
	ON comments;
DROP TRIGGER trg_trk_log_table_expeditions
	ON expeditions;

DROP TRIGGER trg_cpy_location
        ON gtu ;
/*** darwin flat synchro triggers ***/

DROP TRIGGER trg_update_expeditions_darwin_flat
  ON expeditions;

DROP TRIGGER trg_update_collections_darwin_flat
        ON collections;

DROP TRIGGER trg_update_gtu_darwin_flat
        ON gtu;

DROP TRIGGER trg_update_tag_groups_darwin_flat
        ON tag_groups;

DROP TRIGGER trg_update_people_darwin_flat
        ON people;

DROP TRIGGER trg_update_users_darwin_flat
        ON users;

DROP TRIGGER trg_update_igs_darwin_flat
        ON igs;

DROP TRIGGER trg_update_taxonomy_darwin_flat
        ON taxonomy;

DROP TRIGGER trg_update_chronostratigraphy_darwin_flat
        ON chronostratigraphy;

DROP TRIGGER trg_update_lithostratigraphy_darwin_flat
        ON lithostratigraphy;

DROP TRIGGER trg_update_lithology_darwin_flat
        ON lithology;

DROP TRIGGER trg_update_mineralogy_darwin_flat
        ON mineralogy;

DROP TRIGGER trg_update_specimens_darwin_flat
        ON specimens;

DROP TRIGGER trg_delete_specimen_individuals_darwin_flat
        ON specimen_individuals;

DROP TRIGGER trg_delete_specimen_parts_darwin_flat
        ON specimen_parts;

DROP TRIGGER trg_update_specimen_individuals_darwin_flat
        ON specimen_individuals;

DROP TRIGGER trg_update_specimen_parts_darwin_flat
        ON specimen_parts;

DROP TRIGGER trg_cpy_fulltoindex_expeditions
        ON habitats;

DROP TRIGGER trg_cpy_fulltoindex_classification_keywords
        ON classification_keywords;

DROP TRIGGER trg_cpy_fulltoindex_igs
        ON igs;

DROP TRIGGER trg_clr_referencerecord_insurances
        ON insurances;

-- DROP TRIGGER trg_cpy_updatecollectionadmin_collections
--         ON collections;

DROP TRIGGER trg_cpy_updateCollectionRights
        ON collections;

DROP TRIGGER trg_cpy_path_multimedia
        ON multimedia;

DROP TRIGGER trg_cpy_path_collections
        ON collections;

DROP TRIGGER trg_cpy_path_peoplerelationships
        ON people_relationships;

DROP TRIGGER trg_cpy_path_gtu
        ON gtu;

DROP TRIGGER trg_cpy_path_specimen_parts
        ON specimen_parts;

DROP TRIGGER trg_cpy_path_habitats
        ON habitats;

DROP TRIGGER trg_cpy_unified_values
        ON properties_values;

DROP TRIGGER trg_cpy_unified_values
        ON catalogue_properties;

DROP TRIGGER trg_nbr_in_relation
        ON catalogue_relationships;

DROP TRIGGER trg_nbr_in_synonym
        ON classification_synonymies;

DROP TRIGGER trg_darwin_flat_indviduals_after_del
        ON specimen_individuals;