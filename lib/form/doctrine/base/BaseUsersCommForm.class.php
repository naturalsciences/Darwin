<?php

/**
 * UsersComm form base class.
 *
 * @method UsersComm getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersCommForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'person_user_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'comm_type'       => new sfWidgetFormTextarea(),
      'entry'           => new sfWidgetFormTextarea(),
      'tag'             => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'person_user_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'))),
      'comm_type'       => new sfValidatorString(array('required' => false)),
      'entry'           => new sfValidatorString(array('required' => false)),
      'tag'             => new sfValidatorString(),
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
