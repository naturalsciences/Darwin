<?php

/**
 * TagGroups filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTagGroupsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag_ref'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tags'), 'add_empty' => true)),
      'group_name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'group_name_indexed'     => new sfWidgetFormFilterInput(),
      'sub_group_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_group_name_indexed' => new sfWidgetFormFilterInput(),
      'color'                  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tag_ref'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Tags'), 'column' => 'id')),
      'group_name'             => new sfValidatorPass(array('required' => false)),
      'group_name_indexed'     => new sfValidatorPass(array('required' => false)),
      'sub_group_name'         => new sfValidatorPass(array('required' => false)),
      'sub_group_name_indexed' => new sfValidatorPass(array('required' => false)),
      'color'                  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag_groups_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TagGroups';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'tag_ref'                => 'ForeignKey',
      'group_name'             => 'Text',
      'group_name_indexed'     => 'Text',
      'sub_group_name'         => 'Text',
      'sub_group_name_indexed' => 'Text',
      'color'                  => 'Text',
    );
  }
}
