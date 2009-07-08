<?php

/**
 * SpecimensAccompanying form base class.
 *
 * @package    form
 * @subpackage specimens_accompanying
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSpecimensAccompanyingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'type'         => new sfWidgetFormTextarea(),
      'specimen_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => false)),
      'taxon_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => false)),
      'mineral_ref'  => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => false)),
      'form'         => new sfWidgetFormTextarea(),
      'quantity'     => new sfWidgetFormInput(),
      'unit'         => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'SpecimensAccompanying', 'column' => 'id', 'required' => false)),
      'type'         => new sfValidatorString(array('max_length' => 2147483647)),
      'specimen_ref' => new sfValidatorDoctrineChoice(array('model' => 'Specimens')),
      'taxon_ref'    => new sfValidatorDoctrineChoice(array('model' => 'Taxonomy')),
      'mineral_ref'  => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy')),
      'form'         => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'quantity'     => new sfValidatorNumber(array('required' => false)),
      'unit'         => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('specimens_accompanying[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimensAccompanying';
  }

}
