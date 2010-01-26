<?php

/**
 * People form.
 *
 * @package    form
 * @subpackage People
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PeopleForm extends BasePeopleForm
{
  public function configure()
  {
    unset(
      $this['is_physical'],
      $this['formated_name_indexed'],
      $this['formated_name_ts'],
      $this['sub_type'],
      $this['formated_name'],
      $this['birth_date_mask'], 
      $this['end_date_mask']
    );
    
    $this->widgetSchema['additional_names'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['title'] = new sfWidgetFormInput();



    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['birth_date'] = new widgetFormJQueryFuzzyDate(
      array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'from_date')                
    );      
                                      
    $this->widgetSchema['end_date'] = new widgetFormJQueryFuzzyDate(
      array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'to_date')                
    );      

    $this->validatorSchema['birth_date'] = new fuzzyDateValidator(
      array(
        'required' => false,                       
        'from_date' => true,                       
        'min' => $minDate,                         
        'max' => $maxDate,
        'empty_value' => $dateLowerBound,
      ),
      array('invalid' => 'Date provided is not valid')
    );

    $this->validatorSchema['end_date'] = new fuzzyDateValidator(
      array(
        'required' => false,
        'from_date' => false,
        'min' => $minDate,
        'max' => $maxDate,
        'empty_value' => $dateUpperBound,
      ),
      array('invalid' => 'Date provided is not valid')
    );

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare(
        'birth_date',
        '<=',
        'end_date',
        array('throw_global_error' => true),
        array('invalid'=>'The birth date cannot be above the "end" date.')
      )
    );
  }
}