<?php

/**
 * UsersMultimedia form base class.
 *
 * @package    form
 * @subpackage users_multimedia
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'person_user_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => false)),
      'category'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'UsersMultimedia', 'column' => 'id', 'required' => false)),
      'person_user_ref' => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'object_ref'      => new sfValidatorDoctrineChoice(array('model' => 'Multimedia')),
      'category'        => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('users_multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersMultimedia';
  }

}
