<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Gtu filter form base class.
 *
 * @package    filters
 * @subpackage Gtu *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseGtuFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'code'               => new sfWidgetFormFilterInput(),
      'parent_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Gtu', 'add_empty' => true)),
      'gtu_from_date_mask' => new sfWidgetFormFilterInput(),
      'gtu_from_date'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'gtu_to_date_mask'   => new sfWidgetFormFilterInput(),
      'gtu_to_date'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'code'               => new sfValidatorPass(array('required' => false)),
      'parent_ref'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Gtu', 'column' => 'id')),
      'gtu_from_date_mask' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gtu_from_date'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'gtu_to_date_mask'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gtu_to_date'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('gtu_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gtu';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'code'               => 'Text',
      'parent_ref'         => 'ForeignKey',
      'gtu_from_date_mask' => 'Number',
      'gtu_from_date'      => 'Date',
      'gtu_to_date_mask'   => 'Number',
      'gtu_to_date'        => 'Date',
    );
  }
}