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
      'id'                => new sfWidgetFormInputHidden(),
      'table_name'        => new sfWidgetFormTextarea(),
      'record_id_1'       => new sfWidgetFormInput(),
      'record_id_2'       => new sfWidgetFormInput(),
      'relationship_type' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'CatalogueRelationships', 'column' => 'id', 'required' => false)),
      'table_name'        => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id_1'       => new sfValidatorInteger(),
      'record_id_2'       => new sfValidatorInteger(),
      'relationship_type' => new sfValidatorInteger(),
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
