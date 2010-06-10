<?php

/**
 * MySavedSpecimens form base class.
 *
 * @method MySavedSpecimens getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
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
      'modification_date_time' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'user_ref'               => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'name'                   => new sfValidatorChoice(array('choices' => array($this->getObject()->get('name')), 'empty_value' => $this->getObject()->get('name'), 'required' => false)),
      'specimen_ids'           => new sfValidatorString(),
      'favorite'               => new sfValidatorBoolean(array('required' => false)),
      'modification_date_time' => new sfValidatorString(),
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
