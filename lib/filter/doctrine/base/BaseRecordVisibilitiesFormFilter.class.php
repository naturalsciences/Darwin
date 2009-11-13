<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * RecordVisibilities filter form base class.
 *
 * @package    filters
 * @subpackage RecordVisibilities *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseRecordVisibilitiesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation' => new sfWidgetFormFilterInput(),
      'record_id'           => new sfWidgetFormFilterInput(),
      'db_user_type'        => new sfWidgetFormFilterInput(),
      'user_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'visible'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'referenced_relation' => new sfValidatorPass(array('required' => false)),
      'record_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'db_user_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_ref'            => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'visible'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('record_visibilities_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RecordVisibilities';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'referenced_relation' => 'Text',
      'record_id'           => 'Number',
      'db_user_type'        => 'Number',
      'user_ref'            => 'ForeignKey',
      'visible'             => 'Boolean',
    );
  }
}