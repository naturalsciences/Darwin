<?php

/**
 * CollectionMaintenance filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCollectionMaintenanceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'record_id'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'referenced_relation'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'people_ref'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'action_observation'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description'            => new sfWidgetFormFilterInput(),
      'description_ts'         => new sfWidgetFormFilterInput(),
      'modification_date_time' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'modification_date_mask' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'record_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'referenced_relation'    => new sfValidatorPass(array('required' => false)),
      'people_ref'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'               => new sfValidatorPass(array('required' => false)),
      'action_observation'     => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'description_ts'         => new sfValidatorPass(array('required' => false)),
      'modification_date_time' => new sfValidatorPass(array('required' => false)),
      'modification_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('collection_maintenance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionMaintenance';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'record_id'              => 'Number',
      'referenced_relation'    => 'Text',
      'people_ref'             => 'Number',
      'category'               => 'Text',
      'action_observation'     => 'Text',
      'description'            => 'Text',
      'description_ts'         => 'Text',
      'modification_date_time' => 'Text',
      'modification_date_mask' => 'Number',
    );
  }
}
