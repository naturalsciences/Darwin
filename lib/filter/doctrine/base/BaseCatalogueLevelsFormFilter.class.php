<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CatalogueLevels filter form base class.
 *
 * @package    filters
 * @subpackage CatalogueLevels *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCatalogueLevelsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'level_type'     => new sfWidgetFormFilterInput(),
      'level_name'     => new sfWidgetFormFilterInput(),
      'level_sys_name' => new sfWidgetFormFilterInput(),
      'optional_level' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'level_type'     => new sfValidatorPass(array('required' => false)),
      'level_name'     => new sfValidatorPass(array('required' => false)),
      'level_sys_name' => new sfValidatorPass(array('required' => false)),
      'optional_level' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('catalogue_levels_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueLevels';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'level_type'     => 'Text',
      'level_name'     => 'Text',
      'level_sys_name' => 'Text',
      'optional_level' => 'Boolean',
    );
  }
}