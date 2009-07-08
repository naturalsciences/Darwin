<?php

/**
 * UsersCollRightsAsked form base class.
 *
 * @package    form
 * @subpackage users_coll_rights_asked
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersCollRightsAskedForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'collection_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => false)),
      'user_ref'             => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'field_group_name'     => new sfWidgetFormTextarea(),
      'db_user_type'         => new sfWidgetFormInput(),
      'searchable'           => new sfWidgetFormInputCheckbox(),
      'visible'              => new sfWidgetFormInputCheckbox(),
      'motivation'           => new sfWidgetFormTextarea(),
      'asking_date_time'     => new sfWidgetFormDateTime(),
      'with_sub_collections' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorDoctrineChoice(array('model' => 'UsersCollRightsAsked', 'column' => 'id', 'required' => false)),
      'collection_ref'       => new sfValidatorDoctrineChoice(array('model' => 'Collections')),
      'user_ref'             => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'field_group_name'     => new sfValidatorString(array('max_length' => 2147483647)),
      'db_user_type'         => new sfValidatorInteger(),
      'searchable'           => new sfValidatorBoolean(),
      'visible'              => new sfValidatorBoolean(),
      'motivation'           => new sfValidatorString(array('max_length' => 2147483647)),
      'asking_date_time'     => new sfValidatorDateTime(),
      'with_sub_collections' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('users_coll_rights_asked[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersCollRightsAsked';
  }

}
