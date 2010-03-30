<?php

/**
 * Specimens filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecimensFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => true)),
      'expedition_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Expeditions'), 'add_empty' => true)),
      'gtu_ref'               => new sfWidgetFormFilterInput(),
      'taxon_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'add_empty' => true)),
      'litho_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lithostratigraphy'), 'add_empty' => true)),
      'chrono_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Chronostratigraphy'), 'add_empty' => true)),
      'lithology_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lithology'), 'add_empty' => true)),
      'mineral_ref'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Mineralogy'), 'add_empty' => true)),
      'host_taxon_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HostTaxon'), 'add_empty' => true)),
      'host_specimen_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HostSpecimen'), 'add_empty' => true)),
      'host_relationship'     => new sfWidgetFormFilterInput(),
      'acquisition_category'  => new sfWidgetFormFilterInput(),
      'acquisition_date_mask' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'acquisition_date'      => new sfWidgetFormFilterInput(),
      'collecting_method'     => new sfWidgetFormFilterInput(),
      'collecting_tool'       => new sfWidgetFormFilterInput(),
      'specimen_count_min'    => new sfWidgetFormFilterInput(),
      'specimen_count_max'    => new sfWidgetFormFilterInput(),
      'station_visible'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'multimedia_visible'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'ig_ref'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Igs'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'collection_ref'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Collections'), 'column' => 'id')),
      'expedition_ref'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Expeditions'), 'column' => 'id')),
      'gtu_ref'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'taxon_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Taxonomy'), 'column' => 'id')),
      'litho_ref'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Lithostratigraphy'), 'column' => 'id')),
      'chrono_ref'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Chronostratigraphy'), 'column' => 'id')),
      'lithology_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Lithology'), 'column' => 'id')),
      'mineral_ref'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Mineralogy'), 'column' => 'id')),
      'host_taxon_ref'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('HostTaxon'), 'column' => 'id')),
      'host_specimen_ref'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('HostSpecimen'), 'column' => 'id')),
      'host_relationship'     => new sfValidatorPass(array('required' => false)),
      'acquisition_category'  => new sfValidatorPass(array('required' => false)),
      'acquisition_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'acquisition_date'      => new sfValidatorPass(array('required' => false)),
      'collecting_method'     => new sfValidatorPass(array('required' => false)),
      'collecting_tool'       => new sfValidatorPass(array('required' => false)),
      'specimen_count_min'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'specimen_count_max'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'station_visible'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'multimedia_visible'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'ig_ref'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Igs'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('specimens_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specimens';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'collection_ref'        => 'ForeignKey',
      'expedition_ref'        => 'ForeignKey',
      'gtu_ref'               => 'Number',
      'taxon_ref'             => 'ForeignKey',
      'litho_ref'             => 'ForeignKey',
      'chrono_ref'            => 'ForeignKey',
      'lithology_ref'         => 'ForeignKey',
      'mineral_ref'           => 'ForeignKey',
      'host_taxon_ref'        => 'ForeignKey',
      'host_specimen_ref'     => 'ForeignKey',
      'host_relationship'     => 'Text',
      'acquisition_category'  => 'Text',
      'acquisition_date_mask' => 'Number',
      'acquisition_date'      => 'Text',
      'collecting_method'     => 'Text',
      'collecting_tool'       => 'Text',
      'specimen_count_min'    => 'Number',
      'specimen_count_max'    => 'Number',
      'station_visible'       => 'Boolean',
      'multimedia_visible'    => 'Boolean',
      'ig_ref'                => 'ForeignKey',
    );
  }
}
