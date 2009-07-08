<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * TagGroups filter form base class.
 *
 * @package    filters
 * @subpackage TagGroups *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseTagGroupsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Tags', 'add_empty' => true)),
      'group_name'             => new sfWidgetFormFilterInput(),
      'group_name_indexed'     => new sfWidgetFormFilterInput(),
      'sub_group_name'         => new sfWidgetFormFilterInput(),
      'sub_group_name_indexed' => new sfWidgetFormFilterInput(),
      'color'                  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tag_ref'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Tags', 'column' => 'id')),
      'group_name'             => new sfValidatorPass(array('required' => false)),
      'group_name_indexed'     => new sfValidatorPass(array('required' => false)),
      'sub_group_name'         => new sfValidatorPass(array('required' => false)),
      'sub_group_name_indexed' => new sfValidatorPass(array('required' => false)),
      'color'                  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tag_groups_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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