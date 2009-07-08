<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PropertiesValues filter form base class.
 *
 * @package    filters
 * @subpackage PropertiesValues *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePropertiesValuesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'property_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueProperties', 'add_empty' => true)),
      'property_min'              => new sfWidgetFormFilterInput(),
      'property_min_unified'      => new sfWidgetFormFilterInput(),
      'property_max'              => new sfWidgetFormFilterInput(),
      'property_max_unified'      => new sfWidgetFormFilterInput(),
      'property_accuracy'         => new sfWidgetFormFilterInput(),
      'property_accuracy_unified' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'property_ref'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'CatalogueProperties', 'column' => 'id')),
      'property_min'              => new sfValidatorPass(array('required' => false)),
      'property_min_unified'      => new sfValidatorPass(array('required' => false)),
      'property_max'              => new sfValidatorPass(array('required' => false)),
      'property_max_unified'      => new sfValidatorPass(array('required' => false)),
      'property_accuracy'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'property_accuracy_unified' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('properties_values_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PropertiesValues';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'property_ref'              => 'ForeignKey',
      'property_min'              => 'Text',
      'property_min_unified'      => 'Text',
      'property_max'              => 'Text',
      'property_max_unified'      => 'Text',
      'property_accuracy'         => 'Number',
      'property_accuracy_unified' => 'Number',
    );
  }
}