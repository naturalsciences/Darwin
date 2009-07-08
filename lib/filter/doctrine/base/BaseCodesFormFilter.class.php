<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Codes filter form base class.
 *
 * @package    filters
 * @subpackage Codes *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCodesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'        => new sfWidgetFormFilterInput(),
      'record_id'         => new sfWidgetFormFilterInput(),
      'code_category'     => new sfWidgetFormFilterInput(),
      'code_prefix'       => new sfWidgetFormFilterInput(),
      'code'              => new sfWidgetFormFilterInput(),
      'code_suffix'       => new sfWidgetFormFilterInput(),
      'full_code_indexed' => new sfWidgetFormFilterInput(),
      'code_date'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'table_name'        => new sfValidatorPass(array('required' => false)),
      'record_id'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code_category'     => new sfValidatorPass(array('required' => false)),
      'code_prefix'       => new sfValidatorPass(array('required' => false)),
      'code'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'code_suffix'       => new sfValidatorPass(array('required' => false)),
      'full_code_indexed' => new sfValidatorPass(array('required' => false)),
      'code_date'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('codes_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Codes';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'table_name'        => 'Text',
      'record_id'         => 'Number',
      'code_category'     => 'Text',
      'code_prefix'       => 'Text',
      'code'              => 'Number',
      'code_suffix'       => 'Text',
      'full_code_indexed' => 'Text',
      'code_date'         => 'Date',
    );
  }
}