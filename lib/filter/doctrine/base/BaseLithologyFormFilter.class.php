<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Lithology filter form base class.
 *
 * @package    filters
 * @subpackage Lithology *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseLithologyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                    => new sfWidgetFormFilterInput(),
      'name_indexed'            => new sfWidgetFormFilterInput(),
      'description_year'        => new sfWidgetFormFilterInput(),
      'description_year_compl'  => new sfWidgetFormFilterInput(),
      'level_ref'               => new sfWidgetFormFilterInput(),
      'status'                  => new sfWidgetFormFilterInput(),
      'path'                    => new sfWidgetFormFilterInput(),
      'parent_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'Lithology', 'add_empty' => true)),
      'unit_main_group_ref'     => new sfWidgetFormFilterInput(),
      'unit_main_group_indexed' => new sfWidgetFormFilterInput(),
      'unit_group_ref'          => new sfWidgetFormFilterInput(),
      'unit_group_indexed'      => new sfWidgetFormFilterInput(),
      'unit_sub_group_ref'      => new sfWidgetFormFilterInput(),
      'unit_sub_group_indexed'  => new sfWidgetFormFilterInput(),
      'unit_rock_ref'           => new sfWidgetFormFilterInput(),
      'unit_rock_indexed'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                    => new sfValidatorPass(array('required' => false)),
      'name_indexed'            => new sfValidatorPass(array('required' => false)),
      'description_year'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description_year_compl'  => new sfValidatorPass(array('required' => false)),
      'level_ref'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                  => new sfValidatorPass(array('required' => false)),
      'path'                    => new sfValidatorPass(array('required' => false)),
      'parent_ref'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Lithology', 'column' => 'id')),
      'unit_main_group_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_main_group_indexed' => new sfValidatorPass(array('required' => false)),
      'unit_group_ref'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_group_indexed'      => new sfValidatorPass(array('required' => false)),
      'unit_sub_group_ref'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_sub_group_indexed'  => new sfValidatorPass(array('required' => false)),
      'unit_rock_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_rock_indexed'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lithology_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithology';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'name'                    => 'Text',
      'name_indexed'            => 'Text',
      'description_year'        => 'Number',
      'description_year_compl'  => 'Text',
      'level_ref'               => 'Number',
      'status'                  => 'Text',
      'path'                    => 'Text',
      'parent_ref'              => 'ForeignKey',
      'unit_main_group_ref'     => 'Number',
      'unit_main_group_indexed' => 'Text',
      'unit_group_ref'          => 'Number',
      'unit_group_indexed'      => 'Text',
      'unit_sub_group_ref'      => 'Number',
      'unit_sub_group_indexed'  => 'Text',
      'unit_rock_ref'           => 'Number',
      'unit_rock_indexed'       => 'Text',
    );
  }
}