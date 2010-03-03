<?php

/**
 * TagGroups form base class.
 *
 * @method TagGroups getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTagGroupsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'tag_ref'                => new sfWidgetFormInputText(),
      'group_name'             => new sfWidgetFormTextarea(),
      'group_name_indexed'     => new sfWidgetFormTextarea(),
      'sub_group_name'         => new sfWidgetFormTextarea(),
      'sub_group_name_indexed' => new sfWidgetFormTextarea(),
      'group_color'            => new sfWidgetFormTextarea(),
      'tag_value'              => new sfWidgetFormTextarea(),
      'tag_value_indexed'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'tag_ref'                => new sfValidatorInteger(),
      'group_name'             => new sfValidatorString(),
      'group_name_indexed'     => new sfValidatorString(array('required' => false)),
      'sub_group_name'         => new sfValidatorString(),
      'sub_group_name_indexed' => new sfValidatorString(array('required' => false)),
      'group_color'            => new sfValidatorString(array('required' => false)),
      'tag_value'              => new sfValidatorString(),
      'tag_value_indexed'      => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('tag_groups[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TagGroups';
  }

}
