<?php

/**
 * GtuTags filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGtuTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag_group_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('TagGroups'), 'add_empty' => true)),
      'gtu_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'tag_group_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('TagGroups'), 'column' => 'id')),
      'gtu_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Gtu'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('gtu_tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GtuTags';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'tag_group_ref' => 'ForeignKey',
      'gtu_ref'       => 'ForeignKey',
    );
  }
}
