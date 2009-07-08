<?php

/**
 * Expeditions form base class.
 *
 * @package    form
 * @subpackage expeditions
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseExpeditionsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'name'                      => new sfWidgetFormTextarea(),
      'name_ts'                   => new sfWidgetFormTextarea(),
      'name_indexed'              => new sfWidgetFormTextarea(),
      'name_language_full_text'   => new sfWidgetFormTextarea(),
      'expedition_from_date_mask' => new sfWidgetFormInput(),
      'expedition_from_date'      => new sfWidgetFormDate(),
      'expedition_to_date_mask'   => new sfWidgetFormInput(),
      'expedition_to_date'        => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'Expeditions', 'column' => 'id', 'required' => false)),
      'name'                      => new sfValidatorString(array('max_length' => 2147483647)),
      'name_ts'                   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'name_indexed'              => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'name_language_full_text'   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'expedition_from_date_mask' => new sfValidatorInteger(),
      'expedition_from_date'      => new sfValidatorDate(array('required' => false)),
      'expedition_to_date_mask'   => new sfValidatorInteger(),
      'expedition_to_date'        => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('expeditions[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Expeditions';
  }

}
