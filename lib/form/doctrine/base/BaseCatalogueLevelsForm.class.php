<?php

/**
 * CatalogueLevels form base class.
 *
 * @package    form
 * @subpackage catalogue_levels
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCatalogueLevelsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'level_type'     => new sfWidgetFormTextarea(),
      'level_name'     => new sfWidgetFormTextarea(),
      'level_sys_name' => new sfWidgetFormTextarea(),
      'optional_level' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => 'CatalogueLevels', 'column' => 'id', 'required' => false)),
      'level_type'     => new sfValidatorString(),
      'level_name'     => new sfValidatorString(),
      'level_sys_name' => new sfValidatorString(),
      'optional_level' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('catalogue_levels[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueLevels';
  }

}
