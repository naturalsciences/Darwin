<?php

/**
 * PropertiesValues form.
 *
 * @package    form
 * @subpackage PropertiesValues
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PropertiesValuesForm extends BasePropertiesValuesForm
{
  public function configure()
  {
    $this->useFields(array('id', 'property_value', 'property_accuracy'));
    $this->widgetSchema['property_value'] = new sfWidgetFormInput();
    $this->validatorSchema['property_value']->setOption('required', false);
    $this->mergePostValidator(new PropertyValidatorSchema());
    //catalogue_properties[PropertiesValues][0]
//      $this->widgetSchema->setNameFormat('catalogue_properties[PropertiesValues][][%s]');
  }
}