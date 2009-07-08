<?php

/**
 * Soortenregister form base class.
 *
 * @package    form
 * @subpackage soortenregister
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSoortenregisterForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'taxa_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => false)),
      'gtu_ref'     => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => false)),
      'habitat_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Habitats', 'add_empty' => false)),
      'date_from'   => new sfWidgetFormDate(),
      'date_to'     => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'Soortenregister', 'column' => 'id', 'required' => false)),
      'taxa_ref'    => new sfValidatorDoctrineChoice(array('model' => 'Taxonomy')),
      'gtu_ref'     => new sfValidatorDoctrineChoice(array('model' => 'Gtu')),
      'habitat_ref' => new sfValidatorDoctrineChoice(array('model' => 'Habitats')),
      'date_from'   => new sfValidatorDate(array('required' => false)),
      'date_to'     => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soortenregister[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Soortenregister';
  }

}
