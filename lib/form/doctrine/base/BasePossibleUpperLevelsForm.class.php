<?php

/**
 * PossibleUpperLevels form base class.
 *
 * @method PossibleUpperLevels getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BasePossibleUpperLevelsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'level_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => false)),
      'level_upper_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UpperLevel'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'level_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Level'))),
      'level_upper_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UpperLevel'))),
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
