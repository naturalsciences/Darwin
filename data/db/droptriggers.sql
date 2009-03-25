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

CREATE TRIGGER trg_clear_referencedPeople ON people;