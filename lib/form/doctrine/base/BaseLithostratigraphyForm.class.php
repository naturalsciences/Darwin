<?php

/**
 * Lithostratigraphy form base class.
 *
 * @method Lithostratigraphy getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLithostratigraphyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormTextarea(),
      'name_indexed'        => new sfWidgetFormTextarea(),
      'level_ref'           => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormTextarea(),
      'path'                => new sfWidgetFormTextarea(),
      'parent_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => false)),
      'group_ref'           => new sfWidgetFormInputText(),
      'group_indexed'       => new sfWidgetFormTextarea(),
      'formation_ref'       => new sfWidgetFormInputText(),
      'formation_indexed'   => new sfWidgetFormTextarea(),
      'member_ref'          => new sfWidgetFormInputText(),
      'member_indexed'      => new sfWidgetFormTextarea(),
      'layer_ref'           => new sfWidgetFormInputText(),
      'layer_indexed'       => new sfWidgetFormTextarea(),
      'sub_level_1_ref'     => new sfWidgetFormInputText(),
      'sub_level_1_indexed' => new sfWidgetFormTextarea(),
      'sub_level_2_ref'     => new sfWidgetFormInputText(),
      'sub_level_2_indexed' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'                => new sfValidatorString(),
      'name_indexed'        => new sfValidatorString(array('required' => false)),
      'level_ref'           => new sfValidatorInteger(),
      'status'              => new sfValidatorString(array('required' => false)),
      'path'                => new sfValidatorString(array('required' => false)),
      'parent_ref'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'group_ref'           => new sfValidatorInteger(array('required' => false)),
      'group_indexed'       => new sfValidatorString(array('required' => false)),
      'formation_ref'       => new sfValidatorInteger(array('required' => false)),
      'formation_indexed'   => new sfValidatorString(array('required' => false)),
      'member_ref'          => new sfValidatorInteger(array('required' => false)),
      'member_indexed'      => new sfValidatorString(array('required' => false)),
      'layer_ref'           => new sfValidatorInteger(array('required' => false)),
      'layer_indexed'       => new sfValidatorString(array('required' => false)),
      'sub_level_1_ref'     => new sfValidatorInteger(array('required' => false)),
      'sub_level_1_indexed' => new sfValidatorString(array('required' => false)),
      'sub_level_2_ref'     => new sfValidatorInteger(array('required' => false)),
      'sub_level_2_indexed' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lithostratigraphy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithostratigraphy';
  }

}
