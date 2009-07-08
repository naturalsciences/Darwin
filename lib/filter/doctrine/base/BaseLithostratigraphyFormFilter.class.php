<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Lithostratigraphy filter form base class.
 *
 * @package    filters
 * @subpackage Lithostratigraphy *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseLithostratigraphyFormFilter extends BaseFormFilterDoctrine
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
      'parent_ref'             => new sfWidgetFormDoctrineChoice(array('model' => 'Lithostratigraphy', 'add_empty' => true)),
      'group_ref'              => new sfWidgetFormFilterInput(),
      'group_indexed'          => new sfWidgetFormFilterInput(),
      'formation_ref'          => new sfWidgetFormFilterInput(),
      'formation_indexed'      => new sfWidgetFormFilterInput(),
      'member_ref'             => new sfWidgetFormFilterInput(),
      'member_indexed'         => new sfWidgetFormFilterInput(),
      'layer_ref'              => new sfWidgetFormFilterInput(),
      'layer_indexed'          => new sfWidgetFormFilterInput(),
      'sub_level_1_ref'        => new sfWidgetFormFilterInput(),
      'sub_level_1_indexed'    => new sfWidgetFormFilterInput(),
      'sub_level_2_ref'        => new sfWidgetFormFilterInput(),
      'sub_level_2_indexed'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                   => new sfValidatorPass(array('required' => false)),
      'name_indexed'           => new sfValidatorPass(array('required' => false)),
      'description_year'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'description_year_compl' => new sfValidatorPass(array('required' => false)),
      'level_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'                 => new sfValidatorPass(array('required' => false)),
      'path'                   => new sfValidatorPass(array('required' => false)),
      'parent_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Lithostratigraphy', 'column' => 'id')),
      'group_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_indexed'          => new sfValidatorPass(array('required' => false)),
      'formation_ref'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'formation_indexed'      => new sfValidatorPass(array('required' => false)),
      'member_ref'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'member_indexed'         => new sfValidatorPass(array('required' => false)),
      'layer_ref'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'layer_indexed'          => new sfValidatorPass(array('required' => false)),
      'sub_level_1_ref'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_1_indexed'    => new sfValidatorPass(array('required' => false)),
      'sub_level_2_ref'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_2_indexed'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lithostratigraphy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithostratigraphy';
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
      'group_ref'              => 'Number',
      'group_indexed'          => 'Text',
      'formation_ref'          => 'Number',
      'formation_indexed'      => 'Text',
      'member_ref'             => 'Number',
      'member_indexed'         => 'Text',
      'layer_ref'              => 'Number',
      'layer_indexed'          => 'Text',
      'sub_level_1_ref'        => 'Number',
      'sub_level_1_indexed'    => 'Text',
      'sub_level_2_ref'        => 'Number',
      'sub_level_2_indexed'    => 'Text',
    );
  }
}