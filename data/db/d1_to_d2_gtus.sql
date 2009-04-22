SET search_path TO "$user","darwin1",public;
BEGIN TRANSACTION;
INSERT INTO tags (label)
	(SELECT label_selection.label FROM
		(SELECT DISTINCT ON (preselection.label_indexed) preselection.label, preselection.label_indexed FROM
			(SELECT DISTINCT cou_name as label, fullToIndex(cou_name) as label_indexed FROM tbl_countries
			 UNION
			 SELECT DISTINCT prv_name as label, fullToIndex(prv_name) as label_indexed FROM tbl_provinces
			 UNION
			 SELECT DISTINCT loc_full_name as label, fullToIndex(loc_full_name) as label_indexed FROM tbl_locations
			) as preselection
			ORDER BY preselection.label_indexed
		) as label_selection
	);
INSERT INTO tag_groups (group_name, sub_group_name, tag_ref)
	(SELECT DISTINCT 'administrative area' as group_name, 'country' as sub_group_name, id as tag_ref
	 FROM tags
	 WHERE label_indexed IN (SELECT DISTINCT FullToIndex(cou_name)
				 FROM tbl_countries INNER JOIN tbl_locations ON cou_id_ctn = loc_country_code_nr
				)
	);
INSERT INTO tag_groups (group_name, sub_group_name, tag_ref)
	(SELECT DISTINCT 'administrative area' as group_name, 'province' as sub_group_name, id as tag_ref
	 FROM tags
	 WHERE label_indexed IN (SELECT DISTINCT FullToIndex(prv_name)
				 FROM tbl_provinces INNER JOIN tbl_locations ON prv_id_ctn = loc_province_nr
				)
	);
INSERT INTO tag_groups (group_name, sub_group_name, tag_ref)
	(SELECT distinct case when lca_name = 'Undefined' then 'Other' else lower(lca_name) end, lower(lsc_name), id
	 FROM (tbl_locations inner join tbl_location_categories on loc_category_nr = lca_id_ctn
			     inner join tbl_location_sub_categories on loc_sub_category_nr = lsc_id_ctn
	      ) inner join tags on fullToIndex(loc_full_name) = label_indexed
	 WHERE lower(lsc_name) not in ('country', 'province')
	);
CREATE OR REPLACE FUNCTION fct_cpy_gtu_d1_to_d2 () RETURNS integer
AS $$
DECLARE
	recordsInserted integer := 0;
	buffer integer;
BEGIN
	INSERT INTO gtu (id)
        	(SELECT DISTINCT ON (loc_indexed) id FROM
                	(SELECT loc_id_ctn as id, fullToIndex(loc_full_name) as loc_indexed
	                 FROM tbl_locations left join tbl_location_children_parents on loc_id_ctn = lcp_children_id_nr
        	         WHERE lcp_children_id_nr is null
                	) as first_selection
	        );
	IF FOUND THEN
		GET DIAGNOSTICS recordsInserted = ROW_COUNT;
		INSERT INTO gtu (id, parent_ref)
        		(SELECT DISTINCT ON (loc_indexed) id, parent_id FROM
                		(SELECT loc_id_ctn as id, child_locs.lcp_parent_id_nr as parent_id, fullToIndex(loc_full_name) as loc_indexed
		                 FROM tbl_locations inner join tbl_location_children_parents as child_locs on loc_id_ctn = child_locs.lcp_children_id_nr
        		         WHERE child_locs.lcp_parent_id_nr IN (select id from gtu)
                		) as first_selection
		        );
		IF FOUND THEN
			WHILE FOUND LOOP
				GET DIAGNOSTICS buffer = ROW_COUNT;
				recordsInserted := buffer + recordsInserted;
				INSERT INTO gtu (id, parent_ref)
				(SELECT DISTINCT ON (loc_indexed) id, parent_id FROM
			                (SELECT loc_id_ctn as id, child_locs.lcp_parent_id_nr as parent_id, fullToIndex(loc_full_name) as loc_indexed
			                 FROM tbl_locations inner join tbl_location_children_parents as child_locs on loc_id_ctn = child_locs.lcp_children_id_nr
			                 WHERE child_locs.lcp_parent_id_nr IN (select id from gtu)
			                   AND loc_id_ctn NOT IN (select id from gtu)
			                ) as first_selection
			        );
			END LOOP;
		END IF;
	END IF;
	return recordsInserted;
EXCEPTION
	WHEN OTHERS THEN ROLLBACK;
	recordsInserted := 0;
	RETURN recordsInserted;
END;
$$ LANGUAGE plpgsql;

SELECT 'INSERT 0 ' || fct_cpy_gtu_d1_to_d2();
ROLLBACK;
/*COMMIT;*/
END TRANSACTION;
