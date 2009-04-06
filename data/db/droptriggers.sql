DROP TRIGGER IF EXISTS tgr_clr_incrementMainCode_specimens ON specimens CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_specimensMainCode_specimenPartCode ON specimen_parts_codes CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_idToCode_gtu ON gtu CASCADE;

DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_lithology ON lithology CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_catalogueproperties ON catalogue_properties CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_chronostratigraphy ON chronostratigraphy  CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_expeditions ON expeditions CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_identifications ON identifications CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_lithostratigraphy ON lithostratigraphy CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_mineralogy ON mineralogy CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_multimedia ON multimedia CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_multimediacodes ON multimedia_codes CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_multimediakeywords ON multimedia_keywords CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_specimenpartscodes ON specimen_parts_codes CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_specimenscodes ON specimens_codes CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_taggroups ON tag_groups CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_tags ON tags CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_taxa ON taxa CASCADE;
DROP TRIGGER IF EXISTS trg_cpy_fullToIndex_vernacularnames ON vernacular_names CASCADE;

DROP TRIGGER IF EXISTS trg_clr_specialstatus_specimenindividual ON specimen_individual CASCADE;

/*** REPERCUTION record_id ****/
DROP TRIGGER trg_clr_referenceRecord_cataloguerelationships ON catalogue_relationships;
DROP TRIGGER trg_clr_referenceRecord_catalogueauthors ON catalogue_authors;
DROP TRIGGER trg_clr_referenceRecord_gtu ON gtu;
DROP TRIGGER trg_clr_referenceRecord_catalogueproperties ON catalogue_properties;
DROP TRIGGER trg_clr_referenceRecord_identifications ON identifications;
--DROP TRIGGER trg_clr_referenceRecord_expertises ON expertises;
DROP TRIGGER trg_clr_referenceRecord_vernacularnames ON vernacular_names;
DROP TRIGGER trg_clr_referenceRecord_expeditions ON expeditions;
DROP TRIGGER trg_clr_referenceRecord_people ON people;
DROP TRIGGER trg_clr_referenceRecord_users ON users;
DROP TRIGGER trg_clr_referenceRecord_multimedia ON multimedia;
DROP TRIGGER trg_clr_referenceRecord_peoplerelationships ON people_relationships;
DROP TRIGGER trg_clr_referenceRecord_collections ON collections;
DROP TRIGGER trg_clr_referenceRecord_userscollrightsasked ON users_coll_rights_asked;
DROP TRIGGER trg_clr_referenceRecord_mysavedsearches ON collection_maintenance;
DROP TRIGGER trg_clr_referenceRecord_taxa ON taxa;
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

DROP TRIGGER trg_clear_referencedPeople ON people;

DROP TRIGGER trg_cpy_toFullText_comments ON comments;
DROP TRIGGER trg_cpy_toFullText_identifications ON identifications;
DROP TRIGGER trg_cpy_toFullText_peopleaddresses ON people_addresses;
DROP TRIGGER trg_cpy_toFullText_usersaddresses ON users_addresses;
DROP TRIGGER trg_cpy_toFullText_multimedia ON multimedia;
DROP TRIGGER trg_cpy_toFullText_collectionmaintenance ON collection_maintenance;
DROP TRIGGER trg_cpy_toFullText_expeditions ON expeditions;
DROP TRIGGER trg_cpy_toFullText_habitats ON habitats;
DROP TRIGGER trg_cpy_toFullText_vernacularnames ON vernacular_names;
	
DROP TRIGGER trg_cas_userType_userslogininfos ON users_login_infos;
DROP TRIGGER trg_chk_peopleType ON people;
DROP TRIGGER trg_chk_AreAuthors ON catalogue_authors;

DROP TRIGGER trg_clr_title ON people;
DROP TRIGGER trg_clr_title ON users;
DROP TRIGGER trg_cpy_FormattedName ON people;
DROP TRIGGER trg_cpy_FormattedName ON users;

DROP TRIGGER trg_clr_SavedSpecimense ON specimens;

/*** Copy Hierarchy from parents triggers ***/

DROP TRIGGER trg_cpy_hierarchy_from_parents_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_hierarchy_from_parents_taxa ON taxonomy;
--DROP TRIGGER trg_cpy_hierarchy_from_parents_lithology ON lithology;

/*** Cascade of name update of a unit on all reference of this unit in children triggers ***/
DROP TRIGGER trg_cpy_name_updt_impact_children_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_name_updt_impact_children_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_name_updt_impact_children_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_name_updt_impact_children_taxa ON taxonomy;
-- DROP TRIGGER trg_cpy_name_updt_impact_children_lithology ON lithology;

/*** Cascade of level or parent_ref update of a unit on all children units triggers  ***/
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_chronostratigraphy ON chronostratigraphy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_lithostratigraphy ON lithostratigraphy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_mineralogy ON mineralogy;
DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_taxa ON taxonomy;
-- DROP TRIGGER trg_cpy_update_levels_or_parent_cascade_lithology ON lithology;
