<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PossibleUpperLevels filter form base class.
 *
 * @package    filters
 * @subpackage PossibleUpperLevels *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePossibleUpperLevelsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'level_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueLevels', 'add_empty' => true)),
      'level_upper_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'CatalogueLevels', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'level_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'CatalogueLevels', 'column' => 'id')),
      'level_upper_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'CatalogueLevels', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('possible_upper_levels_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PossibleUpperLevels';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'level_ref'       => 'ForeignKey',
      'level_upper_ref' => 'ForeignKey',
    );
  }
}