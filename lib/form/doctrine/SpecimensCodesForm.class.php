<?php

/**
 * VernacularNames form.
 *
 * @package    form
 * @subpackage VernacularNames
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensCodesForm extends BaseSpecimensCodesForm
{
  public function configure()
  {

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $maxDate->setStart(false);

    $this->useFields(array('id', 'code_category', 'code_prefix', 'code_prefix_separator', 'code', 'code_suffix', 'code_suffix_separator', 'code_date'));
    $choices = array('main'=> 'Main', 'secondary' => 'Secondary', 'temporary' => 'Temporary') ;
    $this->widgetSchema['code_category'] = new sfWidgetFormChoice(array(
        'choices' => $choices
      ));
    $this->validatorSchema['code_category'] = new sfValidatorChoice(array('required' => true, 'choices'=>array_keys($choices)));
    $this->widgetSchema['code_prefix'] = new sfWidgetFormInput();
    $this->widgetSchema['code_prefix']->setAttributes(array('class'=>'lsmall_size'));
    $this->validatorSchema['code_prefix'] = new sfValidatorString(array('required' => false));
    $this->widgetSchema['code_prefix_separator'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimensCodes',
        'table_method' => 'getDistinctPrefixSep',
        'method' => 'getCodePrefixSeparator',
        'key_method' => 'getCodePrefixSeparator',
        'add_empty' => true,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['code_prefix_separator']->setAttributes(array('class'=>'vvsmall_size'));
    $this->widgetSchema['code'] = new sfWidgetFormInput();
    $this->widgetSchema['code']->setAttributes(array('class'=>'lsmall_size'));
    $this->validatorSchema['code'] = new sfValidatorInteger(array('required' => false));
    $this->widgetSchema['code_suffix'] = new sfWidgetFormInput();
    $this->widgetSchema['code_suffix']->setAttributes(array('class'=>'lsmall_size'));
    $this->validatorSchema['code_suffix'] = new sfValidatorString(array('required' => false));
    $this->widgetSchema['code_suffix_separator'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimensCodes',
        'table_method' => 'getDistinctSuffixSep',
        'method' => 'getCodeSuffixSeparator',
        'key_method' => 'getCodeSuffixSeparator',
        'add_empty' => true,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['code_suffix_separator']->setAttributes(array('class'=>'vvsmall_size'));
    $this->widgetSchema['code_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                           'image'=>'/images/calendar.gif', 
                                                                           'format' => '%day%/%month%/%year%', 
                                                                           'years' => $years,
                                                                           'empty_values' => $dateText,
                                                                          ),
                                                                     array('class' => 'to_date')
                                                                    );
    $this->validatorSchema['code_date'] = new fuzzyDateValidator(array('required' => false,
                                                                       'from_date' => true,
                                                                       'min' => $minDate,
                                                                       'max' => $maxDate, 
                                                                       'empty_value' => $dateLowerBound,
                                                                      ),
                                                                 array('invalid' => 'Date provided is not valid',
                                                                      )
                                                                );

    $this->mergePostValidator(new SpecimensCodesValidatorSchema());
  }
}