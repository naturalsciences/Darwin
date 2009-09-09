<?php

/**
 * Lithology form base class.
 *
 * @package    form
 * @subpackage lithology
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseLithologyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'name'                    => new sfWidgetFormTextarea(),
      'name_indexed'            => new sfWidgetFormTextarea(),
      'level_ref'               => new sfWidgetFormInput(),
      'status'                  => new sfWidgetFormTextarea(),
      'path'                    => new sfWidgetFormTextarea(),
      'parent_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'Lithology', 'add_empty' => false)),
      'unit_main_group_ref'     => new sfWidgetFormInput(),
      'unit_main_group_indexed' => new sfWidgetFormTextarea(),
      'unit_group_ref'          => new sfWidgetFormInput(),
      'unit_group_indexed'      => new sfWidgetFormTextarea(),
      'unit_sub_group_ref'      => new sfWidgetFormInput(),
      'unit_sub_group_indexed'  => new sfWidgetFormTextarea(),
      'unit_rock_ref'           => new sfWidgetFormInput(),
      'unit_rock_indexed'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => 'Lithology', 'column' => 'id', 'required' => false)),
      'name'                    => new sfValidatorString(array('max_length' => 2147483647)),
      'name_indexed'            => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'level_ref'               => new sfValidatorInteger(array('required' => false)),
      'status'                  => new sfValidatorString(array('max_length' => 2147483647)),
      'path'                    => new sfValidatorString(array('max_length' => 2147483647)),
      'parent_ref'              => new sfValidatorDoctrineChoice(array('model' => 'Lithology')),
      'unit_main_group_ref'     => new sfValidatorInteger(),
      'unit_main_group_indexed' => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_group_ref'          => new sfValidatorInteger(),
      'unit_group_indexed'      => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_sub_group_ref'      => new sfValidatorInteger(),
      'unit_sub_group_indexed'  => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_rock_ref'           => new sfValidatorInteger(),
      'unit_rock_indexed'       => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('lithology[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithology';
  }

}
