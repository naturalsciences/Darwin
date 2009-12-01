<?php

/**
 * GtuTags form base class.
 *
 * @method GtuTags getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseGtuTagsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'tag_group_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TagGroups'), 'add_empty' => false)),
      'gtu_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'tag_group_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('TagGroups'))),
      'gtu_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'))),
    ));

    $this->widgetSchema->setNameFormat('gtu_tags[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GtuTags';
  }

}
