<?php

/**
 * TagGroups form base class.
 *
 * @package    form
 * @subpackage tag_groups
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseTagGroupsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'tag_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Tags', 'add_empty' => false)),
      'group_name'             => new sfWidgetFormTextarea(),
      'group_name_indexed'     => new sfWidgetFormTextarea(),
      'sub_group_name'         => new sfWidgetFormTextarea(),
      'sub_group_name_indexed' => new sfWidgetFormTextarea(),
      'color'                  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'TagGroups', 'column' => 'id', 'required' => false)),
      'tag_ref'                => new sfValidatorDoctrineChoice(array('model' => 'Tags')),
      'group_name'             => new sfValidatorString(),
      'group_name_indexed'     => new sfValidatorString(array('required' => false)),
      'sub_group_name'         => new sfValidatorString(),
      'sub_group_name_indexed' => new sfValidatorString(array('required' => false)),
      'color'                  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag_groups[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TagGroups';
  }

}
