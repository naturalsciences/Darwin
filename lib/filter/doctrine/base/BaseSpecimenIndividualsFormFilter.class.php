<?php

/**
 * SpecimenIndividuals filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecimenIndividualsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_ref'                   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Specimens'), 'add_empty' => true)),
      'type'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type_group'                     => new sfWidgetFormFilterInput(),
      'type_search'                    => new sfWidgetFormFilterInput(),
      'sex'                            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'stage'                          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'state'                          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'social_status'                  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'rock_form'                      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'specimen_individuals_count_min' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'specimen_individuals_count_max' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'specimen_ref'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Specimens'), 'column' => 'id')),
      'type'                           => new sfValidatorPass(array('required' => false)),
      'type_group'                     => new sfValidatorPass(array('required' => false)),
      'type_search'                    => new sfValidatorPass(array('required' => false)),
      'sex'                            => new sfValidatorPass(array('required' => false)),
      'stage'                          => new sfValidatorPass(array('required' => false)),
      'state'                          => new sfValidatorPass(array('required' => false)),
      'social_status'                  => new sfValidatorPass(array('required' => false)),
      'rock_form'                      => new sfValidatorPass(array('required' => false)),
      'specimen_individuals_count_min' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'specimen_individuals_count_max' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('specimen_individuals_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenIndividuals';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'specimen_ref'                   => 'ForeignKey',
      'type'                           => 'Text',
      'type_group'                     => 'Text',
      'type_search'                    => 'Text',
      'sex'                            => 'Text',
      'stage'                          => 'Text',
      'state'                          => 'Text',
      'social_status'                  => 'Text',
      'rock_form'                      => 'Text',
      'specimen_individuals_count_min' => 'Number',
      'specimen_individuals_count_max' => 'Number',
    );
  }
}
