<?php

/**
 * Users form.
 *
 * @package    form
 * @subpackage Users
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class UsersForm extends BaseUsersForm
{
  public function configure()
  {
    unset(
      $this['id'],
      $this['formated_name'],
      $this['formated_name_indexed'],
      $this['formated_name_ts'],
      $this['approval_level'],
      $this['birth_date_mask'],
      $this['db_user_type']
    );
    
    $this->widgetSchema['title'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['additional_names'] = new sfWidgetFormInput();

    $this->widgetSchema['title']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['family_name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['given_name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['additional_names']->setAttributes(array('class'=>'medium_size'));




    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(date('Y').'/12/31');
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
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
  }
}