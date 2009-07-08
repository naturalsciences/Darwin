<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Expertise filter form base class.
 *
 * @package    filters
 * @subpackage Expertise *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseExpertiseFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'expert_ref'                  => new sfWidgetFormFilterInput(),
      'defined_by_ordered_ids_list' => new sfWidgetFormFilterInput(),
      'order_by'                    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'expert_ref'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'defined_by_ordered_ids_list' => new sfValidatorPass(array('required' => false)),
      'order_by'                    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('expertise_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Expertise';
  }

  public function getFields()
  {
    return array(
      'table_name'                  => 'Text',
      'record_id'                   => 'Number',
      'expert_ref'                  => 'Number',
      'defined_by_ordered_ids_list' => 'Text',
      'order_by'                    => 'Text',
    );
  }
}