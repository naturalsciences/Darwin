<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * SpecimenPartsInsurances filter form base class.
 *
 * @package    filters
 * @subpackage SpecimenPartsInsurances *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSpecimenPartsInsurancesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_part_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'SpecimenParts', 'add_empty' => true)),
      'insurance_year'    => new sfWidgetFormFilterInput(),
      'insurance_value'   => new sfWidgetFormFilterInput(),
      'insurer_ref'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'specimen_part_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'SpecimenParts', 'column' => 'id')),
      'insurance_year'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'insurance_value'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'insurer_ref'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts_insurances_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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