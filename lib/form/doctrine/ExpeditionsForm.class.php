<?php

/**
 * Expeditions form.
 *
 * @package    form
 * @subpackage expeditions
 * @author     DB team (collections@naturalsciences.be)
 *
 */
class ExpeditionsForm extends BaseExpeditionsForm
{
  public function configure()
  {

    unset($this['name_ts'], $this['name_indexed'], $this['name_language_full_text'], $this['expedition_from_date_mask'], $this['expedition_to_date_mask']);

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema['expedition_from_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                      'image'=>'/images/calendar.gif', 
                                                                                      'format' => '%day%/%month%/%year%', 
                                                                                      'years' => $years,
                                                                                      'empty_values' => $dateText,
                                                                                     ),
                                                                                array('class' => 'from_date')
                                                                               );
    $this->widgetSchema['expedition_to_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                    'image'=>'/images/calendar.gif', 
                                                                                    'format' => '%day%/%month%/%year%', 
                                                                                    'years' => $years,
                                                                                    'empty_values' => $dateText, 
                                                                                   ),
                                                                              array('class' => 'to_date')
                                                                             );
    $this->validatorSchema['name'] = new sfValidatorString(array('required' => true, 'trim' => true));
    $this->validatorSchema['expedition_from_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                  'from_date' => true,
                                                                                  'min' => $minDate,
                                                                                  'max' => $maxDate, 
                                                                                  'empty_value' => $dateLowerBound,
                                                                                 ),
                                                                            array('invalid' => 'Date provided is not valid',
                                                                                 )
                                                                           );
    $this->validatorSchema['expedition_to_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                'from_date' => false,
                                                                                'min' => $minDate,
                                                                                'max' => $maxDate,
                                                                                'empty_value' => $dateUpperBound,
                                                                               ),
                                                                          array('invalid' => 'Date provided is not valid',
                                                                               )
                                                                         );
    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('from_date', 
                                                                          '<=', 
                                                                          'to_date', 
                                                                          array('throw_global_error' => true), 
                                                                          array('invalid'=>'The "begin" date cannot be above the "end" date.')
                                                                         )
                                            );
  }

}