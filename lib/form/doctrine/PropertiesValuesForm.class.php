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
    unset(
    $this['property_ref'],
    $this['property_value_unified'],
    $this['property_accuracy_unified']
   );
   $this->widgetSchema['property_ref'] = new sfWidgetFormInputHidden();
   $this->widgetSchema['property_value'] = new sfWidgetFormInput();
  }
}