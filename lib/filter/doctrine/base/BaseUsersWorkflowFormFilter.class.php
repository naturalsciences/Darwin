<?php

/**
 * UsersWorkflow filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersWorkflowFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'record_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_ref'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => true)),
      'status'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'modification_date_time' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'comment'                => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'referenced_relation'    => new sfValidatorPass(array('required' => false)),
      'record_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_ref'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Users'), 'column' => 'id')),
      'status'                 => new sfValidatorPass(array('required' => false)),
      'modification_date_time' => new sfValidatorPass(array('required' => false)),
      'comment'                => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_workflow_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersWorkflow';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'referenced_relation'    => 'Text',
      'record_id'              => 'Number',
      'user_ref'               => 'ForeignKey',
      'status'                 => 'Text',
      'modification_date_time' => 'Text',
      'comment'                => 'Text',
    );
  }
}
