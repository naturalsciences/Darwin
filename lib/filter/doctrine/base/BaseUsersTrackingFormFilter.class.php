<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersTracking filter form base class.
 *
 * @package    filters
 * @subpackage UsersTracking *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersTrackingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation'    => new sfWidgetFormFilterInput(),
      'record_id'              => new sfWidgetFormFilterInput(),
      'user_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'action'                 => new sfWidgetFormFilterInput(),
      'modification_date_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'referenced_relation'    => new sfValidatorPass(array('required' => false)),
      'record_id'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'user_ref'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'action'                 => new sfValidatorPass(array('required' => false)),
      'modification_date_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('users_tracking_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTracking';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'referenced_relation'    => 'Text',
      'record_id'              => 'Number',
      'user_ref'               => 'ForeignKey',
      'action'                 => 'Text',
      'modification_date_time' => 'Date',
    );
  }
}