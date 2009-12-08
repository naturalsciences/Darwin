<?php

/**
 * CatalogueProperties form.
 *
 * @package    form
 * @subpackage CatalogueProperties
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CataloguePropertiesForm extends BaseCataloguePropertiesForm
{
  public function configure()
  {
    unset(
      $this['property_sub_type_indexed'],
      $this['property_qualifier_indexed'],
      $this['property_method_indexed'],
      $this['property_tool_indexed'],
      $this['date_from_mask'],
      $this['date_to_mask'],
      $this['id']
    );

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal)).'/1/1 0:0:0');
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal)).'/12/31 23:59:59');
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['date_from'] = new widgetFormJQueryFuzzyDate(array('culture'=>  'en',  //@TODO change
                                                                               'image'=>'/images/calendar.gif', 
                                                                               'format' => '%day%/%month%/%year%', 
                                                                               'years' => $years, ),
                                                                         array('class' => 'from_date')
                                                                        );
    $this->widgetSchema['date_to'] = new widgetFormJQueryFuzzyDate(array('culture'=> 'en',  //@TODO change
                                                                             'image'=>'/images/calendar.gif', 
                                                                             'format' => '%day%/%month%/%year%', 
                                                                             'years' => $years, ),
                                                                       array('class' => 'to_date')
                                                                      );

    $this->validatorSchema['date_from'] = new fuzzyDateValidator(array(
      'required' => false,
      'from_date' => true,
      'min' => $minDate,
      'max' => $maxDate,
      'empty_value' => $dateLowerBound,
    ),
    array('invalid' => 'Invalid date "from"')
    );

    $this->validatorSchema['date_to'] = new fuzzyDateValidator(array(
	'required' => false,
	'from_date' => false,
	'min' => $minDate,
	'max' => $maxDate,
	'empty_value' => $dateUpperBound,
    ),
    array('invalid' => 'Invalid date "to"')
    );

     $this->validatorSchema->setPostValidator(
	new sfValidatorSchemaCompare(
	  'date_from',
	  '<=', 
          'date_to', 
          array('throw_global_error' => true),
	  array('invalid'=>'The "from" date cannot be above the "to" date.')
	)
      );


    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['property_qualifier'] = new sfWidgetFormInput();
    $this->widgetSchema['property_type'] = new sfWidgetFormChoice(array(
      'choices' =>  CommentsTable::getNotionsFor('taxonomy'),  //@TODO remove this! use $this->options['table']
    ));
    $this->widgetSchema['property_sub_type'] = new sfWidgetFormChoice(array(
      'choices' =>  CommentsTable::getNotionsFor('taxonomy'),  
    ));
    $this->widgetSchema['property_accuracy_unit'] = new sfWidgetFormChoice(array(
      'choices' =>  array('cm','mm','...'),  
    ));
    $this->widgetSchema['property_unit'] = new sfWidgetFormChoice(array(
      'choices' =>  array('cm','mm','...'),  
    ));
    $this->widgetSchema['property_method'] = new sfWidgetFormInput();
    $this->widgetSchema['property_tool'] = new sfWidgetFormInput();

  }
}