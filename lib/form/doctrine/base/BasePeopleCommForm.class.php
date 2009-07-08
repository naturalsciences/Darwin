<?php

/**
 * PeopleComm form base class.
 *
 * @package    form
 * @subpackage people_comm
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleCommForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'comm_type'         => new sfWidgetFormTextarea(),
      'tag'               => new sfWidgetFormTextarea(),
      'organization_unit' => new sfWidgetFormTextarea(),
      'person_user_role'  => new sfWidgetFormTextarea(),
      'activity_period'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'PeopleComm', 'column' => 'id', 'required' => false)),
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'comm_type'         => new sfValidatorString(array('max_length' => 2147483647)),
      'tag'               => new sfValidatorString(array('max_length' => 2147483647)),
      'organization_unit' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'person_user_role'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'activity_period'   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_comm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleComm';
  }

}
