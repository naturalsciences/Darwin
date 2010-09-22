<?php

/**
 * RegisterLoginInfos form.
 *
 * @package    form
 * @subpackage RegisterLoginInfos
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RegisterLoginInfosForm extends UsersLoginInfosForm
{
  public function configure()
  {
    parent::configure();
    unset($this['login_type']);
    $this->widgetSchema->setLabels(array('new_password'=>'Password', 'user_name'=>'Login'));
  }
}