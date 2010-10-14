<?php

/**
 * SpecimenSearch filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SpecimenSearchFormFilter extends BaseSpecimenSearchFormFilter
{
  public function configure()
  {


      $this->useFields(array('building','floor','room','row','shelf','gtu_code','gtu_from_date','gtu_to_date',
        'taxon_name', 'taxon_level_ref', 'litho_name', 'litho_level_ref', 'litho_level_name', 'chrono_name', 'chrono_level_ref',
        'chrono_level_name', 'lithology_name', 'lithology_level_ref', 'lithology_level_name', 'mineral_name', 'mineral_level_ref',
        'mineral_level_name'));

    $this->addPagerItems();

    $this->widgetSchema['gtu_code'] = new sfWidgetFormInputText();
    $this->widgetSchema['taxon_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['taxon_level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'taxonomy'))),
        'add_empty' => 'All'
      ));

    $this->widgetSchema['lithology_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['lithology_level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'lithology'))),
        'add_empty' => 'All'
      ));
      
    $this->widgetSchema['litho_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['litho_level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'lithostratigraphy'))),
        'add_empty' => 'All'
      ));


    $this->widgetSchema['chrono_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['chrono_level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'chronostratigraphy'))),
        'add_empty' => 'All'
      )); 
           
    $this->widgetSchema['mineral_name'] = new sfWidgetFormInputText(array(), array('class'=>'medium_size'));
    $this->widgetSchema['mineral_level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes','parameters'=>array(array('table'=>'mineralogy'))),
        'add_empty' => 'All'
      ));      
    $this->widgetSchema['col_fields'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['collection_ref'] = new sfWidgetCollectionList(array('choices' => array()));
    $this->widgetSchema['collection_ref']->addOption('public_only',true);    
    $this->validatorSchema['collection_ref'] = new sfValidatorPass(); //Avoid duplicate the query
    $this->widgetSchema['spec_ids'] = new sfWidgetFormTextarea(array('label'=>'#ID list'));
    
    $this->validatorSchema['spec_ids'] = new sfValidatorString( array('required' => false,'trim' => true));
    $this->validatorSchema['col_fields'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                ));

    $this->validatorSchema['gtu_code'] = new sfValidatorString(array('required' => false,
                                                                 'trim' => true
                                                                )
                                                          );
    $this->validatorSchema['taxon_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['taxon_level_ref'] = new sfValidatorInteger(array('required' => false));
    $this->validatorSchema['chrono_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['chrono_level_ref'] = new sfValidatorInteger(array('required' => false));    
    $this->validatorSchema['litho_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['litho_level_ref'] = new sfValidatorInteger(array('required' => false));  
    $this->validatorSchema['lithology_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['lithology_level_ref'] = new sfValidatorInteger(array('required' => false));  
    $this->validatorSchema['mineral_name'] = new sfValidatorString(array('required' => false,
                                                                       'trim' => true
                                                                      )
                                                                );
    $this->validatorSchema['mineral_level_ref'] = new sfValidatorInteger(array('required' => false));              


    $minDate = new FuzzyDateTime(strval(min(range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')))).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max(range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')))).'/12/31'));
    $maxDate->setStart(false);
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $this->widgetSchema['tags'] = new sfWidgetFormInputText();
    $this->widgetSchema['gtu_from_date'] = new widgetFormJQueryFuzzyDate($this->getDateItemOptions(),
                                                                                array('class' => 'from_date')
                                                                               );
    $this->widgetSchema['gtu_to_date'] = new widgetFormJQueryFuzzyDate($this->getDateItemOptions(),
                                                                              array('class' => 'to_date')
                                                                             );
    $this->widgetSchema->setLabels(array('gtu_from_date' => 'Between',
                                         'gtu_to_date' => 'and',
                                        )
                                  );
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false, 'trim' => true));
    $this->validatorSchema['gtu_from_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                  'from_date' => true,
                                                                                  'min' => $minDate,
                                                                                  'max' => $maxDate, 
                                                                                  'empty_value' => $dateLowerBound,
                                                                                 ),
                                                                            array('invalid' => 'Date provided is not valid',)
                                                                           );
    $this->validatorSchema['gtu_to_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                'from_date' => false,
                                                                                'min' => $minDate,
                                                                                'max' => $maxDate,
                                                                                'empty_value' => $dateUpperBound,
                                                                               ),
                                                                          array('invalid' => 'Date provided is not valid',)
                                                                         );

    $subForm = new sfForm();
    $this->embedForm('Tags',$subForm);

    $this->widgetSchema['tools'] = new widgetFormSelectDoubleListFilterable(
      array(
            'choices' => Doctrine::getTable('CollectingTools')->fetchTools(),
            'label_associated'=>$this->getI18N()->__('Selected'),
            'label_unassociated'=>$this->getI18N()->__('Available')
           ));
    $this->widgetSchema['methods'] = new widgetFormSelectDoubleListFilterable(
      array(
            'choices' => Doctrine::getTable('CollectingMethods')->fetchMethods(),
            'label_associated'=>$this->getI18N()->__('Selected'),
            'label_unassociated'=>$this->getI18N()->__('Available')
           ));
    $this->validatorSchema['methods'] = new sfValidatorPass();
    $this->validatorSchema['tools'] = new sfValidatorPass();


    /* Define list of options available for different type of searches to provide */
    $what_searched = array('specimen'=>$this->getI18N()->__('Specimens'), 
                           'individual'=>$this->getI18N()->__('Individuals'), 
                           'part'=>$this->getI18N()->__('Parts'));
    $this->widgetSchema['what_searched'] = new sfWidgetFormChoice(array(
        'choices' => $what_searched,
    ));

    $this->validatorSchema['what_searched'] = new sfValidatorChoice(array('choices'=>array_keys($what_searched), 'required'=>false,'empty_value'=>'specimen'));
   
    /* Labels */
    $this->widgetSchema->setLabels(array('gtu_code' => 'Sampling Location code',
                                         'taxon_name' => 'Taxon',
                                         'taxon_level_ref' => 'Level',
                                         'what_searched' => 'What would you like to search ?',
                                         'code_ref_relation' => 'Code of'
                                        )
                                  );

  /**
  * Individuals Fields
  */
    $this->widgetSchema['type'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctTypeGroups',
        'method' => 'getTypeGroupFormated',
        'key_method' => 'getTypeGroup',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['type'] = new sfValidatorPass();

    $this->widgetSchema['sex'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSexes',
        'method' => 'getSexSearchFormated',
        'key_method' => 'getSex',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['sex'] = new sfValidatorPass();

    $this->widgetSchema['stage'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStages',
        'method' => 'getStageSearchFormated',
        'key_method' => 'getStage',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['stage'] = new sfValidatorPass();

    $this->widgetSchema['status'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStates',
        'method' => 'getStateSearchFormated',
        'key_method' => 'getState',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['status'] = new sfValidatorPass();

    $this->widgetSchema['social'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSocialStatuses',
        'method' => 'getSocialStatusSearchFormated',
        'key_method' => 'getSocialStatus',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['social'] = new sfValidatorPass();

    $this->widgetSchema['rockform'] = new sfWidgetFormDoctrineChoice(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctRockForms',
        'method' => 'getRockFormSearchFormated',
        'key_method' => 'getRockForm',
        'multiple' => true,
        'expanded' => true,
        'add_empty' => false,
    ));
    $this->validatorSchema['rockform'] = new sfValidatorPass();

    $this->widgetSchema['container'] = new sfWidgetFormInput();
    $this->validatorSchema['container'] = new sfValidatorString(array('required' => false));

    $this->widgetSchema['sub_container'] = new sfWidgetFormInput();
    $this->validatorSchema['sub_container'] = new sfValidatorString(array('required' => false));




    $this->widgetSchema['building'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctBuildings',
      'method' => 'getBuildings',
      'key_method' => 'getBuildings',
      'add_empty' => true,
    ));
    $this->validatorSchema['building'] = new sfValidatorString(array('required' => false));

    $this->widgetSchema['floor'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctFloors',
      'method' => 'getFloors',
      'key_method' => 'getFloors',
      'add_empty' => true,
    ));
    $this->validatorSchema['floor'] = new sfValidatorString(array('required' => false));

    $this->widgetSchema['row'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctRows',
      'method' => 'getRows',
      'key_method' => 'getRows',
      'add_empty' => true,
    ));
    $this->validatorSchema['row'] = new sfValidatorString(array('required' => false));

    $this->widgetSchema['room'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctRooms',
      'method' => 'getRooms',
      'key_method' => 'getRooms',
      'add_empty' => true,
    ));
    $this->validatorSchema['room'] = new sfValidatorString(array('required' => false));

    $this->widgetSchema['shelf'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctShelfs',
      'method' => 'getShelfs',
      'key_method' => 'getShelfs',
      'add_empty' => true,
    ));
    $this->validatorSchema['shelf'] = new sfValidatorString(array('required' => false));

    $subForm = new sfForm();
    $this->embedForm('Codes',$subForm);

     // LAT LON 
    $this->widgetSchema['lat_from'] = new sfWidgetForminput();
    $this->widgetSchema['lat_from']->setLabel('Latitude');
    $this->widgetSchema['lat_to'] = new sfWidgetForminput();
    $this->widgetSchema['lon_from'] = new sfWidgetForminput();
    $this->widgetSchema['lon_from']->setLabel('Longitude');
    $this->widgetSchema['lon_to'] = new sfWidgetForminput();

    $this->validatorSchema['lat_from'] = new sfValidatorNumber(array('required'=>false,'min' => '-90', 'max'=>'90'));
    $this->validatorSchema['lon_from'] = new sfValidatorNumber(array('required'=>false,'min' => '-180', 'max'=>'180'));
    $this->validatorSchema['lat_to'] = new sfValidatorNumber(array('required'=>false,'min' => '-90', 'max'=>'90'));
    $this->validatorSchema['lon_to'] = new sfValidatorNumber(array('required'=>false,'min' => '-180', 'max'=>'180'));

    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }


  public function addCodeValue($num)
  {
      $form = new CodeLineForm();
      $this->embeddedForms['Codes']->embedForm($num, $form);
      $this->embedForm('Codes', $this->embeddedForms['Codes']);
  }

  public function addLatLonColumnQuery($query, $values)
  {
    if( $values['lat_from'] != '' && $values['lon_from'] != '' && $values['lon_to'] != ''  && $values['lat_to'] != '' )
    {
      $query->andWhere('gtu_location && ST_SetSRID(ST_MakeBox2D(ST_Point('.$values['lon_from'].', '.$values['lat_from'].'),
        ST_Point('.$values['lon_to'].', '.$values['lat_to'].')),4326)');
    }
    return $query;
  }

  public function addContainerColumnQuery($query, $field, $val)
  {
    if(trim($val) != '')
    {
      $values = explode(' ',$val);
      $query_value = array();
      foreach($values as $value)
      {
        if(trim($value) != '')
          $query_value[] = '%'.strtolower($value).'%';
      }
      $query_array = array_fill(0,count($query_value),'lower(container) like ?');
      $query->andWhere( implode(' or ',$query_array) ,$query_value);
    }
    return $query ;
  }

  public function addSubContainerColumnQuery($query, $field, $val)
  {
    if(trim($val) != '')
    {
      $values = explode(' ',$val);
      $query_value = array();
      foreach($values as $value)
      {
        if(trim($value) != '')
          $query_value[] = '%'.strtolower($value).'%';
      }
      $query_array = array_fill(0,count($query_value),'lower(sub_container) like ?');
      $query->andWhere( implode(' or ',$query_array) ,$query_value);
    }
    return $query ;
  }

  public function addToolsColumnQuery($query, $field, $val)
  {
    if($val != '' && is_array($val) && !empty($val))
    {
      $query->andWhere('spec_ref in (select fct_search_tools (?))',implode(',', $val));
    }
    return $query ;
  }

  public function addMethodsColumnQuery($query, $field, $val)
  {
    if($val != '' && is_array($val) && !empty($val))
    {
      $query->andWhere('spec_ref in (select fct_search_methods (?))',implode(',', $val));
    }
    return $query ;
  }

  public function addSexColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_sex',$val);
      else
        $query->andWhere('individual_sex = ?',$val);
    }
    return $query ;
  }

  public function addTypeColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_type_search',$val);
      else
        $query->andWhere('individual_type_search = ?',$val);
    }
    return $query ;
  }

  public function addStageColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_stage',$val);
      else
        $query->andWhere('individual_stage = ?',$val);
    }
    return $query ;
  }

  public function addStatusColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_state',$val);
      else
        $query->andWhere('individual_state = ?',$val);
    }
    return $query ;
  }

  public function addSocialColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_social_status',$val);
      else
        $query->andWhere('individual_social_status = ?',$val);
    }
    return $query ;
  }

  public function addRockformColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('individual_rock_form',$val);
      else
        $query->andWhere('individual_rock_form = ?',$val);
    }
    return $query ;
  }

  public function addBuildingColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('building',$val);
      else
        $query->andWhere('building = ?',$val);
    }
    return $query ;
  }

  public function addFloorColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('floor',$val);
      else
        $query->andWhere('floor = ?',$val);
    }
    return $query ;
  }

  public function addRoomColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('room',$val);
      else
        $query->andWhere('room = ?',$val);
    }
    return $query ;
  }

  public function addRowColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('row',$val);
      else
        $query->andWhere('row = ?',$val);
    }
    return $query ;
  }

  public function addShelfColumnQuery($query, $field, $val)
  {
    if($val != '')
    {
      if(is_array($val))
        $query->andWhereIn('shelf',$val);
      else
        $query->andWhere('shelf = ?',$val);
    }
    return $query ;
  }

  public function addTagsColumnQuery($query, $field, $val)
  {
    $alias = $query->getRootAlias();
    $conn_MGR = Doctrine_Manager::connection();
    $tagList = '';

    foreach($val as $line)
    {
      $line_val = $line['tag'];
      if( $line_val != '')
      {
        $tagList = $conn_MGR->quote($line_val, 'string');
        $query->andWhere("gtu_tag_values_indexed && getTagsIndexedAsArray($tagList)");
      }
    }
    return $query ;
  }

  public function addCodesColumnQuery($query, $field, $val)
  {   

    $str_params = '';
    $str_params_part = '' ;
    $params = array();
    $params_part = array() ;
    foreach($val as $i => $code)
    {
      if(empty($code)) continue;
      if($code['referenced_relation'] == 'specimens')
      {
        if($str_params != '')
          $str_params .= ',';
        $str_params .= '?,?,?,?,?';
        $params[] = $code['category'];
        $params[] = $code['code_part'];
        $params[] = $code['code_from'];
        $params[] = $code['code_to'];
        $params[] = $code['referenced_relation'];
      }
      else
      {
        if($str_params_part != '')
          $str_params_part .= ',';
        $str_params_part .= '?,?,?,?,?';
        $params_part[] = $code['category'];
        $params_part[] = $code['code_part'];
        $params_part[] = $code['code_from'];
        $params_part[] = $code['code_to'];
        $params_part[] = $code['referenced_relation'];
      }
      
    }
    if(! empty($params)) 
    {
      $query->addWhere("spec_ref in (select fct_searchCodes($str_params) )", $params);      
    }
    if(! empty($params_part)) 
    {
      $query->addWhere("part_ref in (select fct_searchCodes($str_params_part) )", $params_part);      
    }    
    return $query ;
  }

  public function addGtuCodeColumnQuery($query, $field, $val)
  {
    if($val != '')
      $query->andWhere("LOWER(gtu_code) like ?", strtolower('%'.$val.'%'));
    return $query ;  
  }

  public function addGtuTagValue($num)
  {
      $form = new TagLineForm(null,array('num'=>$num));
      $this->embeddedForms['Tags']->embedForm($num, $form);
      $this->embedForm('Tags', $this->embeddedForms['Tags']);
  }
  
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['Codes'])&& is_array($taintedValues['Codes']))
    {

      foreach($taintedValues['Codes'] as $key=>$newVal)
      {
        if (!isset($this['Codes'][$key]))
        {
          $this->addCodeValue($key);
        }
      }
    }
    else $this->offsetUnset('Codes') ;

    if(isset($taintedValues['collection_ref']))
      $this->widgetSchema['collection_ref']->addOption('listCheck',$taintedValues['collection_ref']) ;
    if(isset($taintedValues['Tags'])&& is_array($taintedValues['Tags']))
    {
      foreach($taintedValues['Tags'] as $key=>$newVal)
      {
        if (!isset($this['Tags'][$key]))
        {
          $this->addGtuTagValue($key);
        }
      }
    }
    else $this->offsetUnset('Tags') ;
    parent::bind($taintedValues, $taintedFiles);
  }
  
  public function addSpecIdsColumnQuery($query, $field, $val)
  {
    $ids = explode(',', $val);
    $clean_ids =array();
    foreach($ids as $id)
    {
      if(ctype_digit($id))
        $clean_ids[] = $id;
    }
    
    if(! empty($clean_ids))
    {
      if($this->getValue('what_searched') == 'specimen')
        $query->andWhereIn("spec_ref", $clean_ids);
      elseif($this->getValue('what_searched') == 'individual')
        $query->andWhereIn("individual_ref", $clean_ids);
      else
        $query->andWhereIn("part_ref", $clean_ids);
    }
    return $query ;
  }

  public function addCollectionRefColumnQuery($query, $field, $val)
  {
    if (count($val) > 0) 
    {
      $query->andWhereIn('collection_ref',$val) ;
    }
    return $query;
  }

  public function doBuildQuery(array $values)
  {
    $fields = SpecimenSearchTable::getFieldsByType();

    if($values['what_searched'] == 'specimen')
    {
      $str = '';
      foreach($fields['specimens'] as $fld)
      {
        $str .= ' dummy_first( '. $fld .' ) as '.$fld.' ,' ;
      }
      $query = Doctrine_Query::create()
        ->from('SpecimenSearch s')
        ->groupBy('spec_ref')
        ->select($str . ' MIN(id) as id');

    }
    elseif($values['what_searched'] == 'individual')
    {
      $str = '';
      $array_fld = array_merge($fields['specimens'],$fields['individuals']);
      foreach($array_fld as $fld)
      {
        $str .= ' dummy_first( '. $fld .' ) as '.$fld.' ,' ;
      }

      $query = Doctrine_Query::create()
        ->from('IndividualSearch s')
        ->select($str .' MIN(id) as id,  false as with_types')
        ->andWhere('individual_ref != 0 ')
        ->groupBy('individual_ref');
    }
    else
    {
      $array_fld = array_merge($fields['specimens'],$fields['individuals']);
      $array_fld = array_merge($array_fld,$fields['parts']);
      $str = implode(', ',$array_fld);
      $query = Doctrine_Query::create()
        ->select($str.' , false as with_types')
        ->andWhere('part_ref != 0 ')
        ->from('PartSearch s');
    }

    $this->options['query'] = $query;
    $query = parent::doBuildQuery($values);

    if ($values['taxon_level_ref'] != '') $query->andWhere('taxon_level_ref = ?', intval($values['taxon_level_ref']));
    if ($values['chrono_level_ref'] != '') $query->andWhere('chrono_level_ref = ?', intval($values['chrono_level_ref']));
    if ($values['litho_level_ref'] != '') $query->andWhere('litho_level_ref = ?', intval($values['litho_level_ref']));    
    if ($values['lithology_level_ref'] != '') $query->andWhere('lithology_level_ref = ?', intval($values['lithology_level_ref']));
    if ($values['mineral_level_ref'] != '') $query->andWhere('mineral_level_ref = ?', intval($values['mineral_level_ref']));
    $this->addLatLonColumnQuery($query, $values);
    $this->addNamingColumnQuery($query, 'taxonomy', 'name_indexed', $values['taxon_name'],null,'taxon_name_indexed');
    $this->addNamingColumnQuery($query, 'chronostratigraphy', 'name_indexed', $values['chrono_name'],null,'chrono_name_indexed');
    $this->addNamingColumnQuery($query, 'lithostratigraphy', 'name_indexed', $values['litho_name'],null,'litho_name_indexed');
    $this->addNamingColumnQuery($query, 'lithology', 'name_indexed', $values['lithology_name'],null,'lithology_name_indexed');
    $this->addNamingColumnQuery($query, 'mineralogy', 'name_indexed', $values['mineral_name'],null,'mineral_name_indexed');
    $fields = array('gtu_from_date', 'gtu_to_date');
    $this->addDateFromToColumnQuery($query, $fields, $values['gtu_from_date'], $values['gtu_to_date']);
    $query->limit($this->getCatalogueRecLimits());
    return $query;
  }

}
