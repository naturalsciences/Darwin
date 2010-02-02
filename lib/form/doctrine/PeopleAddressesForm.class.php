<?php

/**
 * PeopleAddresses form.
 *
 * @package    form
 * @subpackage PeopleAddresses
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PeopleAddressesForm extends BasePeopleAddressesForm
{
  public function configure()
  {
    unset($this['id'],$this['address_parts_ts']);
    $this->widgetSchema['person_user_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['tag'] = new widgetFormTagEntry(array('choices' => array('home'=>'Home', 'dom'=>'Dom', 'work'=>'Work', 'pref'=>'Prefered', 'intl'=>'International', 'postal'=>'Postal')));
    $this->widgetSchema['entry'] = new sfWidgetFormInput();
    $this->widgetSchema['po_box'] = new sfWidgetFormInput();
    $this->widgetSchema['extended_address'] = new sfWidgetFormInput();
    $this->widgetSchema['locality'] = new sfWidgetFormInput();
    $this->widgetSchema['region'] = new sfWidgetFormInput();
    $this->widgetSchema['zip_code'] = new sfWidgetFormInput();
    $this->widgetSchema['country'] = new sfWidgetFormInput();

  }
}