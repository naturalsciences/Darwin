<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PeopleAliases filter form base class.
 *
 * @package    filters
 * @subpackage PeopleAliases *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePeopleAliasesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation' => new sfWidgetFormFilterInput(),
      'record_id'           => new sfWidgetFormFilterInput(),
      'person_ref'          => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
      'collection_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'person_name'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'referenced_relation' => new sfValidatorPass(array('required' => false)),
      'record_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'person_ref'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
      'collection_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Collections', 'column' => 'id')),
      'person_name'         => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_aliases_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleAliases';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'referenced_relation' => 'Text',
      'record_id'           => 'Number',
      'person_ref'          => 'ForeignKey',
      'collection_ref'      => 'ForeignKey',
      'person_name'         => 'Text',
    );
  }
}