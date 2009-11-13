<?php

/**
 * UsersComm form base class.
 *
 * @package    form
 * @subpackage users_comm
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersCommForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'comm_type'         => new sfWidgetFormTextarea(),
      'tag'               => new sfWidgetFormTextarea(),
      'organization_unit' => new sfWidgetFormTextarea(),
      'person_user_role'  => new sfWidgetFormTextarea(),
      'activity_period'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'UsersComm', 'column' => 'id', 'required' => false)),
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'comm_type'         => new sfValidatorString(),
      'tag'               => new sfValidatorString(),
      'organization_unit' => new sfValidatorString(array('required' => false)),
      'person_user_role'  => new sfValidatorString(array('required' => false)),
      'activity_period'   => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_comm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersComm';
  }

}
