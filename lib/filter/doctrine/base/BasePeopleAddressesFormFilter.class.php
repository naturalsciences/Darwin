<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PeopleAddresses filter form base class.
 *
 * @package    filters
 * @subpackage PeopleAddresses *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePeopleAddressesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
      'tag'               => new sfWidgetFormFilterInput(),
      'organization_unit' => new sfWidgetFormFilterInput(),
      'person_user_role'  => new sfWidgetFormFilterInput(),
      'activity_period'   => new sfWidgetFormFilterInput(),
      'po_box'            => new sfWidgetFormFilterInput(),
      'extended_address'  => new sfWidgetFormFilterInput(),
      'locality'          => new sfWidgetFormFilterInput(),
      'region'            => new sfWidgetFormFilterInput(),
      'zip_code'          => new sfWidgetFormFilterInput(),
      'country'           => new sfWidgetFormFilterInput(),
      'address_parts_ts'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
      'tag'               => new sfValidatorPass(array('required' => false)),
      'organization_unit' => new sfValidatorPass(array('required' => false)),
      'person_user_role'  => new sfValidatorPass(array('required' => false)),
      'activity_period'   => new sfValidatorPass(array('required' => false)),
      'po_box'            => new sfValidatorPass(array('required' => false)),
      'extended_address'  => new sfValidatorPass(array('required' => false)),
      'locality'          => new sfValidatorPass(array('required' => false)),
      'region'            => new sfValidatorPass(array('required' => false)),
      'zip_code'          => new sfValidatorPass(array('required' => false)),
      'country'           => new sfValidatorPass(array('required' => false)),
      'address_parts_ts'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_addresses_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleAddresses';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'person_user_ref'   => 'ForeignKey',
      'tag'               => 'Text',
      'organization_unit' => 'Text',
      'person_user_role'  => 'Text',
      'activity_period'   => 'Text',
      'po_box'            => 'Text',
      'extended_address'  => 'Text',
      'locality'          => 'Text',
      'region'            => 'Text',
      'zip_code'          => 'Text',
      'country'           => 'Text',
      'address_parts_ts'  => 'Text',
    );
  }
}