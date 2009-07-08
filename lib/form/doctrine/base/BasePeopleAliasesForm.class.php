<?php

/**
 * PeopleAliases form base class.
 *
 * @package    form
 * @subpackage people_aliases
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleAliasesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'table_name'     => new sfWidgetFormTextarea(),
      'record_id'      => new sfWidgetFormInput(),
      'person_ref'     => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'collection_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => false)),
      'person_name'    => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => 'PeopleAliases', 'column' => 'id', 'required' => false)),
      'table_name'     => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'      => new sfValidatorInteger(),
      'person_ref'     => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'collection_ref' => new sfValidatorDoctrineChoice(array('model' => 'Collections')),
      'person_name'    => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('people_aliases[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleAliases';
  }

}
