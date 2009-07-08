<?php

/**
 * GtuTags form base class.
 *
 * @package    form
 * @subpackage gtu_tags
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseGtuTagsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'tag_group_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'TagGroups', 'add_empty' => false)),
      'gtu_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => 'GtuTags', 'column' => 'id', 'required' => false)),
      'tag_group_ref' => new sfValidatorDoctrineChoice(array('model' => 'TagGroups')),
      'gtu_ref'       => new sfValidatorDoctrineChoice(array('model' => 'Gtu')),
    ));

    $this->widgetSchema->setNameFormat('gtu_tags[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'GtuTags';
  }

}
