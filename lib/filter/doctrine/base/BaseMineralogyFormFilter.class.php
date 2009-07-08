<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Mineralogy filter form base class.
 *
 * @package    filters
 * @subpackage Mineralogy *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMineralogyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                   => new sfWidgetFormFilterInput(),
      'name_indexed'           => new sfWidgetFormFilterInput(),
      'description_year'       => new sfWidgetFormFilterInput(),
      'description_year_compl' => new sfWidgetFormFilterInput(),
      'level_ref'              => new sfWidgetFormFilterInput(),
      'status'                 => new sfWidgetFormFilterInput(),
      'path'                   => new sfWidgetFormFilterInput(),
      'parent_ref'             => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => true)),
      'code'                   => new sfWidgetFormFilterInput(),
      'classification'         => new sfWidgetFormFilterInput(),
      'formule'                => new sfWidgetFormFilterInput(),
      'formule_indexed'        => new sfWidgetFormFilterInput(),
      'cristal_system'         => new sfWidgetFormFilterInput(),
      'unit_class_ref'         => new sfWidgetFormFilterInput(),
      'unit_class_indexed'     => new sfWidgetFormFilterInput(),
      'unit_division_ref'      => new sfWidgetFormFilterInput(),
      'unit_division_indexed'  => new sfWidgetFormFilterInput(),
      'unit_family_ref'        => new sfWidgetFormFilterInput(),
      'unit_family_indexed'    => new sfWidgetFormFilterInput(),
      'unit_group_ref'         => new sfWidgetFormFilterInput(),
      'unit_group_indexed'     => new sfWidgetFormFilterInput(),
      'unit_variety_ref'       => new sfWidgetFormFilterInput(),
      'unit_variety_indexed'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'name_indexed'           => new sfValidatorPass(array('required' => false)),
      'description_year'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description_year_compl' => new sfValidatorPass(array('required' => false)),
      'level_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                 => new sfValidatorPass(array('required' => false)),
      'path'                   => new sfValidatorPass(array('required' => false)),
      'parent_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Mineralogy', 'column' => 'id')),
      'code'                   => new sfValidatorPass(array('required' => false)),
      'classification'         => new sfValidatorPass(array('required' => false)),
      'formule'                => new sfValidatorPass(array('required' => false)),
      'formule_indexed'        => new sfValidatorPass(array('required' => false)),
      'cristal_system'         => new sfValidatorPass(array('required' => false)),
      'unit_class_ref'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_class_indexed'     => new sfValidatorPass(array('required' => false)),
      'unit_division_ref'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_division_indexed'  => new sfValidatorPass(array('required' => false)),
      'unit_family_ref'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_family_indexed'    => new sfValidatorPass(array('required' => false)),
      'unit_group_ref'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_group_indexed'     => new sfValidatorPass(array('required' => false)),
      'unit_variety_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_variety_indexed'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('mineralogy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mineralogy';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'name'                   => 'Text',
      'name_indexed'           => 'Text',
      'description_year'       => 'Number',
      'description_year_compl' => 'Text',
      'level_ref'              => 'Number',
      'status'                 => 'Text',
      'path'                   => 'Text',
      'parent_ref'             => 'ForeignKey',
      'code'                   => 'Text',
      'classification'         => 'Text',
      'formule'                => 'Text',
      'formule_indexed'        => 'Text',
      'cristal_system'         => 'Text',
      'unit_class_ref'         => 'Number',
      'unit_class_indexed'     => 'Text',
      'unit_division_ref'      => 'Number',
      'unit_division_indexed'  => 'Text',
      'unit_family_ref'        => 'Number',
      'unit_family_indexed'    => 'Text',
      'unit_group_ref'         => 'Number',
      'unit_group_indexed'     => 'Text',
      'unit_variety_ref'       => 'Number',
      'unit_variety_indexed'   => 'Text',
    );
  }
}