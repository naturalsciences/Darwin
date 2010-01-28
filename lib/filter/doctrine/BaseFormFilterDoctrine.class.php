<?php

/**
 * Project filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine
{
  public function setup()
  {
  }

  protected function addPagerItems()
  {
    $recPerPages = array("1"=>"1", "2"=>"2", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "75"=>"75", "100"=>"100");
    
    $this->widgetSchema['rec_per_page'] = new sfWidgetFormChoice(array('choices' => $recPerPages));
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    
    $this->validatorSchema['rec_per_page'] = new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage'))));
    $this->hasPager = true;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(isset($this->hasPager) && $this->hasPager)
      {
	if(! isset($taintedValues['rec_per_page']))
	{
	  $taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
	}
      }
      parent::bind($taintedValues, $taintedFiles);
  }

  protected function getDateItemOptions()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    return array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ); 
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function getCurrentCulture()
  {
    return isset($this->options['culture']) ? $this->options['culture'] : 'en';
  }
}
