<?php

/**
 * PeopleRelationships filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePeopleRelationshipsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'relationship_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'person_1_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People1'), 'add_empty' => true)),
      'person_2_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People2'), 'add_empty' => true)),
      'person_title'      => new sfWidgetFormFilterInput(),
      'path'              => new sfWidgetFormFilterInput(),
      'organization_unit' => new sfWidgetFormFilterInput(),
      'person_user_role'  => new sfWidgetFormFilterInput(),
      'activity_period'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'relationship_type' => new sfValidatorPass(array('required' => false)),
      'person_1_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('People1'), 'column' => 'id')),
      'person_2_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('People2'), 'column' => 'id')),
      'person_title'      => new sfValidatorPass(array('required' => false)),
      'path'              => new sfValidatorPass(array('required' => false)),
      'organization_unit' => new sfValidatorPass(array('required' => false)),
      'person_user_role'  => new sfValidatorPass(array('required' => false)),
      'activity_period'   => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_relationships_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleRelationships';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'relationship_type' => 'Text',
      'person_1_ref'      => 'ForeignKey',
      'person_2_ref'      => 'ForeignKey',
      'person_title'      => 'Text',
      'path'              => 'Text',
      'organization_unit' => 'Text',
      'person_user_role'  => 'Text',
      'activity_period'   => 'Text',
    );
  }
}
