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
      'specimen_individual_ref' => new sfWidgetFormInput(),
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
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => 'SpecimenParts', 'column' => 'id', 'required' => false)),
      'specimen_individual_ref' => new sfValidatorInteger(),
      'specimen_part'           => new sfValidatorString(array('max_length' => 2147483647)),
      'complete'                => new sfValidatorBoolean(),
      'building'                => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'floor'                   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'room'                    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'row'                     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'shelf'                   => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'container'               => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'sub_container'           => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'container_type'          => new sfValidatorString(array('max_length' => 2147483647)),
      'sub_container_type'      => new sfValidatorString(array('max_length' => 2147483647)),
      'storage'                 => new sfValidatorString(array('max_length' => 2147483647)),
      'surnumerary'             => new sfValidatorBoolean(),
      'specimen_status'         => new sfValidatorString(array('max_length' => 2147483647)),
      'specimen_part_count_min' => new sfValidatorInteger(),
      'specimen_part_count_max' => new sfValidatorInteger(),
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
