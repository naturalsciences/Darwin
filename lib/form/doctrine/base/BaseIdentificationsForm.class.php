<?php

/**
 * Identifications form base class.
 *
 * @package    form
 * @subpackage identifications
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseIdentificationsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'referenced_relation'   => new sfWidgetFormTextarea(),
      'record_id'             => new sfWidgetFormInput(),
      'notion_concerned'      => new sfWidgetFormTextarea(),
      'notion_date'           => new sfWidgetFormDateTime(),
      'value_defined'         => new sfWidgetFormTextarea(),
      'value_defined_indexed' => new sfWidgetFormTextarea(),
      'value_defined_ts'      => new sfWidgetFormTextarea(),
      'determination_status'  => new sfWidgetFormTextarea(),
      'order_by'              => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'Identifications', 'column' => 'id', 'required' => false)),
      'referenced_relation'   => new sfValidatorString(),
      'record_id'             => new sfValidatorInteger(),
      'notion_concerned'      => new sfValidatorString(),
      'notion_date'           => new sfValidatorDateTime(array('required' => false)),
      'value_defined'         => new sfValidatorString(array('required' => false)),
      'value_defined_indexed' => new sfValidatorString(array('required' => false)),
      'value_defined_ts'      => new sfValidatorString(array('required' => false)),
      'determination_status'  => new sfValidatorString(array('required' => false)),
      'order_by'              => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('identifications[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Identifications';
  }

}
