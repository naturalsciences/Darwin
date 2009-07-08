<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Specimens filter form base class.
 *
 * @package    filters
 * @subpackage Specimens *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseSpecimensFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'expedition_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Expeditions', 'add_empty' => true)),
      'gtu_ref'                  => new sfWidgetFormFilterInput(),
      'taxon_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => true)),
      'litho_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Lithostratigraphy', 'add_empty' => true)),
      'chrono_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Chronostratigraphy', 'add_empty' => true)),
      'lithology_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Lithology', 'add_empty' => true)),
      'mineral_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => true)),
      'identification_qual'      => new sfWidgetFormFilterInput(),
      'sp'                       => new sfWidgetFormFilterInput(),
      'identification_taxon_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => true)),
      'host_taxon_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => true)),
      'host_specimen_ref'        => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => true)),
      'host_relationship'        => new sfWidgetFormFilterInput(),
      'acquisition_category'     => new sfWidgetFormFilterInput(),
      'acquisition_date_mask'    => new sfWidgetFormFilterInput(),
      'acquisition_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'collecting_method'        => new sfWidgetFormFilterInput(),
      'collecting_tool'          => new sfWidgetFormFilterInput(),
      'specimen_count_min'       => new sfWidgetFormFilterInput(),
      'specimen_count_max'       => new sfWidgetFormFilterInput(),
      'station_visible'          => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'multimedia_visible'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'category'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'collection_ref'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Collections', 'column' => 'id')),
      'expedition_ref'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Expeditions', 'column' => 'id')),
      'gtu_ref'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'taxon_ref'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Taxonomy', 'column' => 'id')),
      'litho_ref'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Lithostratigraphy', 'column' => 'id')),
      'chrono_ref'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Chronostratigraphy', 'column' => 'id')),
      'lithology_ref'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Lithology', 'column' => 'id')),
      'mineral_ref'              => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Mineralogy', 'column' => 'id')),
      'identification_qual'      => new sfValidatorPass(array('required' => false)),
      'sp'                       => new sfValidatorPass(array('required' => false)),
      'identification_taxon_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Taxonomy', 'column' => 'id')),
      'host_taxon_ref'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Taxonomy', 'column' => 'id')),
      'host_specimen_ref'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Specimens', 'column' => 'id')),
      'host_relationship'        => new sfValidatorPass(array('required' => false)),
      'acquisition_category'     => new sfValidatorPass(array('required' => false)),
      'acquisition_date_mask'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'acquisition_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'collecting_method'        => new sfValidatorPass(array('required' => false)),
      'collecting_tool'          => new sfValidatorPass(array('required' => false)),
      'specimen_count_min'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'specimen_count_max'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'station_visible'          => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'multimedia_visible'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'category'                 => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimens_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specimens';
  }

  public function getFields()
  {
    return array(
      'id'                       => 'Number',
      'collection_ref'           => 'ForeignKey',
      'expedition_ref'           => 'ForeignKey',
      'gtu_ref'                  => 'Number',
      'taxon_ref'                => 'ForeignKey',
      'litho_ref'                => 'ForeignKey',
      'chrono_ref'               => 'ForeignKey',
      'lithology_ref'            => 'ForeignKey',
      'mineral_ref'              => 'ForeignKey',
      'identification_qual'      => 'Text',
      'sp'                       => 'Text',
      'identification_taxon_ref' => 'ForeignKey',
      'host_taxon_ref'           => 'ForeignKey',
      'host_specimen_ref'        => 'ForeignKey',
      'host_relationship'        => 'Text',
      'acquisition_category'     => 'Text',
      'acquisition_date_mask'    => 'Number',
      'acquisition_date'         => 'Date',
      'collecting_method'        => 'Text',
      'collecting_tool'          => 'Text',
      'specimen_count_min'       => 'Number',
      'specimen_count_max'       => 'Number',
      'station_visible'          => 'Boolean',
      'multimedia_visible'       => 'Boolean',
      'category'                 => 'Text',
    );
  }
}