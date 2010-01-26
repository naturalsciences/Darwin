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
    $this->useFields(array('family_name'));
    $this->widgetSchema['family_name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));
    $recPerPages = array("1"=>"1", "2"=>"2", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "75"=>"75", "100"=>"100");
    $this->widgetSchema['rec_per_page'] = new sfWidgetFormChoice(array('choices' => $recPerPages));
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    
    $this->validatorSchema['rec_per_page'] = new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage'))));

  }

  public function addFamilyNameColumnQuery($query, $field, $val)
  {
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
