<?php

/**
 * UsersTrackingRecords form base class.
 *
 * @package    form
 * @subpackage users_tracking_records
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersTrackingRecordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'tracking_ref' => new sfWidgetFormInput(),
      'field_name'   => new sfWidgetFormTextarea(),
      'old_value'    => new sfWidgetFormTextarea(),
      'new_value'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'UsersTrackingRecords', 'column' => 'id', 'required' => false)),
      'tracking_ref' => new sfValidatorInteger(),
      'field_name'   => new sfValidatorString(array('max_length' => 2147483647)),
      'old_value'    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'new_value'    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_tracking_records[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTrackingRecords';
  }

}
