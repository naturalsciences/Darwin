<?php

/**
 * SpecimenPartsInsurances filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecimenPartsInsurancesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_part_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('SpecimenParts'), 'add_empty' => true)),
      'insurance_year'    => new sfWidgetFormFilterInput(),
      'insurance_value'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'insurer_ref'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'specimen_part_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('SpecimenParts'), 'column' => 'id')),
      'insurance_year'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'insurance_value'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'insurer_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts_insurances_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenPartsInsurances';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'specimen_part_ref' => 'ForeignKey',
      'insurance_year'    => 'Number',
      'insurance_value'   => 'Number',
      'insurer_ref'       => 'Number',
    );
  }
}
