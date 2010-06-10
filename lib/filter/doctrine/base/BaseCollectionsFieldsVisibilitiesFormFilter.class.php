<?php

/**
 * CollectionsFieldsVisibilities filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCollectionsFieldsVisibilitiesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => true)),
      'user_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'field_group_name' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'db_user_type'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'searchable'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'visible'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'collection_ref'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Collections'), 'column' => 'id')),
      'user_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'field_group_name' => new sfValidatorPass(array('required' => false)),
      'db_user_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'searchable'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'visible'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('collections_fields_visibilities_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

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
