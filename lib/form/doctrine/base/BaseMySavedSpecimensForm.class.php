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
      'id'                     => new sfWidgetFormInputHidden(),
      'user_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'name'                   => new sfWidgetFormTextarea(),
      'specimen_ids'           => new sfWidgetFormTextarea(),
      'favorite'               => new sfWidgetFormInputCheckbox(),
      'modification_date_time' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'MySavedSpecimens', 'column' => 'id', 'required' => false)),
      'user_ref'               => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'name'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'specimen_ids'           => new sfValidatorString(array('max_length' => 2147483647)),
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
