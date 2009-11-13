<?php

/**
 * UsersLoginInfos form base class.
 *
 * @package    form
 * @subpackage users_login_infos
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersLoginInfosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'   => new sfWidgetFormInputHidden(),
      'login_type' => new sfWidgetFormTextarea(),
      'user_name'  => new sfWidgetFormTextarea(),
      'password'   => new sfWidgetFormTextarea(),
      'system_id'  => new sfWidgetFormInputHidden(),
      'last_seen'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'user_ref'   => new sfValidatorDoctrineChoice(array('model' => 'UsersLoginInfos', 'column' => 'user_ref', 'required' => false)),
      'login_type' => new sfValidatorString(),
      'user_name'  => new sfValidatorString(array('required' => false)),
      'password'   => new sfValidatorString(array('required' => false)),
      'system_id'  => new sfValidatorDoctrineChoice(array('model' => 'UsersLoginInfos', 'column' => 'system_id', 'required' => false)),
      'last_seen'  => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_login_infos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersLoginInfos';
  }

}
