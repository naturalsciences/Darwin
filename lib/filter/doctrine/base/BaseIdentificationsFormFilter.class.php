<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Identifications filter form base class.
 *
 * @package    filters
 * @subpackage Identifications *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseIdentificationsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'            => new sfWidgetFormFilterInput(),
      'record_id'             => new sfWidgetFormFilterInput(),
      'notion_concerned'      => new sfWidgetFormFilterInput(),
      'notion_date'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'value_defined'         => new sfWidgetFormFilterInput(),
      'value_defined_indexed' => new sfWidgetFormFilterInput(),
      'value_defined_ts'      => new sfWidgetFormFilterInput(),
      'determination_status'  => new sfWidgetFormFilterInput(),
      'order_by'              => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'            => new sfValidatorPass(array('required' => false)),
      'record_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'notion_concerned'      => new sfValidatorPass(array('required' => false)),
      'notion_date'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'value_defined'         => new sfValidatorPass(array('required' => false)),
      'value_defined_indexed' => new sfValidatorPass(array('required' => false)),
      'value_defined_ts'      => new sfValidatorPass(array('required' => false)),
      'determination_status'  => new sfValidatorPass(array('required' => false)),
      'order_by'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('identifications_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Identifications';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'table_name'            => 'Text',
      'record_id'             => 'Number',
      'notion_concerned'      => 'Text',
      'notion_date'           => 'Date',
      'value_defined'         => 'Text',
      'value_defined_indexed' => 'Text',
      'value_defined_ts'      => 'Text',
      'determination_status'  => 'Text',
      'order_by'              => 'Number',
    );
  }
}