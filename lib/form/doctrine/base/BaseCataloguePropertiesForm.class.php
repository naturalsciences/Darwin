<?php

/**
 * CatalogueProperties form base class.
 *
 * @package    form
 * @subpackage catalogue_properties
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCataloguePropertiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'referenced_relation'        => new sfWidgetFormTextarea(),
      'record_id'                  => new sfWidgetFormInput(),
      'property_type'              => new sfWidgetFormTextarea(),
      'property_sub_type'          => new sfWidgetFormTextarea(),
      'property_sub_type_indexed'  => new sfWidgetFormTextarea(),
      'property_qualifier'         => new sfWidgetFormTextarea(),
      'property_qualifier_indexed' => new sfWidgetFormTextarea(),
      'date_from_mask'             => new sfWidgetFormInput(),
      'date_from timestamp'        => new sfWidgetFormDateTime(),
      'date_to_mask'               => new sfWidgetFormInput(),
      'date_to timestamp'          => new sfWidgetFormDateTime(),
      'property_unit'              => new sfWidgetFormTextarea(),
      'property_accuracy_unit'     => new sfWidgetFormTextarea(),
      'property_method'            => new sfWidgetFormTextarea(),
      'property_method_indexed'    => new sfWidgetFormTextarea(),
      'property_tool'              => new sfWidgetFormTextarea(),
      'property_tool_indexed'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorDoctrineChoice(array('model' => 'CatalogueProperties', 'column' => 'id', 'required' => false)),
      'referenced_relation'        => new sfValidatorString(),
      'record_id'                  => new sfValidatorInteger(),
      'property_type'              => new sfValidatorString(),
      'property_sub_type'          => new sfValidatorString(array('required' => false)),
      'property_sub_type_indexed'  => new sfValidatorString(array('required' => false)),
      'property_qualifier'         => new sfValidatorString(array('required' => false)),
      'property_qualifier_indexed' => new sfValidatorString(array('required' => false)),
      'date_from_mask'             => new sfValidatorInteger(),
      'date_from timestamp'        => new sfValidatorDateTime(),
      'date_to_mask'               => new sfValidatorInteger(),
      'date_to timestamp'          => new sfValidatorDateTime(),
      'property_unit'              => new sfValidatorString(),
      'property_accuracy_unit'     => new sfValidatorString(array('required' => false)),
      'property_method'            => new sfValidatorString(array('required' => false)),
      'property_method_indexed'    => new sfValidatorString(array('required' => false)),
      'property_tool'              => new sfValidatorString(),
      'property_tool_indexed'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('catalogue_properties[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueProperties';
  }

}
