 DROP INDEX IF EXISTS idx_users_title;
 DROP INDEX IF EXISTS idx_gin_identifications_value_defined_ts;
 DROP INDEX IF EXISTS idx_users_sub_type;
 DROP INDEX IF EXISTS idx_users_login_infos_login_type;
 DROP INDEX IF EXISTS idx_users_addresses_country;
 DROP INDEX IF EXISTS idx_users_comm_comm_type;
 DROP INDEX IF EXISTS idx_users_languages_language_country;
 DROP INDEX IF EXISTS idx_users_login_infos_user_name;
 DROP INDEX IF EXISTS idx_users_languages_preferred_language;
 DROP INDEX IF EXISTS idx_users_login_infos_login_system;
 DROP INDEX IF EXISTS idx_gin_chronostratigraphy_naming;
 DROP INDEX IF EXISTS idx_users_workflow_user_status;
 DROP INDEX IF EXISTS idx_gist_vernacular_names_name_ts;
 DROP INDEX IF EXISTS idx_vernacular_names_name_indexed;
 DROP INDEX IF EXISTS idx_gist_comments_comment_ts;
 DROP INDEX IF EXISTS idx_gist_multimedia_descriptive_ts;
 DROP INDEX IF EXISTS idx_gist_codes_full_code_indexed;
 DROP INDEX IF EXISTS idx_gist_expeditions_name_ts;
 DROP INDEX IF EXISTS idx_gist_collection_maintenance_description_ts;
 DROP INDEX IF EXISTS idx_gin_people_formated_name_ts;
 DROP INDEX IF EXISTS idx_gin_people_addresses_address_parts_ts;
 DROP INDEX IF EXISTS idx_gin_users_formated_name_ts;
 DROP INDEX IF EXISTS idx_gin_users_addresses_address_parts_ts;
 DROP INDEX IF EXISTS idx_gin_taxonomy_naming;
 DROP INDEX IF EXISTS idx_gin_collection_naming; 
 DROP INDEX IF EXISTS idx_gin_darwin_flat_collection_institution_formated_name_ts;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_collection_main_manager_formated_name_ts;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_expedition_name_ts;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_taxon_name_indexed;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_chrono_name_indexed;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_litho_name_indexed;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_lithology_name_indexed;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_mineral_name_indexed;
 DROP INDEX IF EXISTS idx_gin_darwin_flat_gtu_tag_values_indexed;
 DROP INDEX IF EXISTS idx_gist_habitats_description_ts;
 DROP INDEX IF EXISTS idx_gin_lithostratigraphy_naming;
 DROP INDEX IF EXISTS idx_gin_mineralogy_naming;
 DROP INDEX IF EXISTS idx_gin_lithology_naming;
 DROP INDEX IF EXISTS idx_words_trgm;
 DROP INDEX IF EXISTS idx_words_table_field;
 DROP INDEX IF EXISTS idx_igs_ig_date;
 DROP INDEX IF EXISTS idx_expeditions_expedition_from_date;
 DROP INDEX IF EXISTS idx_users_multimedia_object_ref;
 DROP INDEX IF EXISTS idx_possible_upper_levels_level_ref;
 DROP INDEX IF EXISTS idx_possible_upper_levels_level_upper_ref;
 DROP INDEX IF EXISTS idx_record_visibilities;
 DROP INDEX IF EXISTS idx_tag_groups_gtu_ref;
 DROP INDEX IF EXISTS idx_gtu_parent_ref;
 DROP INDEX IF EXISTS idx_people_languages_people_ref;
 DROP INDEX IF EXISTS idx_users_languages_users_ref;
 DROP INDEX IF EXISTS idx_collections_institution_ref;
 DROP INDEX IF EXISTS idx_people_relationships_person_1_ref;
 DROP INDEX IF EXISTS idx_people_relationships_person_2_ref;
 DROP INDEX IF EXISTS idx_people_comm_person_user_ref;
 DROP INDEX IF EXISTS idx_collections_main_manager_ref;
 DROP INDEX IF EXISTS idx_people_addresses_person_user_ref;
 DROP INDEX IF EXISTS idx_users_comm_person_user_ref;
 DROP INDEX IF EXISTS idx_users_workflow_user_ref;
 DROP INDEX IF EXISTS idx_users_addresses_person_user_ref;
 DROP INDEX IF EXISTS idx_collections_parent_ref;
 DROP INDEX IF EXISTS idx_users_login_infos_user_ref;
 DROP INDEX IF EXISTS idx_people_multimedia_person_user_ref;
 DROP INDEX IF EXISTS idx_people_multimedia_object_ref;
 DROP INDEX IF EXISTS idx_collections_rights_collection_ref;
 DROP INDEX IF EXISTS idx_users_multimedia_person_user_ref;
 DROP INDEX IF EXISTS idx_collections_rights_db_user_type;
 DROP INDEX IF EXISTS idx_taxonomy_parent_ref;
 DROP INDEX IF EXISTS idx_collection_maintenance_user_ref;
 DROP INDEX IF EXISTS idx_collections_rights_user_ref;
 DROP INDEX IF EXISTS idx_collections_fields_visibilities_collection_ref;
 DROP INDEX IF EXISTS idx_collections_fields_visibilities_user_ref;
 DROP INDEX IF EXISTS idx_my_saved_searches_user_ref;
 DROP INDEX IF EXISTS idx_users_coll_rights_asked_collection_ref;
 DROP INDEX IF EXISTS idx_users_coll_rights_asked_user_ref;
 DROP INDEX IF EXISTS idx_my_widgets_user_ref;
 DROP INDEX IF EXISTS idx_my_widgets_icon_ref;
 DROP INDEX IF EXISTS idx_my_widgets_collections;
 DROP INDEX IF EXISTS idx_taxonomy_level_ref;
 DROP INDEX IF EXISTS idx_chronostratigraphy_level_ref;
 DROP INDEX IF EXISTS idx_chronostratigraphy_parent_ref;
 DROP INDEX IF EXISTS idx_chronostratigraphy_system_ref;
 DROP INDEX IF EXISTS idx_lithostratigraphy_level_ref;
 DROP INDEX IF EXISTS idx_lithostratigraphy_parent_ref;
 DROP INDEX IF EXISTS idx_mineralogy_level_ref;
 DROP INDEX IF EXISTS idx_mineralogy_parent_ref;
 DROP INDEX IF EXISTS idx_soortenregister_habitat_ref;
 DROP INDEX IF EXISTS idx_mineralogy_unit_family_ref;
 DROP INDEX IF EXISTS idx_soortenregister_taxa_ref;
 DROP INDEX IF EXISTS idx_lithology_level_ref;
 DROP INDEX IF EXISTS idx_lithology_unit_sub_group_ref;
 DROP INDEX IF EXISTS idx_lithology_parent_ref;
 DROP INDEX IF EXISTS idx_multimedia_keywords_object_ref;
 DROP INDEX IF EXISTS idx_soortenregister_gtu_ref;
 DROP INDEX IF EXISTS idx_specimens_expedition_ref;
 DROP INDEX IF EXISTS idx_specimens_collection_ref;
 DROP INDEX IF EXISTS idx_expeditions_expedition_to_date;
 DROP INDEX IF EXISTS idx_users_tracking_modification_date_time;
 DROP INDEX IF EXISTS idx_specimens_litho_ref;
 DROP INDEX IF EXISTS idx_specimens_gtu_ref;
 DROP INDEX IF EXISTS idx_specimens_taxon_ref;
 DROP INDEX IF EXISTS idx_specimens_lithology_ref;
 DROP INDEX IF EXISTS idx_specimens_chrono_ref;
 DROP INDEX IF EXISTS idx_specimens_mineral_ref;
 DROP INDEX IF EXISTS idx_specimens_host_taxon_ref;
 DROP INDEX IF EXISTS idx_specimens_host_specimen_ref;
 DROP INDEX IF EXISTS idx_specimen_individuals_specimen_ref;
 DROP INDEX IF EXISTS idx_specimen_parts_specimen_individual_ref;
 DROP INDEX IF EXISTS idx_associated_multimedia_multimedia_ref;
 DROP INDEX IF EXISTS idx_specimens_accompanying_specimen_ref;
 DROP INDEX IF EXISTS idx_specimens_accompanying_taxon_ref;
 DROP INDEX IF EXISTS idx_specimens_accompanying_mineral_ref;
 DROP INDEX IF EXISTS idx_insurances_insurer_ref;
 DROP INDEX IF EXISTS idx_specimens_ig_ref;
 DROP INDEX IF EXISTS idx_catalogue_properties_referenced_record;
 DROP INDEX IF EXISTS idx_tags_gtu_ref;
 DROP INDEX IF EXISTS idx_tags_group_ref;
 DROP INDEX IF EXISTS idx_classification_synonymies_referenced_record;
 DROP INDEX IF EXISTS idx_users_tracking_user_ref;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_qualifier;
 DROP INDEX IF EXISTS idx_catalogue_levels_level_type;
 DROP INDEX IF EXISTS idx_catalogue_people_people_type;
 DROP INDEX IF EXISTS idx_chronostratigraphy_lower_bound;
 DROP INDEX IF EXISTS idx_catalogue_people_people_sub_type;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_unit;
 DROP INDEX IF EXISTS idx_catalogue_people_people_order_by;
 DROP INDEX IF EXISTS idx_catalogue_people_referenced_record;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_type;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_accuracy_unit;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_sub_type;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_sub_type_indexed;
 DROP INDEX IF EXISTS idx_chronostratigraphy_upper_bound;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_method_indexed;
 DROP INDEX IF EXISTS idx_chronostratigraphy_name_order_by;
 DROP INDEX IF EXISTS idx_catalogue_properties_property_tool_indexed;
 DROP INDEX IF EXISTS idx_catalogue_relationships_relations;
 DROP INDEX IF EXISTS idx_classification_synonymies_order_by;
 DROP INDEX IF EXISTS idx_classification_keywords_referenced_record;
 DROP INDEX IF EXISTS idx_classification_synonymies_grouping;
 DROP INDEX IF EXISTS idx_class_vernacular_names_community;
 DROP INDEX IF EXISTS idx_class_vernacular_names_community_indexed;
 DROP INDEX IF EXISTS idx_class_vernacular_names_referenced_record;
 DROP INDEX IF EXISTS idx_codes_code_prefix;
 DROP INDEX IF EXISTS idx_codes_code_suffix;
 DROP INDEX IF EXISTS idx_codes_code_prefix_separator;
 DROP INDEX IF EXISTS idx_codes_code_suffix_separator;
 DROP INDEX IF EXISTS idx_codes_code;
 DROP INDEX IF EXISTS idx_collections_collection_name;
 DROP INDEX IF EXISTS idx_codes_referenced_record;
 DROP INDEX IF EXISTS idx_collection_maintenance_action;
 DROP INDEX IF EXISTS idx_comments_notion_concerned;
 DROP INDEX IF EXISTS idx_collection_maintenance_referenced_record;
 DROP INDEX IF EXISTS idx_collections_fields_visibilities_searchable;
 DROP INDEX IF EXISTS idx_collections_collection_type;
 DROP INDEX IF EXISTS idx_collections_fields_visibilities_visible;
 DROP INDEX IF EXISTS idx_identifications_order_by;
 DROP INDEX IF EXISTS idx_comments_referenced_record;
 DROP INDEX IF EXISTS idx_identifications_notion_concerned;
 DROP INDEX IF EXISTS idx_identifications_determination_status;
 DROP INDEX IF EXISTS idx_identifications_referenced_record;
 DROP INDEX IF EXISTS idx_igs_ig_num_indexed;
 DROP INDEX IF EXISTS idx_insurances_referenced_record;
 DROP INDEX IF EXISTS idx_insurances_insurance_currency;
 DROP INDEX IF EXISTS idx_insurances_insurance_year;
 DROP INDEX IF EXISTS idx_mineralogy_cristal_system;
 DROP INDEX IF EXISTS idx_lithostratigraphy_name_order_by;
 DROP INDEX IF EXISTS idx_lithology_name_order_by;
 DROP INDEX IF EXISTS idx_my_widgets_user_category;
 DROP INDEX IF EXISTS idx_mineralogy_code;
 DROP INDEX IF EXISTS idx_multimedia_is_digital;
 DROP INDEX IF EXISTS idx_mineralogy_name_order_by;
 DROP INDEX IF EXISTS idx_my_widgets_visible;
 DROP INDEX IF EXISTS idx_multimedia_type;
 DROP INDEX IF EXISTS idx_my_widgets_group_name;
 DROP INDEX IF EXISTS idx_multimedia_keywords_keyword_indexed;
 DROP INDEX IF EXISTS idx_my_widgets_is_available;
 DROP INDEX IF EXISTS idx_people_title;
 DROP INDEX IF EXISTS idx_my_widgets_order_by;
 DROP INDEX IF EXISTS idx_people_is_physical;
 DROP INDEX IF EXISTS idx_people_sub_type;
 DROP INDEX IF EXISTS idx_people_family_name;
 DROP INDEX IF EXISTS idx_people_addresses_country;
 DROP INDEX IF EXISTS idx_people_comm_comm_type;
 DROP INDEX IF EXISTS idx_people_languages_language_country;
 DROP INDEX IF EXISTS idx_record_visibilities_visible;
 DROP INDEX IF EXISTS idx_specimens_host_relationship;
 DROP INDEX IF EXISTS idx_specimen_parts_container;
 DROP INDEX IF EXISTS idx_specimens_category;
 DROP INDEX IF EXISTS idx_specimen_individuals_type;
 DROP INDEX IF EXISTS idx_specimens_collecting_method;
 DROP INDEX IF EXISTS idx_specimens_collecting_tool;
 DROP INDEX IF EXISTS idx_specimen_parts_row;
 DROP INDEX IF EXISTS idx_specimens_accompanying_form;
 DROP INDEX IF EXISTS idx_specimen_individuals_sex;
 DROP INDEX IF EXISTS idx_specimens_accompanying_unit;
 DROP INDEX IF EXISTS idx_specimen_individuals_type_search;
 DROP INDEX IF EXISTS idx_specimen_individuals_stage;
 DROP INDEX IF EXISTS idx_specimen_individuals_state;
 DROP INDEX IF EXISTS idx_specimen_parts_shelf;
 DROP INDEX IF EXISTS idx_specimen_individuals_social_status;
 DROP INDEX IF EXISTS idx_specimen_individuals_rock_form;
 DROP INDEX IF EXISTS idx_specimen_parts_container_type;
 DROP INDEX IF EXISTS idx_specimen_parts_specimen_part;
 DROP INDEX IF EXISTS idx_specimen_parts_room;
 DROP INDEX IF EXISTS idx_specimen_parts_sub_container;
 DROP INDEX IF EXISTS idx_specimen_parts_sub_container_storage;
 DROP INDEX IF EXISTS idx_specimen_parts_sub_container_type;
 DROP INDEX IF EXISTS idx_specimen_parts_container_storage;
 DROP INDEX IF EXISTS idx_taxonomy_path;
 DROP INDEX IF EXISTS idx_taxonomy_name_order_by;
 DROP INDEX IF EXISTS idx_tag_groups_group_name_indexed;
 DROP INDEX IF EXISTS idx_tag_groups_sub_group_name;
 DROP INDEX IF EXISTS idx_tags_tag_indexed;
 DROP INDEX IF EXISTS idx_tags_group_type;
 DROP INDEX IF EXISTS idx_tags_sub_group_type;
 DROP INDEX IF EXISTS idx_imports_collection_ref;

  /*** Gin indexes ***/

  DROP INDEX IF EXISTS idx_words_trgm;
  DROP INDEX IF EXISTS idx_tags_trgm;
  DROP INDEX IF EXISTS idx_tools_trgm;
  DROP INDEX IF EXISTS idx_methods_trgm;
  DROP INDEX IF EXISTS idx_words_table_field;

  /*** BTree indexes in Darwin flat table to be dropped ***/

  DROP INDEX IF EXISTS idx_darwin_flat_category;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_type;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_code;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_institution_formated_name_indexed;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_main_manager_formated_name_indexed;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_path;
  DROP INDEX IF EXISTS idx_darwin_flat_station_visible;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_code;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_path;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_from_date_mask;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_to_date_mask;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_to_date;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_from_date;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_chrono_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_litho_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_lithology_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_mineral_name_order_by;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_path;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_path;
  DROP INDEX IF EXISTS idx_darwin_flat_chrono_path;
  DROP INDEX IF EXISTS idx_darwin_flat_litho_path;
  DROP INDEX IF EXISTS idx_darwin_flat_lithology_path;
  DROP INDEX IF EXISTS idx_darwin_flat_mineral_path;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_extinct;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_extinct;
  DROP INDEX IF EXISTS idx_darwin_flat_ig_num;
  DROP INDEX IF EXISTS idx_darwin_flat_acquisition_category;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_institution_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_main_manager_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_expedition_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_gtu_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_taxon_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_host_taxon_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_chrono_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_chrono_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_chrono_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_litho_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_litho_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_litho_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_lithology_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_lithology_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_lithology_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_mineral_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_mineral_parent_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_mineral_level_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_ig_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_count_min;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_part_count_min;
  DROP INDEX IF EXISTS idx_darwin_flat_part_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_type_group;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_type_search;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_sex;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_state;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_stage;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_social_status;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_rock_form;
  DROP INDEX IF EXISTS idx_darwin_flat_part;
  DROP INDEX IF EXISTS idx_darwin_flat_part_status;
  DROP INDEX IF EXISTS idx_darwin_flat_building;
  DROP INDEX IF EXISTS idx_darwin_flat_floor;
  DROP INDEX IF EXISTS idx_darwin_flat_room;
  DROP INDEX IF EXISTS idx_darwin_flat_row;
  DROP INDEX IF EXISTS idx_darwin_flat_shelf;
  DROP INDEX IF EXISTS idx_darwin_flat_container;
  DROP INDEX IF EXISTS idx_darwin_flat_sub_container;
  DROP INDEX IF EXISTS idx_darwin_flat_container_type;
  DROP INDEX IF EXISTS idx_darwin_flat_sub_container_type;
  DROP INDEX IF EXISTS idx_darwin_flat_container_storage;
  DROP INDEX IF EXISTS idx_darwin_flat_sub_container_storage;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_is_public;
  DROP INDEX IF EXISTS idx_darwin_flat_collection_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_category;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_coll_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_taxon_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_chrono_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_litho_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_lithology_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_mineral_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_expedition_name;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_with_types;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_with_individuals;
  DROP INDEX IF EXISTS idx_darwin_flat_spec_ref_with_parts;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_category;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_coll_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_taxon_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_chrono_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_litho_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_lithology_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_mineral_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_expedition_name;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_spec_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_type;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_type_group;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_type_search;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_sex;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_state;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_stage;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_social_status;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_rock_form;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_individual_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_individual_ref_with_parts;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_category;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_coll_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_taxon_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_chrono_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_litho_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_lithology_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_mineral_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_expedition_name;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_spec_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_type;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_type_group;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_type_search;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_sex;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_state;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_stage;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_social_status;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_rock_form;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_individual_count_max;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_part;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_part_status;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_building;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_floor;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_room;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_row;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_container_type;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_container_storage;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_sub_container_type;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_container;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_sub_container_storage;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_sub_container;
  DROP INDEX IF EXISTS idx_darwin_flat_part_ref_part_count_max;
  DROP INDEX IF EXISTS idx_gin_darwin_flat_gtu_country_tags;
  DROP INDEX IF EXISTS idx_staging_people_record;

  DROP INDEX IF EXISTS idx_loans_status;
  DROP INDEX IF EXISTS idx_loan_items_loan_ref;
  DROP INDEX IF EXISTS idx_loan_items_ig_ref;
  DROP INDEX IF EXISTS idx_loan_items_part_ref;
  DROP INDEX IF EXISTS idx_loan_rights_ig_ref;
  DROP INDEX IF EXISTS idx_loan_rights_part_ref;