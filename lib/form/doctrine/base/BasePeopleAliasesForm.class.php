<?php

/**
 * PeopleAliases form base class.
 *
 * @method PeopleAliases getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePeopleAliasesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInputText(),
      'person_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'add_empty' => false)),
      'collection_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => false)),
      'person_name'         => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'person_ref'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People'))),
      'collection_ref'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'required' => false)),
      'person_name'         => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('people_aliases[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleAliases';
  }

}
