<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * SpecimenIndividuals filter form base class.
 *
 * @package    filters
 * @subpackage SpecimenIndividuals *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSpecimenIndividualsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_ref'                   => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => true)),
      'type'                           => new sfWidgetFormFilterInput(),
      'type_group'                     => new sfWidgetFormFilterInput(),
      'type_search'                    => new sfWidgetFormFilterInput(),
      'sex'                            => new sfWidgetFormFilterInput(),
      'stage'                          => new sfWidgetFormFilterInput(),
      'stat'                           => new sfWidgetFormFilterInput(),
      'social_status'                  => new sfWidgetFormFilterInput(),
      'rock_form'                      => new sfWidgetFormFilterInput(),
      'specimen_individuals_count_min' => new sfWidgetFormFilterInput(),
      'specimen_individuals_count_max' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'specimen_ref'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Specimens', 'column' => 'id')),
      'type'                           => new sfValidatorPass(array('required' => false)),
      'type_group'                     => new sfValidatorPass(array('required' => false)),
      'type_search'                    => new sfValidatorPass(array('required' => false)),
      'sex'                            => new sfValidatorPass(array('required' => false)),
      'stage'                          => new sfValidatorPass(array('required' => false)),
      'stat'                           => new sfValidatorPass(array('required' => false)),
      'social_status'                  => new sfValidatorPass(array('required' => false)),
      'rock_form'                      => new sfValidatorPass(array('required' => false)),
      'specimen_individuals_count_min' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'specimen_individuals_count_max' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('specimen_individuals_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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
      'stat'                           => 'Text',
      'social_status'                  => 'Text',
      'rock_form'                      => 'Text',
      'specimen_individuals_count_min' => 'Number',
      'specimen_individuals_count_max' => 'Number',
    );
  }
}