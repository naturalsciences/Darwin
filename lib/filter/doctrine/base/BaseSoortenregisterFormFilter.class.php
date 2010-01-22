<?php

/**
 * Soortenregister filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSoortenregisterFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'taxa_ref'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Taxonomy'), 'add_empty' => true)),
      'gtu_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Gtu'), 'add_empty' => true)),
      'habitat_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Habitats'), 'add_empty' => true)),
      'date_from'   => new sfWidgetFormFilterInput(),
      'date_to'     => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'taxa_ref'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Taxonomy'), 'column' => 'id')),
      'gtu_ref'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Gtu'), 'column' => 'id')),
      'habitat_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Habitats'), 'column' => 'id')),
      'date_from'   => new sfValidatorPass(array('required' => false)),
      'date_to'     => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('soortenregister_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Soortenregister';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'taxa_ref'    => 'ForeignKey',
      'gtu_ref'     => 'ForeignKey',
      'habitat_ref' => 'ForeignKey',
      'date_from'   => 'Text',
      'date_to'     => 'Text',
    );
  }
}
