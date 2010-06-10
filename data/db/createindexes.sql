\set log_error_verbosity terse

/*** BTree Indexes for foreign keys ***/

CREATE INDEX CONCURRENTLY idx_possible_upper_levels_level_ref on possible_upper_levels(level_ref);
CREATE INDEX CONCURRENTLY idx_possible_upper_levels_level_upper_ref on possible_upper_levels(level_upper_ref);
CREATE INDEX CONCURRENTLY idx_tag_groups_gtu_ref on tag_groups(gtu_ref);
CREATE INDEX CONCURRENTLY idx_gtu_parent_ref on gtu(parent_ref);
CREATE INDEX CONCURRENTLY idx_people_languages_people_ref on people_languages(people_ref);
CREATE INDEX CONCURRENTLY idx_users_languages_users_ref on users_languages(users_ref);
CREATE INDEX CONCURRENTLY idx_people_relationships_person_1_ref on people_relationships(person_1_ref);
CREATE INDEX CONCURRENTLY idx_people_relationships_person_2_ref on people_relationships(person_2_ref);
CREATE INDEX CONCURRENTLY idx_people_comm_person_user_ref on people_comm(person_user_ref);
CREATE INDEX CONCURRENTLY idx_people_addresses_person_user_ref on people_addresses(person_user_ref);
CREATE INDEX CONCURRENTLY idx_users_comm_person_user_ref on users_comm(person_user_ref);
CREATE INDEX CONCURRENTLY idx_users_addresses_person_user_ref on users_addresses(person_user_ref);
CREATE INDEX CONCURRENTLY idx_users_login_infos_user_ref on users_login_infos(user_ref);
CREATE INDEX CONCURRENTLY idx_people_multimedia_person_user_ref on people_multimedia(person_user_ref);
CREATE INDEX CONCURRENTLY idx_people_multimedia_object_ref on people_multimedia(object_ref);
CREATE INDEX CONCURRENTLY idx_users_multimedia_person_user_ref on users_multimedia(person_user_ref);
CREATE INDEX CONCURRENTLY idx_users_multimedia_object_ref on users_multimedia(object_ref);
CREATE INDEX CONCURRENTLY idx_collections_institution_ref on collections(institution_ref);
CREATE INDEX CONCURRENTLY idx_collections_main_manager_ref on collections(main_manager_ref);
CREATE INDEX CONCURRENTLY idx_collections_parent_ref on collections(parent_ref);
CREATE INDEX CONCURRENTLY idx_collections_admin_collection_ref on collections_admin(collection_ref);
CREATE INDEX CONCURRENTLY idx_collections_admin_user_ref on collections_admin(user_ref);
CREATE INDEX CONCURRENTLY idx_collections_rights_collection_ref on collections_rights(collection_ref);
CREATE INDEX CONCURRENTLY idx_collections_rights_user_ref on collections_rights(user_ref);
CREATE INDEX CONCURRENTLY idx_collections_fields_visibilities_collection_ref on collections_fields_visibilities(collection_ref);
CREATE INDEX CONCURRENTLY idx_collections_fields_visibilities_user_ref on collections_fields_visibilities(user_ref);
CREATE INDEX CONCURRENTLY idx_users_coll_rights_asked_collection_ref on users_coll_rights_asked(collection_ref);
CREATE INDEX CONCURRENTLY idx_users_coll_rights_asked_user_ref on users_coll_rights_asked(user_ref);
CREATE INDEX CONCURRENTLY idx_record_visibilities on record_visibilities(user_ref);
CREATE INDEX CONCURRENTLY idx_users_workflow_user_ref on users_workflow(user_ref);
CREATE INDEX CONCURRENTLY idx_collection_maintenance_user_ref on collection_maintenance(people_ref);
CREATE INDEX CONCURRENTLY idx_my_saved_searches_user_ref on my_saved_searches(user_ref);
CREATE INDEX CONCURRENTLY idx_my_preferences_user_ref on my_preferences(user_ref);
CREATE INDEX CONCURRENTLY idx_my_preferences_icon_ref on my_preferences(icon_ref);
CREATE INDEX CONCURRENTLY idx_my_saved_specimens_user_ref on my_saved_specimens(user_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_level_ref on taxonomy(level_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_parent_ref on taxonomy(parent_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_domain_ref on taxonomy(domain_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_kingdom_ref on taxonomy(kingdom_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_phylum_ref on taxonomy(super_phylum_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_phylum_ref on taxonomy(phylum_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_phylum_ref on taxonomy(sub_phylum_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_phylum_ref on taxonomy(infra_phylum_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_cohort_botany_ref on taxonomy(super_cohort_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_cohort_botany_ref on taxonomy(cohort_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_cohort_botany_ref on taxonomy(sub_cohort_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_cohort_botany_ref on taxonomy(infra_cohort_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_class_ref on taxonomy(super_class_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_class_ref on taxonomy(class_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_class_ref on taxonomy(sub_class_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_class_ref on taxonomy(infra_class_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_division_ref on taxonomy(super_division_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_division_ref on taxonomy(division_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_division_ref on taxonomy(sub_division_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_division_ref on taxonomy(infra_division_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_legion_ref on taxonomy(super_legion_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_legion_ref on taxonomy(legion_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_legion_ref on taxonomy(sub_legion_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_legion_ref on taxonomy(infra_legion_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_cohort_zoology_ref on taxonomy(super_cohort_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_cohort_zoology_ref on taxonomy(cohort_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_cohort_zoology_ref on taxonomy(sub_cohort_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_cohort_zoology_ref on taxonomy(infra_cohort_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_order_ref on taxonomy(super_order_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_order_ref on taxonomy(order_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_order_ref on taxonomy(sub_order_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_order_ref on taxonomy(infra_order_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_section_zoology_ref on taxonomy(section_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_section_zoology_ref on taxonomy(sub_section_zoology_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_family_ref on taxonomy(super_family_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_family_ref on taxonomy(family_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_family_ref on taxonomy(sub_family_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_family_ref on taxonomy(infra_family_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_tribe_ref on taxonomy(super_tribe_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_tribe_ref on taxonomy(tribe_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_tribe_ref on taxonomy(sub_tribe_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_tribe_ref on taxonomy(infra_tribe_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_genus_ref on taxonomy(genus_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_genus_ref on taxonomy(sub_genus_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_section_botany_ref on taxonomy(section_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_section_botany_ref on taxonomy(sub_section_botany_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_serie_ref on taxonomy(serie_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_serie_ref on taxonomy(sub_serie_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_species_ref on taxonomy(super_species_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_species_ref on taxonomy(species_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_species_ref on taxonomy(sub_species_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_variety_ref on taxonomy(variety_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_variety_ref on taxonomy(sub_variety_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_form_ref on taxonomy(form_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_form_ref on taxonomy(sub_form_ref);
CREATE INDEX CONCURRENTLY idx_taxonomy_abberans_ref on taxonomy(abberans_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_level_ref on chronostratigraphy(level_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_parent_ref on chronostratigraphy(parent_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_eon_ref on chronostratigraphy(eon_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_era_ref on chronostratigraphy(era_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_era_ref on chronostratigraphy(sub_era_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_system_ref on chronostratigraphy(system_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_serie_ref on chronostratigraphy(serie_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_stage_ref on chronostratigraphy(stage_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_stage_ref on chronostratigraphy(sub_stage_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_level_1_ref on chronostratigraphy(sub_level_1_ref);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_level_2_ref on chronostratigraphy(sub_level_2_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_level_ref on lithostratigraphy(level_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_parent_ref on lithostratigraphy(parent_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_group_ref on lithostratigraphy(group_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_formation_ref on lithostratigraphy(formation_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_member_ref on lithostratigraphy(member_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_layer_ref on lithostratigraphy(layer_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_sub_level_1_ref on lithostratigraphy(sub_level_1_ref);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_sub_level_2_ref on lithostratigraphy(sub_level_2_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_level_ref on mineralogy(level_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_parent_ref on mineralogy(parent_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_class_ref on mineralogy(unit_class_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_division_ref on mineralogy(unit_division_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_family_ref on mineralogy(unit_family_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_group_ref on mineralogy(unit_group_ref);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_variety_ref on mineralogy(unit_variety_ref);
CREATE INDEX CONCURRENTLY idx_lithology_level_ref on lithology(level_ref);
CREATE INDEX CONCURRENTLY idx_lithology_parent_ref on lithology(parent_ref);
CREATE INDEX CONCURRENTLY idx_lithology_unit_main_group_ref on lithology(unit_main_group_ref);
CREATE INDEX CONCURRENTLY idx_lithology_unit_group_ref on lithology(unit_group_ref);
CREATE INDEX CONCURRENTLY idx_lithology_unit_sub_group_ref on lithology(unit_sub_group_ref);
CREATE INDEX CONCURRENTLY idx_lithology_unit_rock_ref on lithology(unit_rock_ref);
CREATE INDEX CONCURRENTLY idx_multimedia_keywords_object_ref on multimedia_keywords(object_ref);
CREATE INDEX CONCURRENTLY idx_soortenregister_taxa_ref on soortenregister(taxa_ref);
CREATE INDEX CONCURRENTLY idx_soortenregister_gtu_ref on soortenregister(gtu_ref);
CREATE INDEX CONCURRENTLY idx_soortenregister_habitat_ref on soortenregister(habitat_ref);
CREATE INDEX CONCURRENTLY idx_specimens_collection_ref on specimens(collection_ref);
CREATE INDEX CONCURRENTLY idx_specimens_expedition_ref on specimens(expedition_ref) WHERE expedition_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_gtu_ref on specimens(gtu_ref) WHERE gtu_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_taxon_ref on specimens(taxon_ref) WHERE taxon_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_litho_ref on specimens(litho_ref) WHERE litho_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_chrono_ref on specimens(chrono_ref) WHERE chrono_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_lithology_ref on specimens(lithology_ref) WHERE lithology_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_mineral_ref on specimens(mineral_ref) WHERE mineral_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_host_taxon_ref on specimens(host_taxon_ref) WHERE host_taxon_ref <> 0;
CREATE INDEX CONCURRENTLY idx_specimens_host_specimen_ref on specimens(host_specimen_ref) WHERE host_specimen_ref IS NOT NULL;
CREATE INDEX CONCURRENTLY idx_specimen_individuals_specimen_ref on specimen_individuals(specimen_ref);
CREATE INDEX CONCURRENTLY idx_specimen_parts_specimen_individual_ref on specimen_parts(specimen_individual_ref);
CREATE INDEX CONCURRENTLY idx_associated_multimedia_multimedia_ref on associated_multimedia(multimedia_ref);
CREATE INDEX CONCURRENTLY idx_specimens_accompanying_specimen_ref on specimens_accompanying(specimen_ref);
CREATE INDEX CONCURRENTLY idx_specimens_accompanying_taxon_ref on specimens_accompanying(taxon_ref);
CREATE INDEX CONCURRENTLY idx_specimens_accompanying_mineral_ref on specimens_accompanying(mineral_ref);
CREATE INDEX CONCURRENTLY idx_specimens_accompanying_form on specimens_accompanying(form);
CREATE INDEX CONCURRENTLY idx_specimens_accompanying_unit on specimens_accompanying(unit);
CREATE INDEX CONCURRENTLY idx_insurances_referenced_relation on insurances(referenced_relation);
CREATE INDEX CONCURRENTLY idx_insurances_record_id on insurances(record_id);
CREATE INDEX CONCURRENTLY idx_insurances_insurance_currency on insurances(insurance_currency);
CREATE INDEX CONCURRENTLY idx_insurances_insurer_ref on insurances(insurer_ref);
CREATE INDEX CONCURRENTLY idx_specimens_ig_ref on specimens(ig_ref);
CREATE INDEX CONCURRENTLY idx_igs_ig_num_indexed on igs (ig_num_indexed);
CREATE INDEX CONCURRENTLY idx_class_vernacular_names_community_indexed on class_vernacular_names (community_indexed);
CREATE INDEX CONCURRENTLY idx_vernacular_names_name_indexed on vernacular_names (name_indexed);
CREATE INDEX CONCURRENTLY idx_mineralogy_code on mineralogy(upper(code));
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_lower_bound on chronostratigraphy(coalesce(lower_bound, -4600));
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_upper_bound on chronostratigraphy(coalesce(upper_bound, 1));
CREATE INDEX CONCURRENTLY idx_taxonomy_name_order_by on taxonomy(name_order_by);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_name_order_by on chronostratigraphy(name_order_by);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_name_order_by on lithostratigraphy(name_order_by);
CREATE INDEX CONCURRENTLY idx_lithology_name_order_by on lithology(name_order_by);
CREATE INDEX CONCURRENTLY idx_mineralogy_name_order_by on mineralogy(name_order_by);
CREATE INDEX CONCURRENTLY idx_codes_full_code_order_by on codes(full_code_order_by);
CREATE INDEX CONCURRENTLY idx_comments_notion_concerned on comments(notion_concerned);

/*** GiST and eventual GIN Indexes for ts_vector fields ***/

CREATE INDEX CONCURRENTLY idx_gist_comments_comment_ts on comments using gist(comment_ts);
CREATE INDEX CONCURRENTLY idx_gist_codes_full_code_indexed on codes using gist(full_code_indexed);
CREATE INDEX CONCURRENTLY idx_gin_identifications_value_defined_ts on identifications using gin(value_defined_ts);
CREATE INDEX CONCURRENTLY idx_gist_vernacular_names_name_ts on vernacular_names using gist(name_ts);
CREATE INDEX CONCURRENTLY idx_gist_expeditions_name_ts on expeditions using gist(name_ts);
CREATE INDEX CONCURRENTLY idx_gin_people_formated_name_ts on people using gin(formated_name_ts);
CREATE INDEX CONCURRENTLY idx_gin_users_formated_name_ts on users using gin(formated_name_ts);
CREATE INDEX CONCURRENTLY idx_gist_multimedia_descriptive_ts on multimedia using gist(descriptive_ts);
CREATE INDEX CONCURRENTLY idx_gin_people_addresses_address_parts_ts on people_addresses using gin(address_parts_ts);
CREATE INDEX CONCURRENTLY idx_gin_users_addresses_address_parts_ts on users_addresses using gin(address_parts_ts);
CREATE INDEX CONCURRENTLY idx_gist_collection_maintenance_description_ts on collection_maintenance using gist(description_ts);
CREATE INDEX CONCURRENTLY idx_gist_habitats_description_ts on habitats using gist(description_ts);
CREATE INDEX CONCURRENTLY idx_gin_taxonomy_naming on taxonomy using gin(name_indexed);
CREATE INDEX CONCURRENTLY idx_gin_chronostratigraphy_naming on chronostratigraphy using gin(name_indexed);
CREATE INDEX CONCURRENTLY idx_gin_lithostratigraphy_naming on lithostratigraphy using gin(name_indexed);
CREATE INDEX CONCURRENTLY idx_gin_mineralogy_naming on mineralogy using gin(name_indexed);
CREATE INDEX CONCURRENTLY idx_gin_lithology_naming on lithology using gin(name_indexed);

/*** BTree Indexes for _indexed fields used for searches purposes ***/

CREATE INDEX CONCURRENTLY idx_tag_groups_group_name_indexed on tag_groups(group_name_indexed);
CREATE INDEX CONCURRENTLY idx_catalogue_properties_property_types_sub_types on catalogue_properties(property_type, property_sub_type_indexed);
CREATE INDEX CONCURRENTLY idx_catalogue_properties_property_method_indexed on catalogue_properties(property_method_indexed);
CREATE INDEX CONCURRENTLY idx_catalogue_properties_property_tool_indexed on catalogue_properties(property_tool_indexed);
CREATE INDEX CONCURRENTLY idx_multimedia_keywords_keyword_indexed on multimedia_keywords(keyword_indexed);
CREATE INDEX CONCURRENTLY idx_codes_code_prefix on codes(code_prefix) WHERE NOT code_prefix IS NULL;
CREATE INDEX CONCURRENTLY idx_codes_code_suffix on codes(code_suffix) WHERE NOT code_suffix IS NULL;
CREATE INDEX CONCURRENTLY idx_codes_code on codes(code) WHERE NOT code IS NULL;

/*

@TODO: Remove what's here when performance search checked...
If not OK, adapt these lines to use GIN index

CREATE INDEX CONCURRENTLY idx_taxonomy_domain_indexed on taxonomy(domain_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_kingdom_indexed on taxonomy(kingdom_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_super_phylum_indexed on taxonomy(super_phylum_indexed) WHERE super_phylum_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_phylum_indexed on taxonomy(phylum_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_phylum_indexed on taxonomy(sub_phylum_indexed) WHERE sub_phylum_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_phylum_indexed on taxonomy(infra_phylum_indexed) WHERE infra_phylum_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_cohort_botany_indexed on taxonomy(super_cohort_botany_indexed) WHERE super_cohort_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_cohort_botany_indexed on taxonomy(cohort_botany_indexed) WHERE cohort_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_cohort_botany_indexed on taxonomy(sub_cohort_botany_indexed) WHERE sub_cohort_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_cohort_botany_indexed on taxonomy(infra_cohort_botany_indexed) WHERE infra_cohort_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_class_indexed on taxonomy(super_class_indexed) WHERE super_class_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_class_indexed on taxonomy(class_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_class_indexed on taxonomy(sub_class_indexed) WHERE sub_class_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_class_indexed on taxonomy(infra_class_indexed) WHERE infra_class_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_division_indexed on taxonomy(super_division_indexed) WHERE super_division_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_division_indexed on taxonomy(division_indexed) WHERE division_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_division_indexed on taxonomy(sub_division_indexed) WHERE sub_division_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_division_indexed on taxonomy(infra_division_indexed) WHERE infra_division_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_legion_indexed on taxonomy(super_legion_indexed) WHERE super_legion_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_legion_indexed on taxonomy(legion_indexed) WHERE legion_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_legion_indexed on taxonomy(sub_legion_indexed) WHERE sub_legion_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_legion_indexed on taxonomy(infra_legion_indexed) WHERE infra_legion_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_cohort_zoology_indexed on taxonomy(super_cohort_zoology_indexed) WHERE super_cohort_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_cohort_zoology_indexed on taxonomy(cohort_zoology_indexed) WHERE cohort_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_cohort_zoology_indexed on taxonomy(sub_cohort_zoology_indexed) WHERE sub_cohort_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_cohort_zoology_indexed on taxonomy(infra_cohort_zoology_indexed) WHERE infra_cohort_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_order_indexed on taxonomy(super_order_indexed) WHERE super_order_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_order_indexed on taxonomy(order_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_order_indexed on taxonomy(sub_order_indexed) WHERE sub_order_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_order_indexed on taxonomy(infra_order_indexed) WHERE infra_order_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_section_zoology_indexed on taxonomy(section_zoology_indexed) WHERE section_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_section_zoology_indexed on taxonomy(sub_section_zoology_indexed) WHERE sub_section_zoology_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_family_indexed on taxonomy(super_family_indexed) WHERE super_family_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_family_indexed on taxonomy(family_indexed);
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_family_indexed on taxonomy(sub_family_indexed) WHERE sub_family_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_family_indexed on taxonomy(infra_family_indexed) WHERE infra_family_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_tribe_indexed on taxonomy(super_tribe_indexed) WHERE super_tribe_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_tribe_indexed on taxonomy(tribe_indexed) WHERE tribe_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_tribe_indexed on taxonomy(sub_tribe_indexed) WHERE sub_tribe_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_infra_tribe_indexed on taxonomy(infra_tribe_indexed) WHERE infra_tribe_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_genus_indexed on taxonomy(genus_indexed) WHERE genus_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_genus_indexed on taxonomy(sub_genus_indexed) WHERE sub_genus_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_section_botany_indexed on taxonomy(section_botany_indexed) WHERE section_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_section_botany_indexed on taxonomy(sub_section_botany_indexed) WHERE sub_section_botany_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_serie_indexed on taxonomy(serie_indexed) WHERE serie_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_serie_indexed on taxonomy(sub_serie_indexed) WHERE sub_serie_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_super_species_indexed on taxonomy(super_species_indexed) WHERE super_species_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_species_indexed on taxonomy(species_indexed) WHERE species_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_species_indexed on taxonomy(sub_species_indexed) WHERE sub_species_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_variety_indexed on taxonomy(variety_indexed) WHERE variety_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_variety_indexed on taxonomy(sub_variety_indexed) WHERE sub_variety_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_form_indexed on taxonomy(form_indexed) WHERE form_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_sub_form_indexed on taxonomy(sub_form_indexed) WHERE sub_form_indexed <> '';
CREATE INDEX CONCURRENTLY idx_taxonomy_abberans_indexed on taxonomy(abberans_indexed) WHERE abberans_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_eon_indexed on chronostratigraphy(eon_indexed);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_era_indexed on chronostratigraphy(era_indexed);
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_era_indexed on chronostratigraphy(sub_era_indexed) WHERE sub_era_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_system_indexed on chronostratigraphy(system_indexed) WHERE system_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_serie_indexed on chronostratigraphy(serie_indexed) WHERE serie_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_stage_indexed on chronostratigraphy(stage_indexed) WHERE stage_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_stage_indexed on chronostratigraphy(sub_stage_indexed) WHERE sub_stage_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_level_1_indexed on chronostratigraphy(sub_level_1_indexed) WHERE sub_level_1_indexed <> '';
CREATE INDEX CONCURRENTLY idx_chronostratigraphy_sub_level_2_indexed on chronostratigraphy(sub_level_2_indexed) WHERE sub_level_2_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_group_indexed on lithostratigraphy(group_indexed);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_formation_indexed on lithostratigraphy(formation_indexed);
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_member_indexed on lithostratigraphy(member_indexed) WHERE member_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_layer_indexed on lithostratigraphy(layer_indexed) WHERE layer_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_sub_level_1_indexed on lithostratigraphy(sub_level_1_indexed) WHERE sub_level_1_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithostratigraphy_sub_level_2_indexed on lithostratigraphy(sub_level_2_indexed) WHERE sub_level_2_indexed <> '';
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_class_indexed on mineralogy(unit_class_indexed);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_division_indexed on mineralogy(unit_division_indexed);
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_family_indexed on mineralogy(unit_family_indexed) WHERE unit_family_indexed <> '';
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_group_indexed on mineralogy(unit_group_indexed) WHERE unit_group_indexed <> '';
CREATE INDEX CONCURRENTLY idx_mineralogy_unit_variety_indexed on mineralogy(unit_variety_indexed) WHERE unit_variety_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithology_unit_main_group_indexed on lithology(unit_main_group_indexed) WHERE unit_main_group_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithology_unit_group_indexed on lithology(unit_group_indexed) WHERE unit_group_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithology_unit_sub_group_indexed on lithology(unit_sub_group_indexed) WHERE unit_sub_group_indexed <> '';
CREATE INDEX CONCURRENTLY idx_lithology_unit_rock_indexed on lithology(unit_rock_indexed) WHERE unit_rock_indexed <> '';
*/

/*** BTree Indexes used on non indexed fields for search purposes ***/

CREATE INDEX CONCURRENTLY idx_specimen_individuals_type_search on specimen_individuals(type_search) WHERE type_search <> '';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_type on specimen_individuals(type) WHERE "type" <> 'specimen';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_sex on specimen_individuals(sex) WHERE sex <> 'undefined';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_stage on specimen_individuals(stage) WHERE stage <> 'undefined';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_state on specimen_individuals(sex, state) WHERE state <> 'not applicable';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_social_status on specimen_individuals(social_status) WHERE social_status <> 'not applicable';
CREATE INDEX CONCURRENTLY idx_specimen_individuals_rock_form on specimen_individuals(rock_form) WHERE rock_form <> 'not applicable';
CREATE INDEX CONCURRENTLY idx_specimen_parts_specimen_part on specimen_parts(specimen_part);
CREATE INDEX CONCURRENTLY idx_specimen_parts_room on specimen_parts(room) WHERE NOT room IS NULL;
CREATE INDEX CONCURRENTLY idx_specimen_parts_row on specimen_parts(row) WHERE NOT row IS NULL;
CREATE INDEX CONCURRENTLY idx_specimen_parts_shelf on specimen_parts(shelf) WHERE NOT shelf IS NULL;
CREATE INDEX CONCURRENTLY idx_specimen_parts_container on specimen_parts(container) WHERE NOT container IS NULL;
CREATE INDEX CONCURRENTLY idx_specimen_parts_sub_container on specimen_parts(sub_container) WHERE NOT sub_container IS NULL;
CREATE INDEX CONCURRENTLY idx_class_vernacular_names_community on class_vernacular_names(community);
CREATE INDEX CONCURRENTLY idx_multimedia_is_digital on multimedia(is_digital);
CREATE INDEX CONCURRENTLY idx_multimedia_type on multimedia(type);
CREATE INDEX CONCURRENTLY idx_collections_fields_visibilities_searchable on collections_fields_visibilities(searchable) WHERE searchable is true;
CREATE INDEX CONCURRENTLY idx_collections_fields_visibilities_visible on collections_fields_visibilities(visible) WHERE visible is true;
CREATE INDEX CONCURRENTLY idx_record_visibilities_visible on record_visibilities(visible) WHERE visible is true;
CREATE INDEX CONCURRENTLY idx_users_workflow_user_status on users_workflow(user_ref, status);
CREATE INDEX CONCURRENTLY idx_my_preferences_user_category on my_preferences(user_ref, category);
CREATE INDEX CONCURRENTLY idx_collections_collection_type on collections(collection_type);

/*** @TODO: GiST Indexes created for array manipulations ***/

-- !!! Additional modules will have to be installed if this functionality is needed !!!


/*** @TODO:Additional BTree Indexes created to fasten application ***/

/*** FullText ***/
CREATE INDEX CONCURRENTLY idx_words_trgm ON words USING gin(word gin_trgm_ops);
CREATE INDEX CONCURRENTLY idx_words_table_field on words(referenced_relation,field_name);

/*** Dates ***/

CREATE INDEX CONCURRENTLY idx_igs_ig_date on igs(ig_date, ig_date_mask);
CREATE INDEX CONCURRENTLY idx_expeditions_expedition_from_date on expeditions(expedition_from_date, expedition_from_date_mask);
CREATE INDEX CONCURRENTLY idx_expeditions_expedition_to_date on expeditions(expedition_to_date, expedition_to_date_mask);

