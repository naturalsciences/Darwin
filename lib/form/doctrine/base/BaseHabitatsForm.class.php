<?php

/**
 * Habitats form base class.
 *
 * @package    form
 * @subpackage habitats
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseHabitatsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'name'                           => new sfWidgetFormTextarea(),
      'path'                           => new sfWidgetFormTextarea(),
      'code'                           => new sfWidgetFormTextarea(),
      'code_indexed'                   => new sfWidgetFormTextarea(),
      'description'                    => new sfWidgetFormTextarea(),
      'description_ts'                 => new sfWidgetFormTextarea(),
      'description_language_full_text' => new sfWidgetFormTextarea(),
      'habitat_system'                 => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorDoctrineChoice(array('model' => 'Habitats', 'column' => 'id', 'required' => false)),
      'name'                           => new sfValidatorString(array('max_length' => 2147483647)),
      'path'                           => new sfValidatorString(array('max_length' => 2147483647)),
      'code'                           => new sfValidatorString(array('max_length' => 2147483647)),
      'code_indexed'                   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'description'                    => new sfValidatorString(array('max_length' => 2147483647)),
      'description_ts'                 => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'description_language_full_text' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'habitat_system'                 => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('habitats[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Habitats';
  }

}
