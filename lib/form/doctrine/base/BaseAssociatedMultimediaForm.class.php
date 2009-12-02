<?php

/**
 * AssociatedMultimedia form base class.
 *
 * @method AssociatedMultimedia getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseAssociatedMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInputText(),
      'multimedia_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'multimedia_ref'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'))),
    ));

    $this->widgetSchema->setNameFormat('associated_multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'AssociatedMultimedia';
  }

}
