<?php

/**
 * Expertises form base class.
 *
 * @package    form
 * @subpackage expertises
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseExpertisesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'                  => new sfWidgetFormInputHidden(),
      'record_id'                   => new sfWidgetFormInputHidden(),
      'expert_ref'                  => new sfWidgetFormInput(),
      'defined_by_ordered_ids_list' => new sfWidgetFormTextarea(),
      'order_by'                    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'table_name'                  => new sfValidatorDoctrineChoice(array('model' => 'Expertises', 'column' => 'table_name', 'required' => false)),
      'record_id'                   => new sfValidatorDoctrineChoice(array('model' => 'Expertises', 'column' => 'record_id', 'required' => false)),
      'expert_ref'                  => new sfValidatorInteger(),
      'defined_by_ordered_ids_list' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'order_by'                    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('expertises[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Expertises';
  }

}
