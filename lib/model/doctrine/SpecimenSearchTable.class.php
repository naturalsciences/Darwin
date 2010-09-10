<?php


class SpecimenSearchTable extends Doctrine_Table
{
    protected static $widget_flat_array = array(
      'collection_ref' => 'refCollection',
      'gtu_ref' => 'refGtu' ,
      'gtu_code' => 'refGtu' ,
      'gtu_from_date' => 'refGtu' ,
      'gtu_to_date' => 'refGtu' ,
      'gtu_tag_values_indexed' => 'refGtu' ,
      'Tags' => 'refGtu' ,
      'taxon_name' => 'refTaxon' ,
      'taxon_level_ref' => 'refTaxon' ,
      'litho_name' => 'refLitho' ,
      'litho_level_ref' => 'refLitho' ,
      'litho_level_name' => 'refLitho' ,
      'chrono_name' => 'refChrono' ,
      'chrono_level_ref' => 'refChrono' ,
      'chrono_level_name' => 'refChrono' ,
      'lithology_name' => 'refLithology' ,
      'lithology_level_ref' => 'refLithology' ,
      'lithology_level_name' => 'refLithology' ,
      'mineral_name' => 'refMineral' ,
      'mineral_level_ref' => 'refMineral',
      'mineral_level_name' => 'refMineral',
      'Codes' => 'codes',
      'sex' => 'sex',
      'stage' => 'stage',
      'status' => 'status',
      'social' => 'social',
      'rockform' => 'rockform',
    );

    public static function getInstance()
    {
        return Doctrine_Core::getTable('SpecimenSearch');
    }

    public function getRequiredWidget($criterias, $user, $category)
    {
      $req_widget = array() ;
      foreach($criterias as $key => $fields)
      {
        if ($key == "rec_per_page") continue ;
        if($key == "gtu_from_date" && $fields == array('day'=>'','month'=>'','year'=>'')) continue;
        if($key == "gtu_to_date" && $fields == array('day'=>'','month'=>'','year'=>'')) continue;
        if ($fields == "") continue ;
        if(isset(self::$widget_flat_array[$key]))
        { 
          $req_widget[self::$widget_flat_array[$key]] = 1 ;
        }
      }
      Doctrine::getTable('MyWidgets')->forceWidgetOpened($user, $category ,array_keys($req_widget));
    }

    /**
    * Fetch all specimens by an array of ids
    * @param array $ids Ids of specimen to search
    * @return Doctrine_collection
    */
    public function getByMultipleIds(array $ids, $type = "specimens")
    {
      if( empty($ids))
        return $ids;
      $this->setView('specimen');
      $q = Doctrine_Query::create()
        ->select('s.spec_ref, s.taxon_name')
        ->from('SpecimenSearch s')
        ->wherein('s.spec_ref', $ids)
        ->orderBy('spec_ref');
      return $q->execute();
    }

