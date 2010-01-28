<?php

/**
 * People filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PeopleFormFilter extends BasePeopleFormFilter
{
  public function configure()
  {
    $this->useFields(array('is_physical','family_name', 'activity_date_to', 'activity_date_from', 'db_people_type'));

    $this->widgetSchema['family_name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
    $recPerPages = array("1"=>"1", "2"=>"2", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "75"=>"75", "100"=>"100");
    $this->widgetSchema['rec_per_page'] = new sfWidgetFormChoice(array('choices' => $recPerPages));
    
    $db_people_types = array(''=>'');
    foreach(People::getTypes() as $flag => $name)
      $db_people_types[strval($flag)] = $name;
    
    $this->widgetSchema['db_people_type'] = new sfWidgetFormChoice(array('choices' => $db_people_types ));

    $this->widgetSchema['is_physical'] = new sfWidgetFormInputHidden();
    $this->setDefault('is_physical', true); 

    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    
    $this->validatorSchema['rec_per_page'] = new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage'))));

    $this->validatorSchema['db_people_type'] = new sfValidatorChoice(array('required' => false, 'choices' => array_keys($db_people_types) ));


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

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare(
        'activity_date_from',
        '<=',
        'activity_date_to',
        array('throw_global_error' => true),
        array('invalid'=>'The to date cannot be above the "end" date.')
      )
    );
  }

  public function addDbPeopleTypeColumnQuery($query, $field, $val)
  {
    $query->andWhere("($field &  ?) != 0 ", $val);
    return $query;
  }

  public function addActivityDateToColumnQuery($query, $field, $val)
  {
    $query->andWhere("$field <= ? ", $val->format('d/m/Y'));
    return $query;
  }

  public function addActivityDateFromColumnQuery($query, $field, $val)
  {
    $query->andWhere("$field >= ? ", $val->format('d/m/Y'));
    return $query;
  }

  public function addFamilyNameColumnQuery($query, $field, $val)
  {
    if($val['text'] != "")
      $query->andWhere("formated_name_ts  @@ search_words_to_query('people' , 'formated_name_ts', ? , 'contains') ", $val['text']);
    return $query;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(! isset($taintedValues['rec_per_page']))
      {
	$taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
      }
      parent::bind($taintedValues, $taintedFiles);
  }
}
