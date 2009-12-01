<?php

/**
 * Expeditions form base class.
 *
 * @method Expeditions getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseExpeditionsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'name'                      => new sfWidgetFormTextarea(),
      'name_ts'                   => new sfWidgetFormTextarea(),
      'name_indexed'              => new sfWidgetFormTextarea(),
      'name_language_full_text'   => new sfWidgetFormTextarea(),
      'expedition_from_date_mask' => new sfWidgetFormInputText(),
      'expedition_from_date'      => new sfWidgetFormDate(),
      'expedition_to_date_mask'   => new sfWidgetFormInputText(),
      'expedition_to_date'        => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'                      => new sfValidatorString(),
      'name_ts'                   => new sfValidatorString(array('required' => false)),
      'name_indexed'              => new sfValidatorString(array('required' => false)),
      'name_language_full_text'   => new sfValidatorString(array('required' => false)),
      'expedition_from_date_mask' => new sfValidatorInteger(array('required' => false)),
      'expedition_from_date'      => new sfValidatorDate(array('required' => false)),
      'expedition_to_date_mask'   => new sfValidatorInteger(array('required' => false)),
      'expedition_to_date'        => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('expeditions[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Expeditions';
  }

}
