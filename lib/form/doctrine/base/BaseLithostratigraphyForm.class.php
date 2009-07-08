<?php

/**
 * Lithostratigraphy form base class.
 *
 * @package    form
 * @subpackage lithostratigraphy
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseLithostratigraphyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormTextarea(),
      'name_indexed'           => new sfWidgetFormTextarea(),
      'description_year'       => new sfWidgetFormInput(),
      'description_year_compl' => new sfWidgetFormInput(),
      'level_ref'              => new sfWidgetFormInput(),
      'status'                 => new sfWidgetFormTextarea(),
      'path'                   => new sfWidgetFormTextarea(),
      'parent_ref'             => new sfWidgetFormDoctrineChoice(array('model' => 'Lithostratigraphy', 'add_empty' => false)),
      'group_ref'              => new sfWidgetFormInput(),
      'group_indexed'          => new sfWidgetFormTextarea(),
      'formation_ref'          => new sfWidgetFormInput(),
      'formation_indexed'      => new sfWidgetFormTextarea(),
      'member_ref'             => new sfWidgetFormInput(),
      'member_indexed'         => new sfWidgetFormTextarea(),
      'layer_ref'              => new sfWidgetFormInput(),
      'layer_indexed'          => new sfWidgetFormTextarea(),
      'sub_level_1_ref'        => new sfWidgetFormInput(),
      'sub_level_1_indexed'    => new sfWidgetFormTextarea(),
      'sub_level_2_ref'        => new sfWidgetFormInput(),
      'sub_level_2_indexed'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'Lithostratigraphy', 'column' => 'id', 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'name_indexed'           => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'description_year'       => new sfValidatorInteger(array('required' => false)),
      'description_year_compl' => new sfValidatorString(array('max_length' => 2, 'required' => false)),
      'level_ref'              => new sfValidatorInteger(array('required' => false)),
      'status'                 => new sfValidatorString(array('max_length' => 2147483647)),
      'path'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'parent_ref'             => new sfValidatorDoctrineChoice(array('model' => 'Lithostratigraphy')),
      'group_ref'              => new sfValidatorInteger(),
      'group_indexed'          => new sfValidatorString(array('max_length' => 2147483647)),
      'formation_ref'          => new sfValidatorInteger(),
      'formation_indexed'      => new sfValidatorString(array('max_length' => 2147483647)),
      'member_ref'             => new sfValidatorInteger(),
      'member_indexed'         => new sfValidatorString(array('max_length' => 2147483647)),
      'layer_ref'              => new sfValidatorInteger(),
      'layer_indexed'          => new sfValidatorString(array('max_length' => 2147483647)),
      'sub_level_1_ref'        => new sfValidatorInteger(),
      'sub_level_1_indexed'    => new sfValidatorString(array('max_length' => 2147483647)),
      'sub_level_2_ref'        => new sfValidatorInteger(),
      'sub_level_2_indexed'    => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('lithostratigraphy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithostratigraphy';
  }

}
