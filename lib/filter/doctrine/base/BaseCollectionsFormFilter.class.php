<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Collections filter form base class.
 *
 * @package    filters
 * @subpackage Collections *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCollectionsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_type'          => new sfWidgetFormFilterInput(),
      'code'                     => new sfWidgetFormFilterInput(),
      'name'                     => new sfWidgetFormFilterInput(),
      'institution_ref'          => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
      'main_manager_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'parent_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'path'                     => new sfWidgetFormFilterInput(),
      'code_auto_increment'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'code_part_code_auto_copy' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'collection_type'          => new sfValidatorPass(array('required' => false)),
      'code'                     => new sfValidatorPass(array('required' => false)),
      'name'                     => new sfValidatorPass(array('required' => false)),
      'institution_ref'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
      'main_manager_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'parent_ref'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Collections', 'column' => 'id')),
      'path'                     => new sfValidatorPass(array('required' => false)),
      'code_auto_increment'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'code_part_code_auto_copy' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('collections_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collections';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'collection_type'          => 'Text',
      'code'                     => 'Text',
      'name'                     => 'Text',
      'institution_ref'          => 'ForeignKey',
      'main_manager_ref'         => 'ForeignKey',
      'parent_ref'               => 'ForeignKey',
      'path'                     => 'Text',
      'code_auto_increment'      => 'Boolean',
      'code_part_code_auto_copy' => 'Boolean',
    );
  }
}