<?php

/**
 * UsersTrackingRecords form base class.
 *
 * @method UsersTrackingRecords getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseUsersTrackingRecordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'tracking_ref' => new sfWidgetFormInputText(),
      'field_name'   => new sfWidgetFormTextarea(),
      'old_value'    => new sfWidgetFormTextarea(),
      'new_value'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'tracking_ref' => new sfValidatorInteger(),
      'field_name'   => new sfValidatorString(),
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
