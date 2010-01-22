<?php

/**
 * PossibleUpperLevels filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePossibleUpperLevelsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'level_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => true)),
      'level_upper_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UpperLevel'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'level_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Level'), 'column' => 'id')),
      'level_upper_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UpperLevel'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('possible_upper_levels_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

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
