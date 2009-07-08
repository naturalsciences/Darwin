<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CollectionsFieldsVisibilities filter form base class.
 *
 * @package    filters
 * @subpackage CollectionsFieldsVisibilities *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCollectionsFieldsVisibilitiesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'user_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'field_group_name' => new sfWidgetFormFilterInput(),
      'db_user_type'     => new sfWidgetFormFilterInput(),
      'searchable'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'visible'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'collection_ref'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Collections', 'column' => 'id')),
      'user_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'field_group_name' => new sfValidatorPass(array('required' => false)),
      'db_user_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'searchable'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'visible'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('collections_fields_visibilities_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsFieldsVisibilities';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'collection_ref'   => 'ForeignKey',
      'user_ref'         => 'ForeignKey',
      'field_group_name' => 'Text',
      'db_user_type'     => 'Number',
      'searchable'       => 'Boolean',
      'visible'          => 'Boolean',
    );
  }
}