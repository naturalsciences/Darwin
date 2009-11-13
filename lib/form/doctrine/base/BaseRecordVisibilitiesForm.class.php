<?php

/**
 * RecordVisibilities form base class.
 *
 * @package    form
 * @subpackage record_visibilities
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseRecordVisibilitiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInput(),
      'db_user_type'        => new sfWidgetFormInput(),
      'user_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'visible'             => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'RecordVisibilities', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'db_user_type'        => new sfValidatorInteger(),
      'user_ref'            => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'visible'             => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('record_visibilities[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RecordVisibilities';
  }

}
