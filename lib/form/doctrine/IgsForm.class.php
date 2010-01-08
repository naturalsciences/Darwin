<?php

/**
 * Igs form.
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team (collections@naturalsciences.be)
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 *
 */
class IgsForm extends BaseIgsForm
{
 /**
  * Configure the form with its widgets and validators
  */
  public function configure()
  {

    unset($this['ig_date_mask']);

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    /*$dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));*/
    $maxDate->setStart(false);

    $this->widgetSchema['ig_num'] = new sfWidgetFormInputText();
    $this->widgetSchema['ig_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                         'image'=>'/images/calendar.gif', 
                                                                         'format' => '%day%/%month%/%year%', 
                                                                         'years' => $years,
                                                                         'empty_values' => $dateText,
                                                                        ),
                                                                   array('class' => 'from_date')
                                                                  );
    $this->validatorSchema['ig_num'] = new sfValidatorString(array('required' => true, 'trim' => true));
    $this->validatorSchema['ig_date'] = new fuzzyDateValidator(array('required' => false,
                                                                     'from_date' => true,
                                                                     'min' => $minDate,
                                                                     'max' => $maxDate, 
                                                                     'empty_value' => $dateLowerBound,
                                                                    ),
                                                               array('invalid' => 'Date provided is not valid',
                                                                    )
                                                              );
  }

}