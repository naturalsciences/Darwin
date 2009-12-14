<?php

class SearchExpeditionForm extends DarwinForm
{

  protected static $recPerPages = array("1", "2", "5", "10", "25", "50", "75", "100");

  public function configure()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $recPerPages = array_combine(self::$recPerPages, self::$recPerPages);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);
    $this->setWidgets(array('name' => new sfWidgetFormInputText(),
                            'from_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                               'image'=>'/images/calendar.gif', 
                                                                               'format' => '%day%/%month%/%year%', 
                                                                               'years' => $years,
                                                                               'empty_values' => $dateText,
                                                                              ),
                                                                         array('class' => 'from_date')
                                                                        ),
                            'to_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                             'image'=>'/images/calendar.gif', 
                                                                             'format' => '%day%/%month%/%year%', 
                                                                             'years' => $years,
                                                                             'empty_values' => $dateText, 
                                                                            ),
                                                                       array('class' => 'to_date')
                                                                      ),
                            'rec_per_page' => new sfWidgetFormChoice(array('choices' => $recPerPages, 
                                                                           'expanded'=>false)
                                                                    ),
                           )
                     );
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    $this->widgetSchema->setNameFormat('searchExpedition[%s]');
    $this->widgetSchema->setLabels(array('from_date' => 'Between',
                                         'to_date' => 'and',
                                         'rec_per_page' => 'Records per page: ',
                                        )
                                  );

    $this->setValidators(array('name'    => new sfValidatorString(array('required' => false, 'trim' => true)), 
                               'from_date' => new fuzzyDateValidator(array('required' => false,
                                                                           'from_date' => true,
                                                                           'min' => $minDate,
                                                                           'max' => $maxDate, 
                                                                           'empty_value' => $dateLowerBound,
                                                                          ),
                                                                     array('invalid' => 'Date provided is not valid',
                                                                          )
                                                                    ),
                               'to_date' => new fuzzyDateValidator(array('required' => false,
                                                                         'from_date' => false,
                                                                         'min' => $minDate,
                                                                         'max' => $maxDate,
                                                                         'empty_value' => $dateUpperBound,
                                                                        ),
                                                                   array('invalid' => 'Date provided is not valid',
                                                                        )
                                                                  ),
                               'rec_per_page' => new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage')))),
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

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(! isset($taintedValues['rec_per_page']))
      {
	$taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
      }
      parent::bind($taintedValues, $taintedFiles);
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