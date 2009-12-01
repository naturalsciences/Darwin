<?php

/**
 * SpecimenPartsInsurances form base class.
 *
 * @method SpecimenPartsInsurances getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseSpecimenPartsInsurancesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'specimen_part_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SpecimenParts'), 'add_empty' => false)),
      'insurance_year'    => new sfWidgetFormInputText(),
      'insurance_value'   => new sfWidgetFormInputText(),
      'insurer_ref'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'specimen_part_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('SpecimenParts'))),
      'insurance_year'    => new sfValidatorInteger(array('required' => false)),
      'insurance_value'   => new sfValidatorInteger(),
      'insurer_ref'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts_insurances[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenPartsInsurances';
  }

}
