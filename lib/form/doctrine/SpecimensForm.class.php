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
    
    unset($this['acquisition_date_mask']
         );

    /* Set default values */
    $this->setDefaults(array(
        'expedition_ref' => 0,
        'taxon_ref' => 0,
        'mineral_ref' => 0,
        'lithology_ref' => 0,
        'litho_ref' => 0,
        'chrono_ref' => 0,
        'gtu_ref' => 0,
        'host_taxon_ref' => 0,
    ));

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $maxDate->setStart(false);

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
       'method' => 'getName',
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
       'method' => 'getCode',
       'box_title' => $this->getI18N()->__('Choose Gtu unit'),
       'nullable' => true,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    /* Collecting method */
    $this->widgetSchema['collecting_method'] = new widgetFormSelectComplete(array(
        'model' => 'Specimens',
        'table_method' => 'getDistinctMethods',
        'method' => 'getMethod',
        'key_method' => 'getMethod',
        'add_empty' => true,
        'change_label' => 'Pick a method in the list',
        'add_label' => 'Add another method',
    ));
    
    /* Collecting tool */
    $this->widgetSchema['collecting_tool'] = new widgetFormSelectComplete(array(
        'model' => 'Specimens',
        'table_method' => 'getDistinctTools',
        'method' => 'getTool',
        'key_method' => 'getTool',
        'add_empty' => true,
        'change_label' => 'Pick a tool in the list',
        'add_label' => 'Add another tool',
    ));

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
    
    /* Validators */

    $this->validatorSchema['collection_ref'] = new sfValidatorInteger(array('required'=>true));

    $this->validatorSchema['expedition_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['taxon_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['chrono_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['litho_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['lithology_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['mineral_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

    $this->validatorSchema['gtu_ref'] = new sfValidatorInteger(array('required'=>false, 'empty_value'=>0));

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
        
    $this->validatorSchema->setPostValidator(
        new sfValidatorSchemaCompare('specimen_count_min', '<=', 'specimen_count_max',
            array(),
            array('invalid' => 'The min number ("%left_field%") must be lower or equal the max number ("%right_field%")' )
            )
        );
    $this->setDefault('accuracy', 1);
  }
}