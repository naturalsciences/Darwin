CREATE TRIGGER tgr_clr_incrementMainCode_specimens AFTER INSERT
	ON specimens FOR EACH ROW
	EXECUTE PROCEDURE fct_clr_incrementMainCode();
	
CREATE TRIGGER trg_cpy_specimensMainCode_specimenPartCode AFTER INSERT
	ON specimen_parts FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_specimensMainCode();

CREATE TRIGGER trg_cpy_idToCode_gtu BEFORE INSERT OR UPDATE
	ON gtu FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_idToCode();

-- BEGIN HIERARCHYCAL UNITS CATALOGUE COPY FROM PARENT

CREATE TRIGGER trg_cpy_hierarchy_from_parents_chronostratigraphy BEFORE INSERT
	ON chronostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_hierarchy_from_parents();

CREATE TRIGGER trg_cpy_hierarchy_from_parents_lithostratigraphy BEFORE INSERT
	ON lithostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_hierarchy_from_parents();

CREATE TRIGGER trg_cpy_hierarchy_from_parents_mineralogy BEFORE INSERT
	ON mineralogy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_hierarchy_from_parents();

CREATE TRIGGER trg_cpy_hierarchy_from_parents_taxa BEFORE INSERT
	ON taxa FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_hierarchy_from_parents();

-- END HIERARCHYCAL UNITS CATALOGUE COPY FROM PARENT
	
-- BEGIN FULLTOINDEX
CREATE TRIGGER trg_cpy_fullToIndex_catalogueproperties BEFORE INSERT OR UPDATE
	ON catalogue_properties FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();
	
CREATE TRIGGER trg_cpy_fullToIndex_chronostratigraphy BEFORE INSERT OR UPDATE
	ON chronostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_expeditions BEFORE INSERT OR UPDATE
	ON expeditions FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_expeditions BEFORE INSERT OR UPDATE
	ON habitats FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();
	
CREATE TRIGGER trg_cpy_fullToIndex_identifications BEFORE INSERT OR UPDATE
	ON identifications FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_lithology BEFORE INSERT OR UPDATE
	ON lithology FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();
	
CREATE TRIGGER trg_cpy_fullToIndex_lithostratigraphy BEFORE INSERT OR UPDATE
	ON lithostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_mineralogy BEFORE INSERT OR UPDATE
	ON mineralogy FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_multimedia BEFORE INSERT OR UPDATE
	ON multimedia FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_multimediacodes BEFORE INSERT OR UPDATE
	ON multimedia_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_multimediakeywords BEFORE INSERT OR UPDATE
	ON multimedia_keywords FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_specimenpartscodes BEFORE INSERT OR UPDATE
	ON specimen_parts_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_specimenscodes BEFORE INSERT OR UPDATE
	ON specimens_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_taggroups BEFORE INSERT OR UPDATE
	ON tag_groups FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_tags BEFORE INSERT OR UPDATE
	ON tags FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_taxa BEFORE INSERT OR UPDATE
	ON taxa FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_vernacularnames BEFORE INSERT OR UPDATE
	ON vernacular_names FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();
	

CREATE TRIGGER trg_clr_specialstatus_specimenindividuals BEFORE INSERT OR UPDATE
	ON specimen_individuals FOR EACH ROW
	EXECUTE PROCEDURE fct_clr_specialstatus();
	
/******* BEGIN TRIGGER REFS *************
**
**
*****************************************/

CREATE TRIGGER trg_clr_referenceRecord_cataloguerelationships AFTER DELETE 
	ON catalogue_relationships FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_catalogueauthors AFTER DELETE 
	ON catalogue_authors FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecordcataloguelevels AFTER DELETE 
	ON catalogue_levels FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_possibleupperlevels AFTER DELETE 
	ON possible_upper_levels FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_comments AFTER DELETE 
	ON comments FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_tags AFTER DELETE 
	ON tags FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_taggroups AFTER DELETE 
	ON tag_groups FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_gtu AFTER DELETE 
	ON gtu FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_catalogueproperties AFTER DELETE 
	ON catalogue_properties FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_identifications AFTER DELETE 
	ON identifications FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_expertises AFTER DELETE 
	ON expertises FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_classvernacularnames AFTER DELETE 
	ON class_vernacular_names FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_vernacularnames AFTER DELETE 
	ON vernacular_names FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_expeditions AFTER DELETE 
	ON expeditions FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_people AFTER DELETE 
	ON people FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_users AFTER DELETE 
	ON users FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peoplelanguages AFTER DELETE 
	ON people_languages FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_userslanguages AFTER DELETE 
	ON users_languages FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_multimedia AFTER DELETE 
	ON multimedia FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peoplerelationships AFTER DELETE 
	ON people_relationships FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peoplecomm AFTER DELETE 
	ON people_comm FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peopleaddresses AFTER DELETE 
	ON people_addresses FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_userscomm AFTER DELETE 
	ON users_comm FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_usersaddresses AFTER DELETE 
	ON users_addresses FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_userslogininfos AFTER DELETE 
	ON users_login_infos FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peoplemultimedia AFTER DELETE 
	ON people_multimedia FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_usersmultimedia AFTER DELETE 
	ON users_multimedia FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_collections AFTER DELETE 
	ON collections FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_collectionsadmin AFTER DELETE 
	ON collections_admin FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_collectionsrights AFTER DELETE 
	ON collections_rights FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_collectionsfieldsvisibilities AFTER DELETE 
	ON collections_fields_visibilities FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_userscollrightsasked AFTER DELETE 
	ON users_coll_rights_asked FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_recordvisibilities AFTER DELETE 
	ON record_visibilities FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_ AFTER DELETE 
	ON users_workflow FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_mysavedsearches AFTER DELETE 
	ON collection_maintenance FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_mysavedsearches AFTER DELETE 
	ON my_saved_searches FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_mypreferences AFTER DELETE 
	ON my_preferences FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_mysavedspecimens AFTER DELETE 
	ON my_saved_specimens FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_taxa AFTER DELETE 
	ON taxa FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_peopletaxonomicnames AFTER DELETE 
	ON people_taxonomic_names FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_chronostratigraphy AFTER DELETE 
	ON chronostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_lithostratigraphy AFTER DELETE 
	ON lithostratigraphy FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_mineralogy AFTER DELETE 
	ON mineralogy FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_lithology AFTER DELETE 
	ON lithology FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_habitats AFTER DELETE 
	ON habitats FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_multimediakeywords AFTER DELETE 
	ON multimedia_keywords FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_soortenregister AFTER DELETE 
	ON soortenregister FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimens AFTER DELETE 
	ON specimens FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimenscodes AFTER DELETE 
	ON specimens_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_multimediacodes AFTER DELETE 
	ON multimedia_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimenindividuals AFTER DELETE 
	ON specimen_individuals FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimenparts AFTER DELETE 
	ON specimen_parts FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimenpartscodes AFTER DELETE 
	ON specimen_parts_codes FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimenpartsinsurances AFTER DELETE 
	ON specimen_parts_insurances FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_associatedmultimedia AFTER DELETE 
	ON associated_multimedia FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();

CREATE TRIGGER trg_clr_referenceRecord_specimensaccompanying AFTER DELETE 
	ON specimens_accompanying FOR EACH ROW
	EXECUTE PROCEDURE fct_clear_referencedRecord();	
