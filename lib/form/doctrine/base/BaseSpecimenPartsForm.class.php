<?php

/**
 * SpecimenParts form base class.
 *
 * @package    form
 * @subpackage specimen_parts
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSpecimenPartsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'specimen_individual_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'SpecimenIndividuals', 'add_empty' => false)),
      'specimen_part'           => new sfWidgetFormTextarea(),
      'complete'                => new sfWidgetFormInputCheckbox(),
      'building'                => new sfWidgetFormTextarea(),
      'floor'                   => new sfWidgetFormTextarea(),
      'room'                    => new sfWidgetFormTextarea(),
      'row'                     => new sfWidgetFormTextarea(),
      'shelf'                   => new sfWidgetFormTextarea(),
      'container'               => new sfWidgetFormTextarea(),
      'sub_container'           => new sfWidgetFormTextarea(),
      'container_type'          => new sfWidgetFormTextarea(),
      'sub_container_type'      => new sfWidgetFormTextarea(),
      'storage'                 => new sfWidgetFormTextarea(),
      'surnumerary'             => new sfWidgetFormInputCheckbox(),
      'specimen_status'         => new sfWidgetFormTextarea(),
      'specimen_part_count_min' => new sfWidgetFormInput(),
      'specimen_part_count_max' => new sfWidgetFormInput(),
      'category'                => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => 'SpecimenParts', 'column' => 'id', 'required' => false)),
      'specimen_individual_ref' => new sfValidatorDoctrineChoice(array('model' => 'SpecimenIndividuals')),
      'specimen_part'           => new sfValidatorString(),
      'complete'                => new sfValidatorBoolean(),
      'building'                => new sfValidatorString(array('required' => false)),
      'floor'                   => new sfValidatorString(array('required' => false)),
      'room'                    => new sfValidatorString(array('required' => false)),
      'row'                     => new sfValidatorString(array('required' => false)),
      'shelf'                   => new sfValidatorString(array('required' => false)),
      'container'               => new sfValidatorString(array('required' => false)),
      'sub_container'           => new sfValidatorString(array('required' => false)),
      'container_type'          => new sfValidatorString(),
      'sub_container_type'      => new sfValidatorString(),
      'storage'                 => new sfValidatorString(),
      'surnumerary'             => new sfValidatorBoolean(),
      'specimen_status'         => new sfValidatorString(),
      'specimen_part_count_min' => new sfValidatorInteger(),
      'specimen_part_count_max' => new sfValidatorInteger(),
      'category'                => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenParts';
  }

}
