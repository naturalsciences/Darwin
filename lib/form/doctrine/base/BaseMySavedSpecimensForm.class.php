<?php

/**
 * MySavedSpecimens form base class.
 *
 * @method MySavedSpecimens getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMySavedSpecimensForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'               => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormInputHidden(),
      'specimen_ids'           => new sfWidgetFormTextarea(),
      'favorite'               => new sfWidgetFormInputCheckbox(),
      'modification_date_time' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'user_ref'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_ref', 'required' => false)),
      'name'                   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'name', 'required' => false)),
      'specimen_ids'           => new sfValidatorString(),
      'favorite'               => new sfValidatorBoolean(array('required' => false)),
      'modification_date_time' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('my_saved_specimens[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSpecimens';
  }

}
