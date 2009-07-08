<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersTrackingRecords filter form base class.
 *
 * @package    filters
 * @subpackage UsersTrackingRecords *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersTrackingRecordsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tracking_ref' => new sfWidgetFormFilterInput(),
      'field_name'   => new sfWidgetFormFilterInput(),
      'old_value'    => new sfWidgetFormFilterInput(),
      'new_value'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tracking_ref' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'field_name'   => new sfValidatorPass(array('required' => false)),
      'old_value'    => new sfValidatorPass(array('required' => false)),
      'new_value'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_tracking_records_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTrackingRecords';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'tracking_ref' => 'Number',
      'field_name'   => 'Text',
      'old_value'    => 'Text',
      'new_value'    => 'Text',
    );
  }
}