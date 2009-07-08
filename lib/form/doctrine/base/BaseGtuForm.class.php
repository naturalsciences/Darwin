<?php

/**
 * Gtu form base class.
 *
 * @package    form
 * @subpackage gtu
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseGtuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'code'               => new sfWidgetFormTextarea(),
      'parent_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => false)),
      'gtu_from_date_mask' => new sfWidgetFormInput(),
      'gtu_from_date'      => new sfWidgetFormDateTime(),
      'gtu_to_date_mask'   => new sfWidgetFormInput(),
      'gtu_to_date'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'Gtu', 'column' => 'id', 'required' => false)),
      'code'               => new sfValidatorString(array('max_length' => 2147483647)),
      'parent_ref'         => new sfValidatorDoctrineChoice(array('model' => 'Gtu')),
      'gtu_from_date_mask' => new sfValidatorInteger(),
      'gtu_from_date'      => new sfValidatorDateTime(),
      'gtu_to_date_mask'   => new sfValidatorInteger(),
      'gtu_to_date'        => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gtu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gtu';
  }

}
