<?php

/**
 * PossibleUpperLevels form base class.
 *
 * @package    form
 * @subpackage possible_upper_levels
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePossibleUpperLevelsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'level_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueLevels', 'add_empty' => false)),
      'level_upper_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueLevels', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'PossibleUpperLevels', 'column' => 'id', 'required' => false)),
      'level_ref'       => new sfValidatorDoctrineChoice(array('model' => 'CatalogueLevels')),
      'level_upper_ref' => new sfValidatorDoctrineChoice(array('model' => 'CatalogueLevels')),
    ));

    $this->widgetSchema->setNameFormat('possible_upper_levels[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PossibleUpperLevels';
  }

}
