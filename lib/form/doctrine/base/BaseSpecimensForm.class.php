<?php

/**
 * Specimens form base class.
 *
 * @method Specimens getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSpecimensForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'category'              => new sfWidgetFormTextarea(),
      'collection_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => false)),
      'expedition_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Expeditions'), 'add_empty' => true)),
      'gtu_ref'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'add_empty' => true)),
      'taxon_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'add_empty' => true)),
      'litho_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lithostratigraphy'), 'add_empty' => true)),
      'chrono_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Chronostratigraphy'), 'add_empty' => true)),
      'lithology_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Lithology'), 'add_empty' => true)),
      'mineral_ref'           => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Mineralogy'), 'add_empty' => true)),
      'host_taxon_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HostTaxon'), 'add_empty' => true)),
      'host_specimen_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('HostSpecimen'), 'add_empty' => true)),
      'host_relationship'     => new sfWidgetFormTextarea(),
      'acquisition_category'  => new sfWidgetFormTextarea(),
      'acquisition_date_mask' => new sfWidgetFormInputText(),
      'acquisition_date'      => new sfWidgetFormTextarea(),
      'collecting_method'     => new sfWidgetFormTextarea(),
      'collecting_tool'       => new sfWidgetFormTextarea(),
      'specimen_count_min'    => new sfWidgetFormInputText(),
      'specimen_count_max'    => new sfWidgetFormInputText(),
      'station_visible'       => new sfWidgetFormInputCheckbox(),
      'multimedia_visible'    => new sfWidgetFormInputCheckbox(),
      'ig_ref'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Igs'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'category'              => new sfValidatorString(array('required' => false)),
      'collection_ref'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'))),
      'expedition_ref'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Expeditions'), 'required' => false)),
      'gtu_ref'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'required' => false)),
      'taxon_ref'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'required' => false)),
      'litho_ref'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Lithostratigraphy'), 'required' => false)),
      'chrono_ref'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Chronostratigraphy'), 'required' => false)),
      'lithology_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Lithology'), 'required' => false)),
      'mineral_ref'           => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Mineralogy'), 'required' => false)),
      'host_taxon_ref'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('HostTaxon'), 'required' => false)),
      'host_specimen_ref'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('HostSpecimen'), 'required' => false)),
      'host_relationship'     => new sfValidatorString(array('required' => false)),
      'acquisition_category'  => new sfValidatorString(array('required' => false)),
      'acquisition_date_mask' => new sfValidatorInteger(array('required' => false)),
      'acquisition_date'      => new sfValidatorString(array('required' => false)),
      'collecting_method'     => new sfValidatorString(array('required' => false)),
      'collecting_tool'       => new sfValidatorString(array('required' => false)),
      'specimen_count_min'    => new sfValidatorInteger(array('required' => false)),
      'specimen_count_max'    => new sfValidatorInteger(array('required' => false)),
      'station_visible'       => new sfValidatorBoolean(array('required' => false)),
      'multimedia_visible'    => new sfValidatorBoolean(array('required' => false)),
      'ig_ref'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Igs'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('specimens[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Specimens';
  }

}
