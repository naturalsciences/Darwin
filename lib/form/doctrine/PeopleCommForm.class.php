<?php

/**
 * PeopleComm form.
 *
 * @package    form
 * @subpackage PeopleComm
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PeopleCommForm extends BasePeopleCommForm
{
  public function configure()
  {
    unset($this['id']);
    $this->widgetSchema['person_user_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['tag'] = new sfWidgetFormInput();
    $this->widgetSchema['entry'] = new sfWidgetFormInput();
    $this->widgetSchema['comm_type'] = new sfWidgetFormInput();
  }
}