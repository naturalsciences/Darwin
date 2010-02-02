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
    $this->widgetSchema['tag'] = new widgetFormTagEntry(array('choices' => array('home'=>'Home', 'pager'=>'Pager', 'work'=>'Work', 'pref'=>'Prefered', 'voice'=>'Voice', 'fax'=>'Fax', 'cell'=>'Cell')));
    //home/pref/work/internet ( for mail)
    $this->widgetSchema['entry'] = new sfWidgetFormInput();
    $this->widgetSchema['comm_type'] = new sfWidgetFormChoice(array('choices' => array('TEL'=>'tel','EMAIL'=>'email')));
    $this->validatorSchema['tag'] = new sfValidatorString(array('required'=> false));
  }
}