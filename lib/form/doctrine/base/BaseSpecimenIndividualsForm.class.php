<?php

/**
 * SpecimenIndividuals form base class.
 *
 * @package    form
 * @subpackage specimen_individuals
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSpecimenIndividualsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'specimen_ref'                   => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => false)),
      'type'                           => new sfWidgetFormTextarea(),
      'type_group'                     => new sfWidgetFormTextarea(),
      'type_search'                    => new sfWidgetFormTextarea(),
      'sex'                            => new sfWidgetFormTextarea(),
      'stage'                          => new sfWidgetFormTextarea(),
      'stat'                           => new sfWidgetFormTextarea(),
      'social_status'                  => new sfWidgetFormTextarea(),
      'rock_form'                      => new sfWidgetFormTextarea(),
      'specimen_individuals_count_min' => new sfWidgetFormInput(),
      'specimen_individuals_count_max' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorDoctrineChoice(array('model' => 'SpecimenIndividuals', 'column' => 'id', 'required' => false)),
      'specimen_ref'                   => new sfValidatorDoctrineChoice(array('model' => 'Specimens')),
      'type'                           => new sfValidatorString(array('max_length' => 2147483647)),
      'type_group'                     => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'type_search'                    => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'sex'                            => new sfValidatorString(array('max_length' => 2147483647)),
      'stage'                          => new sfValidatorString(array('max_length' => 2147483647)),
      'stat'                           => new sfValidatorString(array('max_length' => 2147483647)),
      'social_status'                  => new sfValidatorString(array('max_length' => 2147483647)),
      'rock_form'                      => new sfValidatorString(array('max_length' => 2147483647)),
      'specimen_individuals_count_min' => new sfValidatorInteger(),
      'specimen_individuals_count_max' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('specimen_individuals[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenIndividuals';
  }

}
