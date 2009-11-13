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
      'tag'               => new sfValidatorString(),
      'organization_unit' => new sfValidatorString(array('required' => false)),
      'person_user_role'  => new sfValidatorString(array('required' => false)),
      'activity_period'   => new sfValidatorString(array('required' => false)),
      'po_box'            => new sfValidatorString(array('required' => false)),
      'extended_address'  => new sfValidatorString(array('required' => false)),
      'locality'          => new sfValidatorString(),
      'region'            => new sfValidatorString(array('required' => false)),
      'zip_code'          => new sfValidatorString(array('required' => false)),
      'country'           => new sfValidatorString(),
      'address_parts_ts'  => new sfValidatorString(array('required' => false)),
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
