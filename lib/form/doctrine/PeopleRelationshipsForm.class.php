<?php

/**
 * PeopleRelationships form.
 *
 * @package    form
 * @subpackage PeopleRelationships
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PeopleRelationshipsForm extends BasePeopleRelationshipsForm
{
  public function configure()
  {
    unset(
      $this['path'],
      $this['activity_date_from_mask'], 
      $this['activity_date_to_mask']
    );
    
    $this->widgetSchema['person_user_role'] = new sfWidgetFormInput();
    $this->widgetSchema['person_2_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['person_1_ref'] = new widgetFormButtonRef(array(
       'model' => 'Institutions',
       'link_url' => 'instituion/choose',
       'method' => 'getFamilyName',
       'box_title' => '',
       'is_hidden' => true,
       'nullable' => false,));
    $this->widgetSchema['relationship_type'] = new sfWidgetFormChoice(array('choices' => PeopleRelationships::$possible_types));
    $this->Postvalidators = array();
    $this->initiateActivityItems();
    $this->validatorSchema->setPostValidator(new sfValidatorAnd($this->Postvalidators));
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
            'image'=> '/images/calendar.gif',       
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