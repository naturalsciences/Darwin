<?php

/**
 * RegisterComm form.
 *
 * @package    form
 * @subpackage RegisterComm
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RegisterCommForm extends BaseUsersCommForm
{
  public function configure()
  {
    $comm_mean = UsersComm::getRegisterCommType();
    unset($this['id'], $this['tag']);
    $this->widgetSchema['person_user_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['comm_type'] = new sfWidgetFormInputHidden(array('default' => $comm_mean));
    $this->widgetSchema['entry'] = new sfWidgetFormInput();
    $this->validatorSchema['entry'] = new sfValidatorString(array('required'=> true));
    $this->validatorSchema['comm_type'] = new sfValidatorString(array('required'=> true, 'empty_value'=> $comm_mean));
  }
}