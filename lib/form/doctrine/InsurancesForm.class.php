<?php

/**
 * Insurances form.
 *
 * @package    form
 * @subpackage Insurances
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class InsurancesForm extends BaseInsurancesForm
{
  public function configure()
  {
    unset($this['id']);

    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    array_unshift($yearsKeyVal, 0);
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $years[0]='-';
    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();    
    $this->widgetSchema['insurance_currency'] = new widgetFormSelectComplete(array('model' => 'Insurances',
                                                                                   'table_method' => 'getDistinctCurrencies',
                                                                                   'method' => 'getCurrencies',
                                                                                   'key_method' => 'getCurrencies',
                                                                                   'add_empty' => false,
                                                                                   'change_label' => 'Pick a currency in the list',
                                                                                   'add_label' => 'Add another currency',
                                                                                   )
                                                                            );
    $this->widgetSchema['insurance_year'] = new sfWidgetFormChoice(array('choices'  => $years,
                                                                        )
                                                                  );
    $this->widgetSchema['insurer_ref'] = new widgetFormButtonRef(array('model' => 'People',
                                                                       'method' => 'getFamilyName',
                                                                       'link_url' => 'people/choose',
                                                                       'box_title' => $this->getI18N()->__('Choose Insurer'),
                                                                       'nullable' => true,
                                                                      )
                                                                );
    $this->widgetSchema->setLabels(array('insurance_value' => $this->getI18N()->__('Value'). ':' ,
                                         'insurance_currency' => $this->getI18N()->__('Currency'). ':',
                                         'insurance_year' => $this->getI18N()->__('Reference year'). ':',
                                         'insurer_ref' => $this->getI18N()->__('Insurer'). ':'
                                        )
                                  );
    $this->validatorSchema['insurance_year'] = new sfValidatorChoice(array('choices' => $yearsKeyVal));

  }
  
}