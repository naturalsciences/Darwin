<?php

/**
 * CatalogueRelationships form base class.
 *
 * @package    form
 * @subpackage catalogue_relationships
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCatalogueRelationshipsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id_1'         => new sfWidgetFormInput(),
      'record_id_2'         => new sfWidgetFormInput(),
      'relationship_type'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'CatalogueRelationships', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id_1'         => new sfValidatorInteger(),
      'record_id_2'         => new sfValidatorInteger(),
      'relationship_type'   => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('catalogue_relationships[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueRelationships';
  }

}
