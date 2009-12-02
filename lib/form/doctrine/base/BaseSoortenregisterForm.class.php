<?php

/**
 * Soortenregister form base class.
 *
 * @method Soortenregister getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSoortenregisterForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'taxa_ref'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'add_empty' => false)),
      'gtu_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'add_empty' => false)),
      'habitat_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Habitats'), 'add_empty' => false)),
      'date_from'   => new sfWidgetFormDate(),
      'date_to'     => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'taxa_ref'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'required' => false)),
      'gtu_ref'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'required' => false)),
      'habitat_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Habitats'), 'required' => false)),
      'date_from'   => new sfValidatorDate(array('required' => false)),
      'date_to'     => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soortenregister[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Soortenregister';
  }

}
