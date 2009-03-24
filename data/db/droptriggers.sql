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
