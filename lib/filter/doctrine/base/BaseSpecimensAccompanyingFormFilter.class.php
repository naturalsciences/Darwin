<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * SpecimensAccompanying filter form base class.
 *
 * @package    filters
 * @subpackage SpecimensAccompanying *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSpecimensAccompanyingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'type'         => new sfWidgetFormFilterInput(),
      'specimen_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => true)),
      'taxon_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => true)),
      'mineral_ref'  => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => true)),
      'form'         => new sfWidgetFormFilterInput(),
      'quantity'     => new sfWidgetFormFilterInput(),
      'unit'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'type'         => new sfValidatorPass(array('required' => false)),
      'specimen_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Specimens', 'column' => 'id')),
      'taxon_ref'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Taxonomy', 'column' => 'id')),
      'mineral_ref'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Mineralogy', 'column' => 'id')),
      'form'         => new sfValidatorPass(array('required' => false)),
      'quantity'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'unit'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimens_accompanying_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimensAccompanying';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'type'         => 'Text',
      'specimen_ref' => 'ForeignKey',
      'taxon_ref'    => 'ForeignKey',
      'mineral_ref'  => 'ForeignKey',
      'form'         => 'Text',
      'quantity'     => 'Number',
      'unit'         => 'Text',
    );
  }
}