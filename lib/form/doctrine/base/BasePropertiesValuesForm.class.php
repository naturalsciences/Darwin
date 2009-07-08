<?php

/**
 * PropertiesValues form base class.
 *
 * @package    form
 * @subpackage properties_values
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePropertiesValuesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'property_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueProperties', 'add_empty' => true)),
      'property_min'              => new sfWidgetFormTextarea(),
      'property_min_unified'      => new sfWidgetFormTextarea(),
      'property_max'              => new sfWidgetFormTextarea(),
      'property_max_unified'      => new sfWidgetFormTextarea(),
      'property_accuracy'         => new sfWidgetFormInput(),
      'property_accuracy_unified' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'PropertiesValues', 'column' => 'id', 'required' => false)),
      'property_ref'              => new sfValidatorDoctrineChoice(array('model' => 'CatalogueProperties', 'required' => false)),
      'property_min'              => new sfValidatorString(array('max_length' => 2147483647)),
      'property_min_unified'      => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'property_max'              => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'property_max_unified'      => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'property_accuracy'         => new sfValidatorNumber(array('required' => false)),
      'property_accuracy_unified' => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('properties_values[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PropertiesValues';
  }

}
