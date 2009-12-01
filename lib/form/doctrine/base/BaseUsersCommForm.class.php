<?php

/**
 * UsersComm form base class.
 *
 * @method UsersComm getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseUsersCommForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'person_user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'comm_type'         => new sfWidgetFormTextarea(),
      'tag'               => new sfWidgetFormTextarea(),
      'organization_unit' => new sfWidgetFormTextarea(),
      'person_user_role'  => new sfWidgetFormTextarea(),
      'activity_period'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'person_user_ref'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'))),
      'comm_type'         => new sfValidatorString(array('required' => false)),
      'tag'               => new sfValidatorString(),
      'organization_unit' => new sfValidatorString(array('required' => false)),
      'person_user_role'  => new sfValidatorString(array('required' => false)),
      'activity_period'   => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_comm[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersComm';
  }

}
