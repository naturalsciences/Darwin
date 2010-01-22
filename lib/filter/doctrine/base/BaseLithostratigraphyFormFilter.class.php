<?php

/**
 * Lithostratigraphy filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLithostratigraphyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name_indexed'        => new sfWidgetFormFilterInput(),
      'level_ref'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'parent_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'group_ref'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'group_indexed'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'formation_ref'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'formation_indexed'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'member_ref'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'member_indexed'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'layer_ref'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'layer_indexed'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_level_1_ref'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_level_1_indexed' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_level_2_ref'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_level_2_indexed' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'name'                => new sfValidatorPass(array('required' => false)),
      'name_indexed'        => new sfValidatorPass(array('required' => false)),
      'level_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'status'              => new sfValidatorPass(array('required' => false)),
      'path'                => new sfValidatorPass(array('required' => false)),
      'parent_ref'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'group_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_indexed'       => new sfValidatorPass(array('required' => false)),
      'formation_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'formation_indexed'   => new sfValidatorPass(array('required' => false)),
      'member_ref'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'member_indexed'      => new sfValidatorPass(array('required' => false)),
      'layer_ref'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'layer_indexed'       => new sfValidatorPass(array('required' => false)),
      'sub_level_1_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_1_indexed' => new sfValidatorPass(array('required' => false)),
      'sub_level_2_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sub_level_2_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lithostratigraphy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithostratigraphy';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'name'                => 'Text',
      'name_indexed'        => 'Text',
      'level_ref'           => 'Number',
      'status'              => 'Text',
      'path'                => 'Text',
      'parent_ref'          => 'ForeignKey',
      'group_ref'           => 'Number',
      'group_indexed'       => 'Text',
      'formation_ref'       => 'Number',
      'formation_indexed'   => 'Text',
      'member_ref'          => 'Number',
      'member_indexed'      => 'Text',
      'layer_ref'           => 'Number',
      'layer_indexed'       => 'Text',
      'sub_level_1_ref'     => 'Number',
      'sub_level_1_indexed' => 'Text',
      'sub_level_2_ref'     => 'Number',
      'sub_level_2_indexed' => 'Text',
    );
  }
}
