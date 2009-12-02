<?php

/**
 * CatalogueLevels form base class.
 *
 * @method CatalogueLevels getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCatalogueLevelsForm extends BaseFormDoctrine
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
      'id'             => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'level_type'     => new sfValidatorString(),
      'level_name'     => new sfValidatorString(),
      'level_sys_name' => new sfValidatorString(),
      'optional_level' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('catalogue_levels[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueLevels';
  }

}
