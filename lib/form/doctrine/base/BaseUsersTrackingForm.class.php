<?php

/**
 * UsersTracking form base class.
 *
 * @package    form
 * @subpackage users_tracking
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersTrackingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'table_name'             => new sfWidgetFormTextarea(),
      'record_id'              => new sfWidgetFormInput(),
      'user_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'action'                 => new sfWidgetFormTextarea(),
      'modification_date_time' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'UsersTracking', 'column' => 'id', 'required' => false)),
      'table_name'             => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'              => new sfValidatorInteger(),
      'user_ref'               => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'action'                 => new sfValidatorString(array('max_length' => 2147483647)),
      'modification_date_time' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('users_tracking[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTracking';
  }

}
