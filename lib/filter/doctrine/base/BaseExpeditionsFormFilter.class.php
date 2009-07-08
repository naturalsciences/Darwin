<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Expeditions filter form base class.
 *
 * @package    filters
 * @subpackage Expeditions *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseExpeditionsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                      => new sfWidgetFormFilterInput(),
      'name_ts'                   => new sfWidgetFormFilterInput(),
      'name_indexed'              => new sfWidgetFormFilterInput(),
      'name_language_full_text'   => new sfWidgetFormFilterInput(),
      'expedition_from_date_mask' => new sfWidgetFormFilterInput(),
      'expedition_from_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'expedition_to_date_mask'   => new sfWidgetFormFilterInput(),
      'expedition_to_date'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'name'                      => new sfValidatorPass(array('required' => false)),
      'name_ts'                   => new sfValidatorPass(array('required' => false)),
      'name_indexed'              => new sfValidatorPass(array('required' => false)),
      'name_language_full_text'   => new sfValidatorPass(array('required' => false)),
      'expedition_from_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expedition_from_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'expedition_to_date_mask'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expedition_to_date'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('expeditions_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Expeditions';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'name'                      => 'Text',
      'name_ts'                   => 'Text',
      'name_indexed'              => 'Text',
      'name_language_full_text'   => 'Text',
      'expedition_from_date_mask' => 'Number',
      'expedition_from_date'      => 'Date',
      'expedition_to_date_mask'   => 'Number',
      'expedition_to_date'        => 'Date',
    );
  }
}