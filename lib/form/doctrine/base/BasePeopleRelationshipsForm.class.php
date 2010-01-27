<?php

/**
 * PeopleRelationships form base class.
 *
 * @method PeopleRelationships getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePeopleRelationshipsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'relationship_type'       => new sfWidgetFormTextarea(),
      'person_1_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People1'), 'add_empty' => false)),
      'person_2_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People2'), 'add_empty' => false)),
      'path'                    => new sfWidgetFormTextarea(),
      'person_user_role'        => new sfWidgetFormTextarea(),
      'activity_date_from'      => new sfWidgetFormTextarea(),
      'activity_date_from_mask' => new sfWidgetFormInputText(),
      'activity_date_to'        => new sfWidgetFormTextarea(),
      'activity_date_to_mask'   => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'relationship_type'       => new sfValidatorString(array('required' => false)),
      'person_1_ref'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People1'))),
      'person_2_ref'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People2'))),
      'path'                    => new sfValidatorString(array('required' => false)),
      'person_user_role'        => new sfValidatorString(array('required' => false)),
      'activity_date_from'      => new sfValidatorString(array('required' => false)),
      'activity_date_from_mask' => new sfValidatorInteger(array('required' => false)),
      'activity_date_to'        => new sfValidatorString(array('required' => false)),
      'activity_date_to_mask'   => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_relationships[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleRelationships';
  }

}
