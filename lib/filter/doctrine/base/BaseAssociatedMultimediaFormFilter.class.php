<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * AssociatedMultimedia filter form base class.
 *
 * @package    filters
 * @subpackage AssociatedMultimedia *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseAssociatedMultimediaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'     => new sfWidgetFormFilterInput(),
      'record_id'      => new sfWidgetFormFilterInput(),
      'multimedia_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'table_name'     => new sfValidatorPass(array('required' => false)),
      'record_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'multimedia_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Multimedia', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('associated_multimedia_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AssociatedMultimedia';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'table_name'     => 'Text',
      'record_id'      => 'Number',
      'multimedia_ref' => 'ForeignKey',
    );
  }
}