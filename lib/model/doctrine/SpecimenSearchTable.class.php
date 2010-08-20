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
        if ($fields == "") continue ;

        if(isset(self::$widget_flat_array[$key]))
        { 
          $req_widget[self::$widget_flat_array[$key]] = 1 ;
        }
      }
      Doctrine::getTable('MyWidgets')->forceWidgetOpened($user, $category ,array_keys($req_widget));
    }
}
