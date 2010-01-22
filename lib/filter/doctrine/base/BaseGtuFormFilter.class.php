<?php

/**
 * Gtu filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGtuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'parent_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'gtu_from_date_mask' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gtu_from_date'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gtu_to_date_mask'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gtu_to_date'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'code'               => new sfValidatorPass(array('required' => false)),
      'parent_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'gtu_from_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gtu_from_date'      => new sfValidatorPass(array('required' => false)),
      'gtu_to_date_mask'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gtu_to_date'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gtu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gtu';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'code'               => 'Text',
      'parent_ref'         => 'ForeignKey',
      'gtu_from_date_mask' => 'Number',
      'gtu_from_date'      => 'Text',
      'gtu_to_date_mask'   => 'Number',
      'gtu_to_date'        => 'Text',
    );
  }
}
