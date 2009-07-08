<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * GtuTags filter form base class.
 *
 * @package    filters
 * @subpackage GtuTags *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseGtuTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag_group_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'TagGroups', 'add_empty' => true)),
      'gtu_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'tag_group_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'TagGroups', 'column' => 'id')),
      'gtu_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Gtu', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('gtu_tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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