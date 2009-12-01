<?php

/**
 * UsersMultimedia form base class.
 *
 * @method UsersMultimedia getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseUsersMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'person_user_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => false)),
      'category'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'person_user_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'))),
      'object_ref'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'))),
      'category'        => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersMultimedia';
  }

}
