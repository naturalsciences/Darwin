<?php

/**
 * Mineralogy filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMineralogyFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name_indexed'          => new sfWidgetFormFilterInput(),
      'level_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => true)),
      'status'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                  => new sfWidgetFormFilterInput(),
      'parent_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'code'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'classification'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'formule'               => new sfWidgetFormFilterInput(),
      'formule_indexed'       => new sfWidgetFormFilterInput(),
      'cristal_system'        => new sfWidgetFormFilterInput(),
      'unit_class_ref'        => new sfWidgetFormFilterInput(),
      'unit_class_indexed'    => new sfWidgetFormFilterInput(),
      'unit_division_ref'     => new sfWidgetFormFilterInput(),
      'unit_division_indexed' => new sfWidgetFormFilterInput(),
      'unit_family_ref'       => new sfWidgetFormFilterInput(),
      'unit_family_indexed'   => new sfWidgetFormFilterInput(),
      'unit_group_ref'        => new sfWidgetFormFilterInput(),
      'unit_group_indexed'    => new sfWidgetFormFilterInput(),
      'unit_variety_ref'      => new sfWidgetFormFilterInput(),
      'unit_variety_indexed'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                  => new sfValidatorPass(array('required' => false)),
      'name_indexed'          => new sfValidatorPass(array('required' => false)),
      'level_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Level'), 'column' => 'id')),
      'status'                => new sfValidatorPass(array('required' => false)),
      'path'                  => new sfValidatorPass(array('required' => false)),
      'parent_ref'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'code'                  => new sfValidatorPass(array('required' => false)),
      'classification'        => new sfValidatorPass(array('required' => false)),
      'formule'               => new sfValidatorPass(array('required' => false)),
      'formule_indexed'       => new sfValidatorPass(array('required' => false)),
      'cristal_system'        => new sfValidatorPass(array('required' => false)),
      'unit_class_ref'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_class_indexed'    => new sfValidatorPass(array('required' => false)),
      'unit_division_ref'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_division_indexed' => new sfValidatorPass(array('required' => false)),
      'unit_family_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_family_indexed'   => new sfValidatorPass(array('required' => false)),
      'unit_group_ref'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_group_indexed'    => new sfValidatorPass(array('required' => false)),
      'unit_variety_ref'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'unit_variety_indexed'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('mineralogy_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mineralogy';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'name'                  => 'Text',
      'name_indexed'          => 'Text',
      'level_ref'             => 'ForeignKey',
      'status'                => 'Text',
      'path'                  => 'Text',
      'parent_ref'            => 'ForeignKey',
      'code'                  => 'Text',
      'classification'        => 'Text',
      'formule'               => 'Text',
      'formule_indexed'       => 'Text',
      'cristal_system'        => 'Text',
      'unit_class_ref'        => 'Number',
      'unit_class_indexed'    => 'Text',
      'unit_division_ref'     => 'Number',
      'unit_division_indexed' => 'Text',
      'unit_family_ref'       => 'Number',
      'unit_family_indexed'   => 'Text',
      'unit_group_ref'        => 'Number',
      'unit_group_indexed'    => 'Text',
      'unit_variety_ref'      => 'Number',
      'unit_variety_indexed'  => 'Text',
    );
  }
}
