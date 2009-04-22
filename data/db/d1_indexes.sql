create index idx_loc_full_name on tbl_locations (fullToIndex(loc_full_name));
create index idx_cou_name on tbl_countries (fullToIndex(cou_name));
create index idx_prv_name on tbl_provinces (fullToIndex(prv_name));
create index idx_low_lca_name on tbl_location_categories (lower(lca_name));
create index idx_low_lsc_name on tbl_location_sub_categories (lower(lsc_name));
