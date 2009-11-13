<?php

/**
 * Codes form base class.
 *
 * @package    form
 * @subpackage codes
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCodesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInput(),
      'code_category'       => new sfWidgetFormTextarea(),
      'code_prefix'         => new sfWidgetFormTextarea(),
      'code'                => new sfWidgetFormInput(),
      'code_suffix'         => new sfWidgetFormTextarea(),
      'full_code_indexed'   => new sfWidgetFormTextarea(),
      'code_date'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'Codes', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'code_category'       => new sfValidatorString(),
      'code_prefix'         => new sfValidatorString(array('required' => false)),
      'code'                => new sfValidatorInteger(array('required' => false)),
      'code_suffix'         => new sfValidatorString(array('required' => false)),
      'full_code_indexed'   => new sfValidatorString(array('required' => false)),
      'code_date'           => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('codes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Codes';
  }

}
