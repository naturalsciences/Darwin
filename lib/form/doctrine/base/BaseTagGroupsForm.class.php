<?php

/**
 * TagGroups form base class.
 *
 * @method TagGroups getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseTagGroupsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'tag_ref'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tags'), 'add_empty' => false)),
      'group_name'             => new sfWidgetFormTextarea(),
      'group_name_indexed'     => new sfWidgetFormTextarea(),
      'sub_group_name'         => new sfWidgetFormTextarea(),
      'sub_group_name_indexed' => new sfWidgetFormTextarea(),
      'color'                  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'tag_ref'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tags'))),
      'group_name'             => new sfValidatorString(),
      'group_name_indexed'     => new sfValidatorString(array('required' => false)),
      'sub_group_name'         => new sfValidatorString(),
      'sub_group_name_indexed' => new sfValidatorString(array('required' => false)),
      'color'                  => new sfValidatorString(array('required' => false)),
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
