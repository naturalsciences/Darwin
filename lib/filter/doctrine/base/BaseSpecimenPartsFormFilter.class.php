<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * SpecimenParts filter form base class.
 *
 * @package    filters
 * @subpackage SpecimenParts *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSpecimenPartsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_individual_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'SpecimenIndividuals', 'add_empty' => true)),
      'specimen_part'           => new sfWidgetFormFilterInput(),
      'complete'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'building'                => new sfWidgetFormFilterInput(),
      'floor'                   => new sfWidgetFormFilterInput(),
      'room'                    => new sfWidgetFormFilterInput(),
      'row'                     => new sfWidgetFormFilterInput(),
      'shelf'                   => new sfWidgetFormFilterInput(),
      'container'               => new sfWidgetFormFilterInput(),
      'sub_container'           => new sfWidgetFormFilterInput(),
      'container_type'          => new sfWidgetFormFilterInput(),
      'sub_container_type'      => new sfWidgetFormFilterInput(),
      'storage'                 => new sfWidgetFormFilterInput(),
      'surnumerary'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'specimen_status'         => new sfWidgetFormFilterInput(),
      'specimen_part_count_min' => new sfWidgetFormFilterInput(),
      'specimen_part_count_max' => new sfWidgetFormFilterInput(),
      'category'                => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'specimen_individual_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'SpecimenIndividuals', 'column' => 'id')),
      'specimen_part'           => new sfValidatorPass(array('required' => false)),
      'complete'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'building'                => new sfValidatorPass(array('required' => false)),
      'floor'                   => new sfValidatorPass(array('required' => false)),
      'room'                    => new sfValidatorPass(array('required' => false)),
      'row'                     => new sfValidatorPass(array('required' => false)),
      'shelf'                   => new sfValidatorPass(array('required' => false)),
      'container'               => new sfValidatorPass(array('required' => false)),
      'sub_container'           => new sfValidatorPass(array('required' => false)),
      'container_type'          => new sfValidatorPass(array('required' => false)),
      'sub_container_type'      => new sfValidatorPass(array('required' => false)),
      'storage'                 => new sfValidatorPass(array('required' => false)),
      'surnumerary'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'specimen_status'         => new sfValidatorPass(array('required' => false)),
      'specimen_part_count_min' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'specimen_part_count_max' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'category'                => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimen_parts_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SpecimenParts';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'specimen_individual_ref' => 'ForeignKey',
      'specimen_part'           => 'Text',
      'complete'                => 'Boolean',
      'building'                => 'Text',
      'floor'                   => 'Text',
      'room'                    => 'Text',
      'row'                     => 'Text',
      'shelf'                   => 'Text',
      'container'               => 'Text',
      'sub_container'           => 'Text',
      'container_type'          => 'Text',
      'sub_container_type'      => 'Text',
      'storage'                 => 'Text',
      'surnumerary'             => 'Boolean',
      'specimen_status'         => 'Text',
      'specimen_part_count_min' => 'Number',
      'specimen_part_count_max' => 'Number',
      'category'                => 'Text',
    );
  }
}