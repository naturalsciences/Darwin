<?php

/**
 * CollectionMaintenance form base class.
 *
 * @package    form
 * @subpackage collection_maintenance
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCollectionMaintenanceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'table_name'             => new sfWidgetFormTextarea(),
      'people_ref'             => new sfWidgetFormInput(),
      'category'               => new sfWidgetFormTextarea(),
      'action_observation'     => new sfWidgetFormTextarea(),
      'description'            => new sfWidgetFormTextarea(),
      'description_ts'         => new sfWidgetFormTextarea(),
      'language_full_text'     => new sfWidgetFormTextarea(),
      'modification_date_time' => new sfWidgetFormDateTime(),
      'modification_date_mask' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'CollectionMaintenance', 'column' => 'id', 'required' => false)),
      'table_name'             => new sfValidatorString(array('max_length' => 2147483647)),
      'people_ref'             => new sfValidatorInteger(),
      'category'               => new sfValidatorString(array('max_length' => 2147483647)),
      'action_observation'     => new sfValidatorString(array('max_length' => 2147483647)),
      'description'            => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'description_ts'         => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'language_full_text'     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'modification_date_time' => new sfValidatorDateTime(),
      'modification_date_mask' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('collection_maintenance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionMaintenance';
  }

}
