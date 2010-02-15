<?php

/**
 * Collections form base class.
 *
 * @method Collections getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCollectionsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'collection_type'          => new sfWidgetFormChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'))),
      'code'                     => new sfWidgetFormTextarea(),
      'name'                     => new sfWidgetFormTextarea(),
      'institution_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Institution'), 'add_empty' => false)),
      'main_manager_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'), 'add_empty' => false)),
      'parent_ref'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'path'                     => new sfWidgetFormTextarea(),
      'code_auto_increment'      => new sfWidgetFormInputCheckbox(),
      'code_part_code_auto_copy' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'collection_type'          => new sfValidatorChoice(array('choices' => array(0 => 'mix', 1 => 'observation', 2 => 'physical'), 'required' => false)),
      'code'                     => new sfValidatorString(),
      'name'                     => new sfValidatorString(),
      'institution_ref'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Institution'))),
      'main_manager_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Manager'))),
      'parent_ref'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'path'                     => new sfValidatorString(array('required' => false)),
      'code_auto_increment'      => new sfValidatorBoolean(array('required' => false)),
      'code_part_code_auto_copy' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collections[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collections';
  }

}
