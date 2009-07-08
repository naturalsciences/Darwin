<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Soortenregister filter form base class.
 *
 * @package    filters
 * @subpackage Soortenregister *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSoortenregisterFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'taxa_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => true)),
      'gtu_ref'     => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => true)),
      'habitat_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Habitats', 'add_empty' => true)),
      'date_from'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'date_to'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'taxa_ref'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Taxonomy', 'column' => 'id')),
      'gtu_ref'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Gtu', 'column' => 'id')),
      'habitat_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Habitats', 'column' => 'id')),
      'date_from'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'date_to'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('soortenregister_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Soortenregister';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'taxa_ref'    => 'ForeignKey',
      'gtu_ref'     => 'ForeignKey',
      'habitat_ref' => 'ForeignKey',
      'date_from'   => 'Date',
      'date_to'     => 'Date',
    );
  }
}