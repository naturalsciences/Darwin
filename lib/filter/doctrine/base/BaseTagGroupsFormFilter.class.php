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
      'tag_ref'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'group_name'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'group_name_indexed'     => new sfWidgetFormFilterInput(),
      'sub_group_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_group_name_indexed' => new sfWidgetFormFilterInput(),
      'group_color'            => new sfWidgetFormFilterInput(),
      'tag_value'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tag_value_indexed'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'tag_ref'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_name'             => new sfValidatorPass(array('required' => false)),
      'group_name_indexed'     => new sfValidatorPass(array('required' => false)),
      'sub_group_name'         => new sfValidatorPass(array('required' => false)),
      'sub_group_name_indexed' => new sfValidatorPass(array('required' => false)),
      'group_color'            => new sfValidatorPass(array('required' => false)),
      'tag_value'              => new sfValidatorPass(array('required' => false)),
      'tag_value_indexed'      => new sfValidatorPass(array('required' => false)),
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
      'tag_ref'                => 'Number',
      'group_name'             => 'Text',
      'group_name_indexed'     => 'Text',
      'sub_group_name'         => 'Text',
      'sub_group_name_indexed' => 'Text',
      'group_color'            => 'Text',
      'tag_value'              => 'Text',
      'tag_value_indexed'      => 'Text',
    );
  }
}
