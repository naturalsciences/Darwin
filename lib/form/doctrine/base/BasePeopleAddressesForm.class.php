<?php

/**
 * PeopleAddresses form base class.
 *
 * @package    form
 * @subpackage people_addresses
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleAddressesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'tag'               => new sfWidgetFormTextarea(),
      'organization_unit' => new sfWidgetFormTextarea(),
      'person_user_role'  => new sfWidgetFormTextarea(),
      'activity_period'   => new sfWidgetFormTextarea(),
      'po_box'            => new sfWidgetFormTextarea(),
      'extended_address'  => new sfWidgetFormTextarea(),
      'locality'          => new sfWidgetFormTextarea(),
      'region'            => new sfWidgetFormTextarea(),
      'zip_code'          => new sfWidgetFormTextarea(),
      'country'           => new sfWidgetFormTextarea(),
      'address_parts_ts'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'PeopleAddresses', 'column' => 'id', 'required' => false)),
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'tag'               => new sfValidatorString(array('max_length' => 2147483647)),
      'organization_unit' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'person_user_role'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'activity_period'   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'po_box'            => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'extended_address'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'locality'          => new sfValidatorString(array('max_length' => 2147483647)),
      'region'            => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'zip_code'          => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'country'           => new sfValidatorString(array('max_length' => 2147483647)),
      'address_parts_ts'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_addresses[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleAddresses';
  }

}
