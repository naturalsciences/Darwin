<?php

/**
 * Specimens form.
 *
 * @package    form
 * @subpackage Specimens
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensForm extends BaseSpecimensForm
{
  public function configure()
  {
    
    unset($this['acquisition_date_mask'], $this['multimedia_visible']
         );

    /* Set default values 
    * commented for now because it prevent specimen to be duplicated
    $this->setDefaults(array(
        'expedition_ref' => 0,
        'taxon_ref' => 0,
        'mineral_ref' => 0,
        'lithology_ref' => 0,
        'litho_ref' => 0,
        'chrono_ref' => 0,
        'gtu_ref' => 0,
        'host_taxon_ref' => 0,
    )); */

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $maxDate->setStart(false);
    $prefixes = Doctrine::getTable('Codes')->getDistinctSepVals();
    $suffixes = Doctrine::getTable('Codes')->getDistinctSepVals(false);

    /* Define name format */
    $this->widgetSchema->setNameFormat('specimen[%s]');

    /* Fields */
    /* Collection Reference */
    $this->widgetSchema['collection_ref'] = new widgetFormButtonRef(
      array('model' => 'Collections',
            'link_url' => 'collection/choose',
            'method' => 'getName',
            'box_title' => $this->getI18N()->__('Choose Collection'),
            'button_class'=>'',
           ),
      array('class'=>'inline',
           )
     );
    
    /* Expedition Reference */
    $this->widgetSchema['expedition_ref'] = new widgetFormButtonRef(array(
       'model' => 'Expeditions',
       'link_url' => 'expedition/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Expedition'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Taxonomy Reference */
    $this->widgetSchema['taxon_ref'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'link_url' => 'taxonomy/choose',
       'method' => 'getNameWithFormat',
       'box_title' => $this->getI18N()->__('Choose Taxon'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Chronostratigraphy Reference */
    $this->widgetSchema['chrono_ref'] = new widgetFormButtonRef(array(
       'model' => 'Chronostratigraphy',
       'link_url' => 'chronostratigraphy/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Chronostratigraphic unit'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Lithostratigraphy Reference */
    $this->widgetSchema['litho_ref'] = new widgetFormButtonRef(array(
       'model' => 'Lithostratigraphy',
       'link_url' => 'lithostratigraphy/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Lithostratigraphic unit'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Lithology Reference */
    $this->widgetSchema['lithology_ref'] = new widgetFormButtonRef(array(
       'model' => 'Lithology',
       'link_url' => 'lithology/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Lithologic unit'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Mineralogy Reference */
    $this->widgetSchema['mineral_ref'] = new widgetFormButtonRef(array(
       'model' => 'Mineralogy',
       'link_url' => 'mineralogy/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Mineralogic unit'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* IG number Reference */
    $this->widgetSchema['ig_ref'] = new widgetFormInputChecked(array('model' => 'Igs',
                                                                     'method' => 'getIgNum',
                                                                     'nullable' => true,
                                                                     'link_url' => 'igs/searchFor',
                                                                     'notExistingAddTitle' => $this->getI18N()->__('This I.G. number does not exist. Would you like to automatically insert it ?'),
                                                                     'notExistingAddValues' => array($this->getI18N()->__('Yes'),
                                                                                                     $this->getI18N()->__('No')
                                                                                                    ),
                                                                    )
                                                              );

    /* Gtu Reference */
    $this->widgetSchema['gtu_ref'] = new widgetFormButtonRef(array(
       'model' => 'Gtu',
       'link_url' => 'gtu/choose',
       'method' => 'getTagsWithCode',
       'box_title' => $this->getI18N()->__('Choose Sampling Location'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Host Reference */
    $this->widgetSchema['host_specimen_ref'] = new widgetFormButtonRef(array(
       'model' => 'Specimens',
       'link_url' => 'specimen/choose',
       'method' => 'getName',
       'box_title' => $this->getI18N()->__('Choose Host specimen'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    $this->widgetSchema['host_taxon_ref'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'link_url' => 'taxonomy/choose',
       'method' => 'getNameWithFormat',
       'box_title' => $this->getI18N()->__('Choose Host taxon'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    $this->widgetSchema['host_relationship'] = new widgetFormSelectComplete(array(
        'model' => 'Specimens',
        'table_method' => 'getDistinctHostRelationships',
        'method' => 'getHostRelationship',
        'key_method' => 'getHostRelationship',
        'add_empty' => true,
        'change_label' => 'Pick a relationship in the list',
        'add_label' => 'Add another relationship',
    ));

    /* Collecting methods */

    $this->widgetSchema['collecting_methods_list'] = new widgetFormSelectDoubleListFilterable(
      array(
            'choices' => Doctrine::getTable('CollectingMethods')->fetchMethods(),
            'label_associated'=>$this->getI18N()->__('Selected'),
            'label_unassociated'=>$this->getI18N()->__('Available'),
            'add_active'=>true,
            'add_url'=>'methods_and_tools/addMethod'
           )
    );
    $this->validatorSchema['collecting_methods_list'] = new sfValidatorChoice(array('choices' => array_keys(Doctrine::getTable('CollectingMethods')->fetchMethods()), 'required' => false, 'multiple' => true));

    /* Collecting tools */

    $this->widgetSchema['collecting_tools_list'] = new widgetFormSelectDoubleListFilterable(
      array(
            'choices' => Doctrine::getTable('CollectingTools')->fetchTools(),
            'label_associated'=>$this->getI18N()->__('Selected'),
            'label_unassociated'=>$this->getI18N()->__('Available'),
            'add_active'=>true,
            'add_url'=>'methods_and_tools/addTool'
           )
    );
    $this->validatorSchema['collecting_tools_list'] = new sfValidatorChoice(array('choices' => array_keys(Doctrine::getTable('CollectingTools')->fetchTools()), 'required' => false, 'multiple' => true));

    /* Acquisition categories */
    $this->widgetSchema['acquisition_category'] = new sfWidgetFormChoice(array(
      'choices' =>  SpecimensTable::getDistinctCategories(),
    ));

    $this->widgetSchema['acquisition_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                  'image'=>'/images/calendar.gif', 
                                                                                  'format' => '%day%/%month%/%year%', 
                                                                                  'years' => $years,
                                                                                  'empty_values' => $dateText,
                                                                                 ),
                                                                            array('class' => 'to_date')
                                                                           );

    $this->widgetSchema['accuracy'] = new sfWidgetFormChoice(array(
        'choices'  => array($this->getI18N()->__('exact'), $this->getI18N()->__('imprecise')),
        'expanded' => true,
    ));

    /* Accompanying elements sub form */

    $this->embedRelation('SpecimensAccompanying');
    
    $subForm = new sfForm();
    $this->embedForm('newSpecimensAccompanying',$subForm);
    $this->widgetSchema['accompanying'] = new sfWidgetFormInputHidden(array('default'=>1));

    /* Codes sub form */
    
    $subForm = new sfForm();
    $this->embedForm('Codes',$subForm);   
    foreach(Doctrine::getTable('Codes')->getCodesRelated('specimens', $this->getObject()->getId()) as $key=>$vals)
    {
      $form = new CodesForm($vals);
      $this->embeddedForms['Codes']->embedForm($key, $form);
    }
    //Re-embedding the container
    $this->embedForm('Codes', $this->embeddedForms['Codes']);

    $subForm = new sfForm();
    $this->embedForm('newCode',$subForm);

    $this->widgetSchema['prefix_separator'] = new sfWidgetFormChoice(array(
        'choices' => $prefixes
    ));
    $this->widgetSchema['prefix_separator']->setAttributes(array('class'=>'vvsmall_size'));

    $this->widgetSchema['suffix_separator'] = new sfWidgetFormChoice(array(
        'choices' => $suffixes
    ));
    $this->widgetSchema['suffix_separator']->setAttributes(array('class'=>'vvsmall_size'));

    $this->widgetSchema['code'] = new sfWidgetFormInputHidden(array('default'=>1));

    /* Collectors sub form */
    
    $subForm = new sfForm();
    $this->embedForm('Collectors',$subForm);   
    foreach(Doctrine::getTable('CataloguePeople')->getPeopleRelated('specimens','collector', $this->getObject()->getId()) as $key=>$vals)
    {
      $form = new PeopleAssociationsForm($vals);
      $this->embeddedForms['Collectors']->embedForm($key, $form);
    }
    //Re-embedding the container
    $this->embedForm('Collectors', $this->embeddedForms['Collectors']);

    $subForm = new sfForm();
    $this->embedForm('newCollectors',$subForm);
    
    $this->widgetSchema['collector'] = new sfWidgetFormInputHidden(array('default'=>1));
    
    /* Identifications sub form */
    
    $subForm = new sfForm();
    $this->embedForm('Identifications',$subForm);   
    foreach(Doctrine::getTable('Identifications')->getIdentificationsRelated('specimens', $this->getObject()->getId()) as $key=>$vals)
    {
      $form = new IdentificationsForm($vals);
      $this->embeddedForms['Identifications']->embedForm($key, $form);
    }
    //Re-embedding the container
    $this->embedForm('Identifications', $this->embeddedForms['Identifications']);

    $subForm = new sfForm();
    $this->embedForm('newIdentification',$subForm);


    $this->widgetSchema['ident'] = new sfWidgetFormInputHidden(array('default'=>1));

    /* Comments sub form */
    
    $subForm = new sfForm();
    $this->embedForm('Comments',$subForm);   
    foreach(Doctrine::getTable('comments')->findForTable('specimens', $this->getObject()->getId()) as $key=>$vals)
    {
      $form = new CommentsSubForm($vals,array('table' => 'specimens'));
      $this->embeddedForms['Comments']->embedForm($key, $form);
    }
    //Re-embedding the container
    $this->embedForm('Comments', $this->embeddedForms['Comments']);

    $subForm = new sfForm();
    $this->embedForm('newComments',$subForm);
    
    $this->widgetSchema['comment'] = new sfWidgetFormInputHidden(array('default'=>1));
    
    /* Labels */
    $this->widgetSchema->setLabels(array('host_specimen_ref' => 'Host specimen',
                                         'host_relationship' => 'Relationship',
                                         'host_taxon_ref' => 'Host taxon',
                                         'gtu_ref' => 'Sampling location',
                                         'station_visible' => 'Public sampling location ?'
                                        )
                                  );
    
    /* Validators */

    $this->validatorSchema['id'] = new sfValidatorInteger(array('required'=>false));

    $this->validatorSchema['collection_ref'] = new sfValidatorInteger(array('required'=>true));

    $this->validatorSchema['expedition_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['taxon_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['chrono_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['litho_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['lithology_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['mineral_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['gtu_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['host_specimen_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>null));

    $this->validatorSchema['host_taxon_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['acquisition_category'] = new sfValidatorChoice(array(
        'choices' => SpecimensTable::getDistinctCategories(),
        'required' => false,
        ));

    $this->validatorSchema['acquisition_date'] = new fuzzyDateValidator(array('required' => false,
                                                                              'from_date' => true,
                                                                              'min' => $minDate,
                                                                              'max' => $maxDate, 
                                                                              'empty_value' => $dateLowerBound,
                                                                             ),
                                                                        array('invalid' => 'Date provided is not valid',
                                                                             )
                                                                       );

    $this->validatorSchema['accuracy'] = new sfValidatorChoice(array(
        'choices' => array(0,1),
        'required' => false,
        ));

    $this->validatorSchema['prefix_separator'] = new sfValidatorChoice(array('choices' => array_keys($prefixes), 'required' => false));
    $this->validatorSchema['suffix_separator'] = new sfValidatorChoice(array('choices' => array_keys($suffixes), 'required' => false));
    $this->validatorSchema['collector'] = new sfValidatorPass();
    $this->validatorSchema['comment'] = new sfValidatorPass();    
    $this->validatorSchema['code'] = new sfValidatorPass();
    $this->validatorSchema['ident'] = new sfValidatorPass();
    $this->validatorSchema['accompanying'] = new sfValidatorPass();

    $this->validatorSchema->setPostValidator(
        new sfValidatorSchemaCompare('specimen_count_min', '<=', 'specimen_count_max',
            array(),
            array('invalid' => 'The min number ("%left_field%") must be lower or equal the max number ("%right_field%")' )
            )
        );
    $this->setDefault('accuracy', 1);
    
  }

  public function addCodes($num, $collectionId=null, $code=null)
  {
      $options = array('referenced_relation' => 'specimens');
      $form_options = array();
      if ($collectionId)
      {
        $collection = Doctrine::getTable('Collections')->findOneById($collectionId);
        if($collection)
        {
          $options['code_prefix'] = $collection->getCodePrefix();
          $options['code_prefix_separator'] = $collection->getCodePrefixSeparator();
          $options['code_suffix'] = $collection->getCodeSuffix();
          $options['code_suffix_separator'] = $collection->getCodeSuffixSeparator();
        }
      }
      if(!$code) $val = new Codes();
      else $val = $code ;
      $val->fromArray($options);
      $val->setRecordId($this->getObject()->getId());
      $form = new CodesForm($val);
      $this->embeddedForms['newCode']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newCode', $this->embeddedForms['newCode']);
  }


  public function addCollectors($num, $people_ref,$order_by=0)
  {
      $options = array('referenced_relation' => 'specimens', 'people_type' => 'collector', 'people_ref' => $people_ref, 'order_by' => $order_by);
      $val = new CataloguePeople();
      $val->fromArray($options);
      $val->setRecordId($this->getObject()->getId());
      $form = new PeopleAssociationsForm($val);
      $this->embeddedForms['newCollectors']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newCollectors', $this->embeddedForms['newCollectors']);
  }

  public function addSpecimensAccompanying($num, $obj=null)
  {
      $options = array('unit' => '%');
      if (!$obj) $val = new SpecimensAccompanying();
      else $val = $obj ;
      $val->fromArray($options);
      $val->Specimens = $this->getObject();
//       $val->setRecordId($this->getObject()->getId());
      $form = new SpecimensAccompanyingForm($val);
      $this->embeddedForms['newSpecimensAccompanying']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newSpecimensAccompanying', $this->embeddedForms['newSpecimensAccompanying']);
  }

  public function addComments($num, $obj=null)
  {
      $options = array('referenced_relation' => 'specimens', 'record_id' => $this->getObject()->getId());
      if (!$obj) $val = new Comments();
      else $val = $obj ;
      $val->fromArray($options);
      $val->setRecordId($this->getObject()->getId());
      $form = new CommentsSubForm($val,array('table' => 'specimens'));
      $this->embeddedForms['newComments']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newComments', $this->embeddedForms['newComments']);
  }
  
  public function addIdentifications($num, $order_by=0, $obj=null)
  {
      $options = array('referenced_relation' => 'specimens', 'order_by' => $order_by);
      if (!$obj) $val = new Identifications();
      else $val = $obj ;
      $val->fromArray($options);
      $val->setRecordId($this->getObject()->getId());
      $form = new IdentificationsForm($val);
      $this->embeddedForms['newIdentification']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newIdentification', $this->embeddedForms['newIdentification']);
  }

  public function reembedIdentifications ($identification, $identification_number)
  {
      $this->getEmbeddedForm('Identifications')->embedForm($identification_number, $identification);
      $this->embedForm('Identifications', $this->embeddedForms['Identifications']);
  }

  public function reembedNewIdentification ($identification, $identification_number)
  {
      $this->getEmbeddedForm('newIdentification')->embedForm($identification_number, $identification);
      $this->embedForm('newIdentification', $this->embeddedForms['newIdentification']);
  }

  protected function getFieldsByGroup()
  {
	return array(
	  'Acquisition' => array(
		'acquisition_category',
		'acquisition_date',
	  ),
	  'Expedition' => array(
		'expedition_ref',
	  ),
	  'Taxonomy' => array('taxon_ref'),
	  'Chrono' => array('chrono_ref'),
	  'Lithology' => array('lithology_ref'),
	  'Lithostratigraphy' => array('litho_ref'),
	  'Mineralogy' => array('mineral_ref'),

	  'Host' => array(
		'host_relationship',
		'host_specimen_ref',
		'host_taxon_ref',
	  ),
	  'Ig' => array(
		'ig_ref',
	  ),
	  'Gtu' => array(
		'gtu_ref',
		'station_visible',
	  ),
	  'Count' => array(
		'accuracy',
		'specimen_count_min',
		'specimen_count_max',
	  ),
/*	  'Collecting_meth' => array(
		'collecting_method',
	  ),
	  'Collecting_Tool' => array(
		'collecting_tool',
	  ),*/
	);
  }
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['accuracy']))
    {
      if($taintedValues['accuracy'] == 0 ) //exact
      {
	$taintedValues['specimen_count_max'] = $taintedValues['specimen_count_min'];
      }
    }

    if(isset($taintedValues['newCode']) && isset($taintedValues['code']))
    {
	foreach($taintedValues['newCode'] as $key=>$newVal)
	{
	  if (!isset($this['newCode'][$key]))
	  {
	    $this->addCodes($key);
	  }
          $taintedValues['newCode'][$key]['record_id'] = 0;
	}
    }
    if(isset($taintedValues['newCollectors']) && isset($taintedValues['collector']))
    {
     foreach($taintedValues['newCollectors'] as $key=>$newVal)
	{
	  if (!isset($this['newCollectors'][$key]))
	  {
	    $this->addCollectors($key,$newVal['people_ref'],$newVal['order_by']);
	  }
       $taintedValues['newCollectors'][$key]['record_id'] = 0;
	}
    }
    if(isset($taintedValues['newComments']) && isset($taintedValues['comment']))
    {
     foreach($taintedValues['newComments'] as $key=>$newVal)
	{
	  if (!isset($this['newComments'][$key]))
	  {
	    $this->addComments($key);
	  }
	  $taintedValues['newComments'][$key]['record_id'] = 0;
	}
    }
    if(isset($taintedValues['newSpecimensAccompanying']) && isset($taintedValues['accompanying']))
    {
     foreach($taintedValues['newSpecimensAccompanying'] as $key=>$newVal)
	{
	  if (!isset($this['newSpecimensAccompanying'][$key]))
	  {
	    $this->addSpecimensAccompanying($key);
	  }
	}
    }
    if(isset($taintedValues['newIdentification']) && isset($taintedValues['ident']))
    {
	foreach($taintedValues['newIdentification'] as $key=>$newVal)
	{
	  if (!isset($this['newIdentification'][$key]))
	  {
	    $this->addIdentifications($key);
            if(isset($taintedValues['newIdentification'][$key]['newIdentifier']))
            {
              foreach($taintedValues['newIdentification'][$key]['newIdentifier'] as $ikey=>$ival)
              {
                if(!isset($this['newIdentification'][$key]['newIdentifier'][$ikey]))
	              {
                  $identification = $this->getEmbeddedForm('newIdentification')->getEmbeddedForm($key);
                  $identification->addIdentifiers($ikey,$ival['people_ref'], $ival['order_by']);
                  $this->reembedNewIdentification($identification, $key);
                }
                $taintedValues['newIdentification'][$key]['newIdentifier'][$ikey]['record_id'] = 0;
              }
	    }
          }
	  elseif(isset($taintedValues['newIdentification'][$key]['newIdentifier']))
	  {

	    foreach($taintedValues['newIdentification'][$key]['newIdentifier'] as $ikey=>$ival)
	    {
	      if(!isset($this['newIdentification'][$key]['newIdentifier'][$ikey]))
	      {
		      $identification = $this->getEmbeddedForm('newIdentification')->getEmbeddedForm($key);
		      $identification->addIdentifiers($ikey,$ival['people_ref'], $ival['order_by']);
		      $this->reembedNewIdentification($identification, $key);
	      }
	      $taintedValues['newIdentification'][$key]['newIdentifier'][$ikey]['record_id'] = 0;
	    }
	  }
          $taintedValues['newIdentification'][$key]['record_id'] = 0;
	}
    }

    if(isset($taintedValues['Identifications']) && isset($taintedValues['ident']))
    {
	foreach($taintedValues['Identifications'] as $key=>$newval)
	{
          if(isset($newval['newIdentifier']))
            {
              foreach($taintedValues['Identifications'][$key]['newIdentifier'] as $ikey=>$ival)
              {
                if(!isset($this['Identifications'][$key]['newIdentifier'][$ikey]))
	        {
                  $identification = $this->getEmbeddedForm('Identifications')->getEmbeddedForm($key);
                  $identification->addIdentifiers($ikey,$ival['people_ref'], $ival['order_by']);
                  $this->reembedIdentifications($identification, $key);
                }
                $taintedValues['Identifications'][$key]['newIdentifier'][$ikey]['record_id'] = 0;
              }
            }
	}
    }

    if(!isset($taintedValues['code']))
    {
      $this->offsetUnset('Codes');
      unset($taintedValues['Codes']);
      $this->offsetUnset('newCode');
      unset($taintedValues['newCode']);
    }
    if(!isset($taintedValues['collector']))
    {
      $this->offsetUnset('Collectors');
      unset($taintedValues['Collectors']);
      $this->offsetUnset('newCollectors');
      unset($taintedValues['newCollectors']);
    }
    if(!isset($taintedValues['accompanying']))
    {
      $this->offsetUnset('SpecimensAccompanying');
      unset($taintedValues['SpecimensAccompanying']);
      $this->offsetUnset('newSpecimensAccompanying');
      unset($taintedValues['newSpecimensAccompanying']);
    }
    if(!isset($taintedValues['comment']))
    {
      $this->offsetUnset('Comments');
      unset($taintedValues['Comments']);
      $this->offsetUnset('newComments');
      unset($taintedValues['newComments']);
    }    
    if(!isset($taintedValues['ident']))
    {
      $this->offsetUnset('Identifications');
      unset($taintedValues['Identifications']);
      $this->offsetUnset('newIdentification');
      unset($taintedValues['newIdentification']);
    }

    parent::bind($taintedValues, $taintedFiles);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $forms && $this->getValue('ident'))
    {
	$value = $this->getValue('newIdentification');
	foreach($this->embeddedForms['newIdentification']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['value_defined']))
	  {
	    unset($this->embeddedForms['newIdentification'][$name]);
	  }
          else
          {
            $form->getObject()->setRecordId($this->getObject()->getId());
            $form->getObject()->save();
            $subvalue = $value[$name]['newIdentifier'];
            foreach($form->embeddedForms['newIdentifier']->getEmbeddedForms() as $subname => $subform)
            {
              if (!isset($subvalue[$subname]['people_ref']))
              {
                unset($form->embeddedForms['newIdentifier'][$subname]);
              }
              else
              {
                $subform->getObject()->setRecordId($form->getObject()->getId());
              }
            }
          }
	}
	$value = $this->getValue('Identifications');
	foreach($this->embeddedForms['Identifications']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['value_defined']))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['Identifications'][$name]);
	  }
          else
          {
            $subvalue = $value[$name]['newIdentifier'];
            foreach($form->embeddedForms['newIdentifier']->getEmbeddedForms() as $subname => $subform)
            {
              if (!isset($subvalue[$subname]['people_ref']))
              {
                unset($form->embeddedForms['newIdentifier'][$subname]);
              }
              else
              {
                $subform->getObject()->setRecordId($form->getObject()->getId());
              }
            }
            $subvalue = $value[$name]['Identifiers'];
            foreach($form->embeddedForms['Identifiers']->getEmbeddedForms() as $subname => $subform)
            {
              if (!isset($subvalue[$subname]['people_ref']))
              {
                $subform->getObject()->delete();
                unset($form->embeddedForms['Identifiers'][$subname]);
              }
            }
          }
	}
    }
    if (null === $forms && $this->getValue('code'))
    {
	$value = $this->getValue('newCode');
	$collectionId = $this->getValue('collection_ref');
        $collection = Doctrine::getTable('Collections')->findOneById($collectionId);
	foreach($this->embeddedForms['newCode']->getEmbeddedForms() as $name => $form)
	{
	  if(!isset($value[$name]['code']))
	  {
	    unset($this->embeddedForms['newCode'][$name]);
	  }
	  elseif($value[$name]['code']=='' && $value[$name]['code_prefix']=='' && $value[$name]['code_suffix']=='' && $collection)
	  {
	    if($collection->getCodeAutoIncrement())
            {
              $form->getObject()->setCode(Doctrine::getTable('Collections')->getAndUpdateLastCode($collectionId));
	      $form->getObject()->setRecordId($this->getObject()->getId());
            }
	    else
	    {
	      unset($this->embeddedForms['newCode'][$name]);
	    }
	  }
	  else
	  {
            if($value[$name]['code']=='' && $collection)
            {
              if($collection->getCodeAutoIncrement())
              {
                $form->getObject()->setCode(Doctrine::getTable('Collections')->getAndUpdateLastCode($collectionId));
              }
            }
            $form->getObject()->setRecordId($this->getObject()->getId());
	  }
	}
	$value = $this->getValue('Codes');
	foreach($this->embeddedForms['Codes']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['code']) || ($value[$name]['code_prefix']=='' && $value[$name]['code']=='' && $value[$name]['code_suffix']==''))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['Codes'][$name]);
	  }
	}
    }
    if (null === $forms && $this->getValue('collector'))
    {
	$value = $this->getValue('newCollectors');
	foreach($this->embeddedForms['newCollectors']->getEmbeddedForms() as $name => $form)
	{
	  if(!isset($value[$name]['people_ref']))
	    unset($this->embeddedForms['newCollectors'][$name]);
	  else
	    $form->getObject()->setRecordId($this->getObject()->getId());
	}
	$value = $this->getValue('Collectors');
	foreach($this->embeddedForms['Collectors']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['people_ref']))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['Collectors'][$name]);
	  }
	}
    }    
    if (null === $forms && $this->getValue('accompanying'))
    {
	$value = $this->getValue('newSpecimensAccompanying');
	foreach($this->embeddedForms['newSpecimensAccompanying']->getEmbeddedForms() as $name => $form)
	{
	  if(!isset($value[$name]['taxon_ref']) && !isset($value[$name]['mineral_ref']))
	    unset($this->embeddedForms['newSpecimensAccompanying'][$name]);
	}
	$value = $this->getValue('SpecimensAccompanying');
	foreach($this->embeddedForms['SpecimensAccompanying']->getEmbeddedForms() as $name => $form)
	{
	  if(!isset($value[$name]['taxon_ref']) && !isset($value[$name]['mineral_ref']))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['SpecimensAccompanying'][$name]);
	  }
	}
    }    
    if (null === $forms && $this->getValue('comment'))
    {
	$value = $this->getValue('newComments');
	foreach($this->embeddedForms['newComments']->getEmbeddedForms() as $name => $form)
	{
	  if(!isset($value[$name]['comment'] ))
	    unset($this->embeddedForms['newComments'][$name]);
	  else
	  {
	    $form->getObject()->setRecordId($this->getObject()->getId());
	  }
	}
	$value = $this->getValue('Comments');
	foreach($this->embeddedForms['Comments']->getEmbeddedForms() as $name => $form)
	{	
	  if (!isset($value[$name]['comment'] ))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['Comments'][$name]);
	  }
	}
    } 
    return parent::saveEmbeddedForms($con, $forms);
  }  
}
