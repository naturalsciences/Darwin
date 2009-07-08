<?php

/**
 * Specimens form base class.
 *
 * @package    form
 * @subpackage specimens
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseSpecimensForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'collection_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => false)),
      'expedition_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Expeditions', 'add_empty' => true)),
      'gtu_ref'                  => new sfWidgetFormInput(),
      'taxon_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => false)),
      'litho_ref'                => new sfWidgetFormDoctrineChoice(array('model' => 'Lithostratigraphy', 'add_empty' => false)),
      'chrono_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Chronostratigraphy', 'add_empty' => false)),
      'lithology_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Lithology', 'add_empty' => false)),
      'mineral_ref'              => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => false)),
      'identification_qual'      => new sfWidgetFormTextarea(),
      'sp'                       => new sfWidgetFormTextarea(),
      'identification_taxon_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => false)),
      'host_taxon_ref'           => new sfWidgetFormDoctrineChoice(array('model' => 'Taxonomy', 'add_empty' => false)),
      'host_specimen_ref'        => new sfWidgetFormDoctrineChoice(array('model' => 'Specimens', 'add_empty' => true)),
      'host_relationship'        => new sfWidgetFormTextarea(),
      'acquisition_category'     => new sfWidgetFormTextarea(),
      'acquisition_date_mask'    => new sfWidgetFormInput(),
      'acquisition_date'         => new sfWidgetFormDate(),
      'collecting_method'        => new sfWidgetFormTextarea(),
      'collecting_tool'          => new sfWidgetFormTextarea(),
      'specimen_count_min'       => new sfWidgetFormInput(),
      'specimen_count_max'       => new sfWidgetFormInput(),
      'station_visible'          => new sfWidgetFormInputCheckbox(),
      'multimedia_visible'       => new sfWidgetFormInputCheckbox(),
      'category'                 => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorDoctrineChoice(array('model' => 'Specimens', 'column' => 'id', 'required' => false)),
      'collection_ref'           => new sfValidatorDoctrineChoice(array('model' => 'Collections')),
      'expedition_ref'           => new sfValidatorDoctrineChoice(array('model' => 'Expeditions', 'required' => false)),
      'gtu_ref'                  => new sfValidatorInteger(),
      'taxon_ref'                => new sfValidatorDoctrineChoice(array('model' => 'Taxonomy')),
      'litho_ref'                => new sfValidatorDoctrineChoice(array('model' => 'Lithostratigraphy')),
      'chrono_ref'               => new sfValidatorDoctrineChoice(array('model' => 'Chronostratigraphy')),
      'lithology_ref'            => new sfValidatorDoctrineChoice(array('model' => 'Lithology')),
      'mineral_ref'              => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy')),
      'identification_qual'      => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'sp'                       => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'identification_taxon_ref' => new sfValidatorDoctrineChoice(array('model' => 'Taxonomy')),
      'host_taxon_ref'           => new sfValidatorDoctrineChoice(array('model' => 'Taxonomy')),
      'host_specimen_ref'        => new sfValidatorDoctrineChoice(array('model' => 'Specimens', 'required' => false)),
      'host_relationship'        => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'acquisition_category'     => new sfValidatorString(array('max_length' => 2147483647)),
      'acquisition_date_mask'    => new sfValidatorInteger(),
      'acquisition_date'         => new sfValidatorDate(),
      'collecting_method'        => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'collecting_tool'          => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'specimen_count_min'       => new sfValidatorInteger(),
      'specimen_count_max'       => new sfValidatorInteger(),
      'station_visible'          => new sfValidatorBoolean(),
      'multimedia_visible'       => new sfValidatorBoolean(),
      'category'                 => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('specimens[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specimens';
  }

}
