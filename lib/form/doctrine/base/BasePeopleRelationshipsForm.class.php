<?php

/**
 * PeopleRelationships form base class.
 *
 * @package    form
 * @subpackage people_relationships
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleRelationshipsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'relationship_type' => new sfWidgetFormTextarea(),
      'person_1_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'person_2_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'person_title'      => new sfWidgetFormTextarea(),
      'path'              => new sfWidgetFormTextarea(),
      'organization_unit' => new sfWidgetFormTextarea(),
      'person_user_role'  => new sfWidgetFormTextarea(),
      'activity_period'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'PeopleRelationships', 'column' => 'id', 'required' => false)),
      'relationship_type' => new sfValidatorString(array('max_length' => 2147483647)),
      'person_1_ref'      => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'person_2_ref'      => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'person_title'      => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'path'              => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'organization_unit' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'person_user_role'  => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'activity_period'   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_relationships[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleRelationships';
  }

}
