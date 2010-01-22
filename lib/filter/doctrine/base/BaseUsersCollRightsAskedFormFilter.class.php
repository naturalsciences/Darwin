<?php

/**
 * UsersCollRightsAsked filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersCollRightsAskedFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => true)),
      'user_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'field_group_name'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'db_user_type'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'searchable'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'visible'              => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'motivation'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'asking_date_time'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'with_sub_collections' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'collection_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Collections'), 'column' => 'id')),
      'user_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'field_group_name'     => new sfValidatorPass(array('required' => false)),
      'db_user_type'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'searchable'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'visible'              => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'motivation'           => new sfValidatorPass(array('required' => false)),
      'asking_date_time'     => new sfValidatorPass(array('required' => false)),
      'with_sub_collections' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('users_coll_rights_asked_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersCollRightsAsked';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'collection_ref'       => 'ForeignKey',
      'user_ref'             => 'ForeignKey',
      'field_group_name'     => 'Text',
      'db_user_type'         => 'Number',
      'searchable'           => 'Boolean',
      'visible'              => 'Boolean',
      'motivation'           => 'Text',
      'asking_date_time'     => 'Text',
      'with_sub_collections' => 'Boolean',
    );
  }
}
