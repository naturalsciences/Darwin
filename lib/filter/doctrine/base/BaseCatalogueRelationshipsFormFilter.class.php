<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CatalogueRelationships filter form base class.
 *
 * @package    filters
 * @subpackage CatalogueRelationships *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCatalogueRelationshipsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'        => new sfWidgetFormFilterInput(),
      'record_id_1'       => new sfWidgetFormFilterInput(),
      'record_id_2'       => new sfWidgetFormFilterInput(),
      'relationship_type' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'        => new sfValidatorPass(array('required' => false)),
      'record_id_1'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'record_id_2'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'relationship_type' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('catalogue_relationships_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueRelationships';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'table_name'        => 'Text',
      'record_id_1'       => 'Number',
      'record_id_2'       => 'Number',
      'relationship_type' => 'Number',
    );
  }
}