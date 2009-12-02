<?php

/**
 * SpecimensAccompanying form base class.
 *
 * @method SpecimensAccompanying getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecimensAccompanyingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'type'         => new sfWidgetFormTextarea(),
      'specimen_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specimens'), 'add_empty' => false)),
      'taxon_ref'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'add_empty' => false)),
      'mineral_ref'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Mineralogy'), 'add_empty' => false)),
      'form'         => new sfWidgetFormTextarea(),
      'quantity'     => new sfWidgetFormInputText(),
      'unit'         => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'type'         => new sfValidatorString(array('required' => false)),
      'specimen_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Specimens'))),
      'taxon_ref'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'required' => false)),
      'mineral_ref'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Mineralogy'), 'required' => false)),
      'form'         => new sfValidatorString(array('required' => false)),
      'quantity'     => new sfValidatorNumber(array('required' => false)),
      'unit'         => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimens_accompanying[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimensAccompanying';
  }

}
