<?php

/**
 * SpecimenPartsInsurances form base class.
 *
 * @package    form
 * @subpackage specimen_parts_insurances
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSpecimenPartsInsurancesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'specimen_part_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'SpecimenParts', 'add_empty' => false)),
      'insurance_year'    => new sfWidgetFormInput(),
      'insurance_value'   => new sfWidgetFormInput(),
      'insurer_ref'       => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'SpecimenPartsInsurances', 'column' => 'id', 'required' => false)),
      'specimen_part_ref' => new sfValidatorDoctrineChoice(array('model' => 'SpecimenParts')),
      'insurance_year'    => new sfValidatorInteger(array('required' => false)),
      'insurance_value'   => new sfValidatorInteger(),
      'insurer_ref'       => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts_insurances[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenPartsInsurances';
  }

}
