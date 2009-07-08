<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersComm filter form base class.
 *
 * @package    filters
 * @subpackage UsersComm *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersCommFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'comm_type'         => new sfWidgetFormFilterInput(),
      'tag'               => new sfWidgetFormFilterInput(),
      'organization_unit' => new sfWidgetFormFilterInput(),
      'person_user_role'  => new sfWidgetFormFilterInput(),
      'activity_period'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'comm_type'         => new sfValidatorPass(array('required' => false)),
      'tag'               => new sfValidatorPass(array('required' => false)),
      'organization_unit' => new sfValidatorPass(array('required' => false)),
      'person_user_role'  => new sfValidatorPass(array('required' => false)),
      'activity_period'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_comm_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersComm';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'person_user_ref'   => 'ForeignKey',
      'comm_type'         => 'Text',
      'tag'               => 'Text',
      'organization_unit' => 'Text',
      'person_user_role'  => 'Text',
      'activity_period'   => 'Text',
    );
  }
}