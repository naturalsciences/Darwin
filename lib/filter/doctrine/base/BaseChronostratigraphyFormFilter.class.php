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
      'name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'name_indexed'  => new sfWidgetFormFilterInput(),
      'name_order_by' => new sfWidgetFormFilterInput(),
      'level_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => true)),
      'status'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'          => new sfWidgetFormFilterInput(),
      'parent_ref'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'lower_bound'   => new sfWidgetFormFilterInput(),
      'upper_bound'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'          => new sfValidatorPass(array('required' => false)),
      'name_indexed'  => new sfValidatorPass(array('required' => false)),
      'name_order_by' => new sfValidatorPass(array('required' => false)),
      'level_ref'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Level'), 'column' => 'id')),
      'status'        => new sfValidatorPass(array('required' => false)),
      'path'          => new sfValidatorPass(array('required' => false)),
      'parent_ref'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Parent'), 'column' => 'id')),
      'lower_bound'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'upper_bound'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
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
      'id'            => 'Number',
      'name'          => 'Text',
      'name_indexed'  => 'Text',
      'name_order_by' => 'Text',
      'level_ref'     => 'ForeignKey',
      'status'        => 'Text',
      'path'          => 'Text',
      'parent_ref'    => 'ForeignKey',
      'lower_bound'   => 'Number',
      'upper_bound'   => 'Number',
    );
  }
}
