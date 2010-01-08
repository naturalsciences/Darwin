<?php

/**
 * Search Ig form.
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team (collections@naturalsciences.be)
 * @staticvar  array  $recPerPages:  Array of values found in the "Nbr of records per pages" select box of the pager
 *
 */
class SearchIgForm extends DarwinForm
{

  protected static $recPerPages = array("1", "2", "5", "10", "25", "50", "75", "100");

 /**
  * Configure the form with its widgets and validators
  *
  * @var   array         $yearsKeyVal    Array of years - constructed from two bound coming from configuration parameters
  * @var   array         $years          Array of years taking keys and values from $yearsKeyVal
  * @var   array         $recPerPages    Array of nbr. of records per pages to be displayed - with keys and values coming from self::$recPerPages
  * @var   array         $dateText       Array constructed for default empty values that should be displayed in select boxes
  * @var   FuzzyDateTime $minDate        FuzzyDateTime object instantiated to define the date lower bound
  * @var   FuzzyDateTime $maxDate        FuzzyDateTime object instantiated to define the date upper bound
  * @var   FuzzyDateTime $dateLowerBound FuzzyDateTime object instantiated to define the lowest date possible
  *
  */
  public function configure()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $recPerPages = array_combine(self::$recPerPages, self::$recPerPages);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
/*    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));*/
    $maxDate->setStart(false);
    $this->setWidgets(array('ig_num' => new sfWidgetFormInputText(),
                            'ig_creation_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                      'image'=>'/images/calendar.gif', 
                                                                                      'format' => '%day%/%month%/%year%', 
                                                                                      'years' => $years,
                                                                                      'empty_values' => $dateText,
                                                                                     ),
                                                                                array('class' => 'from_date')
                                                                               ),
                            'rec_per_page' => new sfWidgetFormChoice(array('choices' => $recPerPages, 
                                                                           'expanded'=>false)
                                                                    ),
                           )
                     );
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    $this->widgetSchema->setNameFormat('searchIg[%s]');
    $this->widgetSchema->setLabels(array('ig_num' => 'I.G.',
                                         'ig_creation_date' => 'I.G. creation date',
                                         'rec_per_page' => 'Records per page: ',
                                        )
                                  );

    $this->setValidators(array('ig_num'    => new sfValidatorString(array('required' => false, 'trim' => true)), 
                               'ig_creation_date' => new fuzzyDateValidator(array('required' => false,
                                                                                  'from_date' => true,
                                                                                  'min' => $minDate,
                                                                                  'max' => $maxDate, 
                                                                                  'empty_value' => $dateLowerBound,
                                                                                 ),
                                                                            array('invalid' => 'Date provided is not valid',
                                                                                 )
                                                                           ),
                               'rec_per_page' => new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage')))),
                              )
                        );
    
  }

 /**
  * Surclass the bind function of the sfForm - Necessary to correctly use the empty_value in the rec_per_page select box as default value
  *
  */
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