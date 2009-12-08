<?php

/**
 * UsersLoginInfos form base class.
 *
 * @method UsersLoginInfos getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersLoginInfosForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'   => new sfWidgetFormInputHidden(),
      'login_type' => new sfWidgetFormTextarea(),
      'user_name'  => new sfWidgetFormTextarea(),
      'password'   => new sfWidgetFormTextarea(),
      'system_id'  => new sfWidgetFormInputHidden(),
      'last_seen'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'user_ref'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_ref', 'required' => false)),
      'login_type' => new sfValidatorString(array('required' => false)),
      'user_name'  => new sfValidatorString(array('required' => false)),
      'password'   => new sfValidatorString(array('required' => false)),
      'system_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'system_id', 'required' => false)),
      'last_seen'  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_login_infos[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersLoginInfos';
  }

}
