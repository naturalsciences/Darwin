<?php

/**
 * Chronostratigraphy filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChronostratigraphyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name_indexed'        => new sfWidgetFormFilterInput(),
      'name_order_by'       => new sfWidgetFormFilterInput(),
      'level_ref'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => true)),
      'status'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                => new sfWidgetFormFilterInput(),
      'parent_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'eon_ref'             => new sfWidgetFormFilterInput(),
      'eon_indexed'         => new sfWidgetFormFilterInput(),
      'era_ref'             => new sfWidgetFormFilterInput(),
      'era_indexed'         => new sfWidgetFormFilterInput(),
      'sub_era_ref'         => new sfWidgetFormFilterInput(),
      'sub_era_indexed'     => new sfWidgetFormFilterInput(),
      'system_ref'          => new sfWidgetFormFilterInput(),
      'system_indexed'      => new sfWidgetFormFilterInput(),
      'serie_ref'           => new sfWidgetFormFilterInput(),
      'serie_indexed'       => new sfWidgetFormFilterInput(),
      'stage_ref'           => new sfWidgetFormFilterInput(),
      'stage_indexed'       => new sfWidgetFormFilterInput(),
      'sub_stage_ref'       => new sfWidgetFormFilterInput(),
      'sub_stage_indexed'   => new sfWidgetFormFilterInput(),
      'sub_level_1_ref'     => new sfWidgetFormFilterInput(),
      'sub_level_1_indexed' => new sfWidgetFormFilterInput(),
      'sub_level_2_ref'     => new sfWidgetFormFilterInput(),
      'sub_level_2_indexed' => new sfWidgetFormFilterInput(),
      'lower_bound'         => new sfWidgetFormFilterInput(),
      'upper_bound'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                => new sfValidatorPass(array('required' => false)),
      'name_indexed'        => new sfValidatorPass(array('required' => false)),
      'name_order_by'       => new sfValidatorPass(array('required' => false)),
      'level_ref'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Level'), 'column' => 'id')),
      'status'              => new sfValidatorPass(array('required' => false)),
      'path'                => new sfValidatorPass(array('required' => false)),
      'parent_ref'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'eon_ref'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'eon_indexed'         => new sfValidatorPass(array('required' => false)),
      'era_ref'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'era_indexed'         => new sfValidatorPass(array('required' => false)),
      'sub_era_ref'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_era_indexed'     => new sfValidatorPass(array('required' => false)),
      'system_ref'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'system_indexed'      => new sfValidatorPass(array('required' => false)),
      'serie_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'serie_indexed'       => new sfValidatorPass(array('required' => false)),
      'stage_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'stage_indexed'       => new sfValidatorPass(array('required' => false)),
      'sub_stage_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_stage_indexed'   => new sfValidatorPass(array('required' => false)),
      'sub_level_1_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_1_indexed' => new sfValidatorPass(array('required' => false)),
      'sub_level_2_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_2_indexed' => new sfValidatorPass(array('required' => false)),
      'lower_bound'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'upper_bound'         => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('chronostratigraphy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chronostratigraphy';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'name'                => 'Text',
      'name_indexed'        => 'Text',
      'name_order_by'       => 'Text',
      'level_ref'           => 'ForeignKey',
      'status'              => 'Text',
      'path'                => 'Text',
      'parent_ref'          => 'ForeignKey',
      'eon_ref'             => 'Number',
      'eon_indexed'         => 'Text',
      'era_ref'             => 'Number',
      'era_indexed'         => 'Text',
      'sub_era_ref'         => 'Number',
      'sub_era_indexed'     => 'Text',
      'system_ref'          => 'Number',
      'system_indexed'      => 'Text',
      'serie_ref'           => 'Number',
      'serie_indexed'       => 'Text',
      'stage_ref'           => 'Number',
      'stage_indexed'       => 'Text',
      'sub_stage_ref'       => 'Number',
      'sub_stage_indexed'   => 'Text',
      'sub_level_1_ref'     => 'Number',
      'sub_level_1_indexed' => 'Text',
      'sub_level_2_ref'     => 'Number',
      'sub_level_2_indexed' => 'Text',
      'lower_bound'         => 'Number',
      'upper_bound'         => 'Number',
    );
  }
}
