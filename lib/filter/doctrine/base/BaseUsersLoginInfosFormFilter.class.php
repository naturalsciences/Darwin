<?php

/**
 * UsersLoginInfos filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersLoginInfosFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'login_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_name'  => new sfWidgetFormFilterInput(),
      'password'   => new sfWidgetFormFilterInput(),
      'last_seen'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'login_type' => new sfValidatorPass(array('required' => false)),
      'user_name'  => new sfValidatorPass(array('required' => false)),
      'password'   => new sfValidatorPass(array('required' => false)),
      'last_seen'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_login_infos_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

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
      'last_seen'  => 'Text',
    );
  }
}
