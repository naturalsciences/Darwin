<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersLoginInfos filter form base class.
 *
 * @package    filters
 * @subpackage UsersLoginInfos *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersLoginInfosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'login_type' => new sfWidgetFormFilterInput(),
      'user_name'  => new sfWidgetFormFilterInput(),
      'password'   => new sfWidgetFormFilterInput(),
      'last_seen'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'login_type' => new sfValidatorPass(array('required' => false)),
      'user_name'  => new sfValidatorPass(array('required' => false)),
      'password'   => new sfValidatorPass(array('required' => false)),
      'last_seen'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('users_login_infos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersLoginInfos';
  }

  public function getFields()
  {
    return array(
      'user_ref'   => 'Number',
      'login_type' => 'Text',
      'user_name'  => 'Text',
      'password'   => 'Text',
      'system_id'  => 'Text',
      'last_seen'  => 'Date',
    );
  }
}