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

CREATE TRIGGER trg_cpy_fullToIndex_people BEFORE INSERT OR UPDATE
	ON people FOR EACH ROW
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

CREATE TRIGGER trg_cpy_fullToIndex_users BEFORE INSERT OR UPDATE
	ON users FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

CREATE TRIGGER trg_cpy_fullToIndex_vernacularnames BEFORE INSERT OR UPDATE
	ON vernacular_names FOR EACH ROW
	EXECUTE PROCEDURE fct_cpy_fullToIndex();

-- END FULLTOINDEX
