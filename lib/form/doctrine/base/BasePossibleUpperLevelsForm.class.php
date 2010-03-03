<?php

/**
 * PossibleUpperLevels form base class.
 *
 * @method PossibleUpperLevels getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePossibleUpperLevelsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'level_ref'       => new sfWidgetFormInputHidden(),
      'level_upper_ref' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'level_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'level_ref', 'required' => false)),
      'level_upper_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'level_upper_ref', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('possible_upper_levels[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PossibleUpperLevels';
  }

}
