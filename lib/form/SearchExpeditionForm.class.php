<?php

class SearchExpeditionForm extends DarwinForm
{

  public function configure()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), date('Y')+2);
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $minDate = new DateTime(strval(min($yearsKeyVal)).'/1/1 0:0:0');
    $maxDate = new DateTime(strval(max($yearsKeyVal)).'/12/31 23:59:59');
    $this->setWidgets(array('name' => new sfWidgetFormInput(),
                            'from_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                               'image'=>'/images/calendar.gif', 
                                                                               'format' => '%day%/%month%/%year%', 
                                                                               'years' => $years, ),
                                                                         array('class' => 'from_date')
                                                                        ),
                            'to_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                             'image'=>'/images/calendar.gif', 
                                                                             'format' => '%day%/%month%/%year%', 
                                                                             'years' => $years, ),
                                                                       array('class' => 'to_date')
                                                                      )
                           )
                     );
    
    $this->widgetSchema->setNameFormat('searchExpedition[%s]');
    
    $this->widgetSchema->setLabels(array('from_date' => 'Between (dd/mm/yyyy)',
                                         'to_date' => 'and (dd/mm/yyyy)',
                                        )
                                  );

    $this->setValidators(array('name'    => new sfValidatorString(array('required' => false, 'trim' => true)), 
                               'from_date' => new fuzzyDateValidator(array('required' => false,
                                                                           'from_date' => true,
                                                                           'min' => $minDate,
                                                                           'max' => $maxDate,
                                                                          ),
                                                                     array('invalid' => 'Date provided is not valid',
                                                                          )
                                                                    ),
                               'to_date' => new fuzzyDateValidator(array('required' => false,
                                                                         'from_date' => false,
                                                                         'min' => $minDate,
                                                                         'max' => $maxDate,
                                                                        ),
                                                                   array('invalid' => 'Date provided is not valid',
                                                                        )
                                                                  ),
                              )
                        );
    
    $this->validatorSchema->setPostValidator(new fuzzyDateValidatorSchemaCompare('from_date', 
                                                                                 '<=', 
                                                                                 'to_date', 
                                                                                 array('throw_global_error' => true), 
                                                                                 array('invalid'=>'The "begin" date cannot be above the "end" date.')
                                                                                )
                                            );
    
  }
  public function getCurrentCulture()
  {
    return isset($this->options['culture']) ? $this->options['culture'] : 'en';
  }
  public function getMonthFormat()
  {
    return isset($this->options['month_format']) ? $this->options['month_format'] : 'short_name';
  }
}