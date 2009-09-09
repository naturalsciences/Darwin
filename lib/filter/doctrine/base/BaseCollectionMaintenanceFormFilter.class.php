<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CollectionMaintenance filter form base class.
 *
 * @package    filters
 * @subpackage CollectionMaintenance *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCollectionMaintenanceFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'             => new sfWidgetFormFilterInput(),
      'people_ref'             => new sfWidgetFormFilterInput(),
      'category'               => new sfWidgetFormFilterInput(),
      'action_observation'     => new sfWidgetFormFilterInput(),
      'description'            => new sfWidgetFormFilterInput(),
      'description_ts'         => new sfWidgetFormFilterInput(),
      'language_full_text'     => new sfWidgetFormFilterInput(),
      'modification_date_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'modification_date_mask' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'             => new sfValidatorPass(array('required' => false)),
      'people_ref'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'               => new sfValidatorPass(array('required' => false)),
      'action_observation'     => new sfValidatorPass(array('required' => false)),
      'description'            => new sfValidatorPass(array('required' => false)),
      'description_ts'         => new sfValidatorPass(array('required' => false)),
      'language_full_text'     => new sfValidatorPass(array('required' => false)),
      'modification_date_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'modification_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('collection_maintenance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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
      'table_name'             => 'Text',
      'people_ref'             => 'Number',
      'category'               => 'Text',
      'action_observation'     => 'Text',
      'description'            => 'Text',
      'description_ts'         => 'Text',
      'language_full_text'     => 'Text',
      'modification_date_time' => 'Date',
      'modification_date_mask' => 'Number',
    );
  }
}