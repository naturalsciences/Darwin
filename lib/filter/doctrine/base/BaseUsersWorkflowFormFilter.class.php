<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersWorkflow filter form base class.
 *
 * @package    filters
 * @subpackage UsersWorkflow *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersWorkflowFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'             => new sfWidgetFormFilterInput(),
      'record_id'              => new sfWidgetFormFilterInput(),
      'user_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'status'                 => new sfWidgetFormFilterInput(),
      'modification_date_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'comment'                => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'             => new sfValidatorPass(array('required' => false)),
      'record_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_ref'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'status'                 => new sfValidatorPass(array('required' => false)),
      'modification_date_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'comment'                => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_workflow_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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
      'table_name'             => 'Text',
      'record_id'              => 'Number',
      'user_ref'               => 'ForeignKey',
      'status'                 => 'Text',
      'modification_date_time' => 'Date',
      'comment'                => 'Text',
    );
  }
}