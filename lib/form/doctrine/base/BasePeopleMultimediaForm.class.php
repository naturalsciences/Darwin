<?php

/**
 * PeopleMultimedia form base class.
 *
 * @package    form
 * @subpackage people_multimedia
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'person_user_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => false)),
      'category'        => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'PeopleMultimedia', 'column' => 'id', 'required' => false)),
      'person_user_ref' => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'object_ref'      => new sfValidatorDoctrineChoice(array('model' => 'Multimedia')),
      'category'        => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('people_multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleMultimedia';
  }

}
