<?php

/**
 * MySavedSpecimens form base class.
 *
 * @package    form
 * @subpackage my_saved_specimens
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMySavedSpecimensForm extends BaseFormDoctrine
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
      'user_ref'               => new sfValidatorDoctrineChoice(array('model' => 'MySavedSpecimens', 'column' => 'user_ref', 'required' => false)),
      'name'                   => new sfValidatorDoctrineChoice(array('model' => 'MySavedSpecimens', 'column' => 'name', 'required' => false)),
      'specimen_ids'           => new sfValidatorString(),
      'favorite'               => new sfValidatorBoolean(),
      'modification_date_time' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('my_saved_specimens[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSpecimens';
  }

}
