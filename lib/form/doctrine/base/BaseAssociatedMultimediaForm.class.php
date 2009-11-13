<?php

/**
 * AssociatedMultimedia form base class.
 *
 * @package    form
 * @subpackage associated_multimedia
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseAssociatedMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInput(),
      'multimedia_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'AssociatedMultimedia', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'multimedia_ref'      => new sfValidatorDoctrineChoice(array('model' => 'Multimedia')),
    ));

    $this->widgetSchema->setNameFormat('associated_multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AssociatedMultimedia';
  }

}
