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
      $this['end_date_mask'],
      $this['activity_date_from_mask'], 
      $this['activity_date_to_mask']
    );
    
    $this->widgetSchema['title'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['additional_names'] = new sfWidgetFormInput();

    $this->widgetSchema['title']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['family_name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['given_name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['additional_names']->setAttributes(array('class'=>'medium_size'));

    $this->widgetSchema['db_people_type'] = new sfWidgetFormChoice(array(
      'choices'        => People::getTypes(),
      'renderer_class' => 'sfWidgetFormSelectDoubleList',
    ));
    $this->validatorSchema['db_people_type'] = new sfValidatorChoice(array('choices' => array_keys(People::getTypes()), 'required' => false, 'multiple' => true));

    $this->Postvalidators = array();
    $this->initiateActivityItems();
    $this->initiateBirthItems();

    $this->Postvalidators[] = new sfValidatorCallback(array('callback' => array($this, 'checkActivity')));
    $this->validatorSchema->setPostValidator(new sfValidatorAnd($this->Postvalidators));
  }

  public function checkActivity($validator, $values)
  {
    if(! empty($values['birth_date']) )
    {
	if($values['birth_date']->getMask() !=0 && $values['activity_date_from']->getMask() !=0)
	{
	  if($values['birth_date'] >= $values['activity_date_from'])
	  {
            $error = new sfValidatorError($validator, 'The start of the activity must be after the birth');
            // throw an error bound to the password field
            throw new sfValidatorErrorSchema($validator, array('activity_date_from' => $error));
	  }
	}
	if($values['end_date']->getMask() !=0 && $values['activity_date_to']->getMask() !=0)
	{
	  if($values['end_date'] <= $values['activity_date_to'])
	  {
            $error = new sfValidatorError($validator,'The end of the activity must be before the death');
            // throw an error bound to the password field
            throw new sfValidatorErrorSchema($validator, array('activity_date_to' => $error));
	  }
	}
    }
    return $values;
  }

  protected function initiateBirthItems()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['birth_date'] = new widgetFormJQueryFuzzyDate(
      array('culture'=> $this->getCurrentCulture(), 
            'image'=> '/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'from_date')                
    );      
                                      
    $this->widgetSchema['end_date'] = new widgetFormJQueryFuzzyDate(
      array('culture'=> $this->getCurrentCulture(), 
            'image'=> '/images/calendar.gif',       
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

    $this->Postvalidators[] = new sfValidatorSchemaCompare(
      'birth_date',
      '<=',
      'end_date',
      array('throw_global_error' => true),
      array('invalid'=>'The birth date cannot be above the "end" date.')
    );
  }

  protected function initiateActivityItems()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['activity_date_from'] = new widgetFormJQueryFuzzyDate(
      array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'from_date')                
    );      
                                      
    $this->widgetSchema['activity_date_to'] = new widgetFormJQueryFuzzyDate(
      array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'to_date')                
    );      

    $this->validatorSchema['activity_date_from'] = new fuzzyDateValidator(
      array(
        'required' => false,                       
        'from_date' => true,                       
        'min' => $minDate,                         
        'max' => $maxDate,
        'empty_value' => $dateLowerBound,
      ),
      array('invalid' => 'Date provided is not valid')
    );

    $this->validatorSchema['activity_date_to'] = new fuzzyDateValidator(
      array(
        'required' => false,
        'from_date' => false,
        'min' => $minDate,
        'max' => $maxDate,
        'empty_value' => $dateUpperBound,
      ),
      array('invalid' => 'Date provided is not valid')
    );

    $this->Postvalidators[] = new sfValidatorSchemaCompare(
      'birth_date',
      '<=',
      'end_date',
      array('throw_global_error' => true),
      array('invalid'=>'The begin activity date cannot be above the end activity date.')
    );
  }
}