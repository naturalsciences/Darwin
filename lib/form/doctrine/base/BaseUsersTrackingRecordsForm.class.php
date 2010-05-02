<?php

/**
 * UsersTrackingRecords form base class.
 *
 * @method UsersTrackingRecords getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersTrackingRecordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tracking_ref' => new sfWidgetFormInputHidden(),
      'field_name'   => new sfWidgetFormInputHidden(),
      'old_value'    => new sfWidgetFormTextarea(),
      'new_value'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'tracking_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'tracking_ref', 'required' => false)),
      'field_name'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'field_name', 'required' => false)),
      'old_value'    => new sfValidatorString(array('required' => false)),
      'new_value'    => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_tracking_records[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTrackingRecords';
  }

}