    public function setView($type)
    {
      $conn = Doctrine_Manager::connection();
      $conn->exec("
        CREATE OR REPLACE TEMP VIEW darwin_flat as select 
MIN(id) as id,
spec_ref,
category,
collection_ref,
collection_type,
collection_code,
collection_name,
collection_institution_ref,
collection_institution_formated_name,
collection_institution_formated_name_ts,
collection_institution_formated_name_indexed,
collection_institution_sub_type,
collection_main_manager_ref,
collection_main_manager_formated_name,
collection_main_manager_formated_name_ts,
collection_main_manager_formated_name_indexed,
collection_parent_ref,
collection_path,
expedition_ref,
expedition_name,
expedition_name_ts,
expedition_name_indexed,
station_visible,
gtu_ref,
gtu_code,
gtu_parent_ref,
gtu_path,
gtu_from_date_mask,
gtu_from_date,
gtu_to_date_mask,
gtu_to_date,
gtu_tag_values_indexed,
gtu_country_tag_value,
taxon_ref,
taxon_name,
taxon_name_indexed,
taxon_name_order_by,
taxon_level_ref,
taxon_level_name,
taxon_status,
taxon_path,
taxon_parent_ref,
taxon_extinct,
litho_ref,
litho_name,
litho_name_indexed,
litho_name_order_by,
litho_level_ref,
litho_level_name,
litho_status,
litho_path,
litho_parent_ref,
chrono_ref,
chrono_name,
chrono_name_indexed,
chrono_name_order_by,
chrono_level_ref,
chrono_level_name,
chrono_status,
chrono_path,
chrono_parent_ref,
lithology_ref,
lithology_name,
lithology_name_indexed,
lithology_name_order_by,
lithology_level_ref,
lithology_level_name,
lithology_status,
lithology_path,
lithology_parent_ref,
mineral_ref,
mineral_name,
mineral_name_indexed,
mineral_name_order_by,
mineral_level_ref,
mineral_level_name,
mineral_status,
mineral_path,
mineral_parent_ref,
host_taxon_ref,
host_relationship,
host_taxon_name,
host_taxon_name_indexed,
host_taxon_name_order_by,
host_taxon_level_ref,
host_taxon_level_name,
host_taxon_status,
host_taxon_path,
host_taxon_parent_ref,
host_taxon_extinct,
ig_ref,
ig_num,
ig_num_indexed,
ig_date_mask,
ig_date,
acquisition_category,
acquisition_date_mask,
acquisition_date,
specimen_count_min,
specimen_count_max,
with_types

FROM public.darwin_flat 
GROUP BY 
spec_ref,
category,
collection_ref,
collection_type,
collection_code,
collection_name,
collection_institution_ref,
collection_institution_formated_name,
collection_institution_formated_name_ts,
collection_institution_formated_name_indexed,
collection_institution_sub_type,
collection_main_manager_ref,
collection_main_manager_formated_name,
collection_main_manager_formated_name_ts,
collection_main_manager_formated_name_indexed,
collection_parent_ref,
collection_path,
expedition_ref,
expedition_name,
expedition_name_ts,
expedition_name_indexed,
station_visible,
gtu_ref,
gtu_code,
gtu_parent_ref,
gtu_path,
gtu_from_date_mask,
gtu_from_date,
gtu_to_date_mask,
gtu_to_date,
gtu_tag_values_indexed,
gtu_country_tag_value,
taxon_ref,
taxon_name,
taxon_name_indexed,
taxon_name_order_by,
taxon_level_ref,
taxon_level_name,
taxon_status,
taxon_path,
taxon_parent_ref,
taxon_extinct,
litho_ref,
litho_name,
litho_name_indexed,
litho_name_order_by,
litho_level_ref,
litho_level_name,
litho_status,
litho_path,
litho_parent_ref,
chrono_ref,
chrono_name,
chrono_name_indexed,
chrono_name_order_by,
chrono_level_ref,
chrono_level_name,
chrono_status,
chrono_path,
chrono_parent_ref,
lithology_ref,
lithology_name,
lithology_name_indexed,
lithology_name_order_by,
lithology_level_ref,
lithology_level_name,
lithology_status,
lithology_path,
lithology_parent_ref,
mineral_ref,
mineral_name,
mineral_name_indexed,
mineral_name_order_by,
mineral_level_ref,
mineral_level_name,
mineral_status,
mineral_path,
mineral_parent_ref,
host_taxon_ref,
host_relationship,
host_taxon_name,
host_taxon_name_indexed,
host_taxon_name_order_by,
host_taxon_level_ref,
host_taxon_level_name,
host_taxon_status,
host_taxon_path,
host_taxon_parent_ref,
host_taxon_extinct,
ig_ref,
ig_num,
ig_num_indexed,
ig_date_mask,
ig_date,
acquisition_category,
acquisition_date_mask,
acquisition_date,
specimen_count_min,
specimen_count_max,
with_types
 ;
      ");
    }
}
