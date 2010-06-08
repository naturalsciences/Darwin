<?php

/**
 * CollectionMaintenance form base class.
 *
 * @method CollectionMaintenance getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCollectionMaintenanceForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'record_id'              => new sfWidgetFormInputText(),
      'referenced_relation'    => new sfWidgetFormTextarea(),
      'people_ref'             => new sfWidgetFormInputText(),
      'category'               => new sfWidgetFormTextarea(),
      'action_observation'     => new sfWidgetFormTextarea(),
      'description'            => new sfWidgetFormTextarea(),
      'description_ts'         => new sfWidgetFormTextarea(),
      'modification_date_time' => new sfWidgetFormTextarea(),
      'modification_date_mask' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'record_id'              => new sfValidatorInteger(),
      'referenced_relation'    => new sfValidatorString(),
      'people_ref'             => new sfValidatorInteger(),
      'category'               => new sfValidatorString(array('required' => false)),
      'action_observation'     => new sfValidatorString(),
      'description'            => new sfValidatorString(array('required' => false)),
      'description_ts'         => new sfValidatorString(array('required' => false)),
      'modification_date_time' => new sfValidatorString(),
      'modification_date_mask' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collection_maintenance[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionMaintenance';
  }

}
