
insert into people (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, sort_string, end_date_day_indexed, end_date_month_indexed, end_date_year_indexed ) VALUES
(1, false, 'Royal Belgian Institute of Natural Sciences', 'royalbelgianinstituteofnaturalsciences', to_tsvector('Royal Belgian Institute of Natural Sciences'),  'Royal Belgian Institute of Natural Sciences', 0, 0, 0, 'royalbelgianinstituteofnaturalscienc', 0, 0,0 );
insert into users (id, is_physical, formated_name, formated_name_indexed, formated_name_ts, family_name, given_name, birth_date_day_indexed, birth_date_month_indexed, birth_date_year_indexed, gender, sort_string) VALUES (1, true, 'Doe John', 'doejohn', to_tsvector('Doe John'), 'Doe', 'John', 0, 0, 0, 'M', 'doejohn');
insert into collections (id, code, name, main_manager_ref, institution_ref, parent_collection_ref, path) VALUES ( 1, 'vert', 'vertebrates', 1, 1, null, '');
insert into collections (id, code, name, main_manager_ref, institution_ref, parent_collection_ref, path,code_auto_increment) VALUES ( 2, 'mam', 'mamalia', 1, 1, 1, '1',true);

insert into collections_admin (collection_ref, user_ref) VALUES (1,1);