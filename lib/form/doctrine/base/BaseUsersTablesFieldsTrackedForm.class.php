<?php

/**
 * UsersTablesFieldsTracked form base class.
 *
 * @package    form
 * @subpackage users_tables_fields_tracked
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersTablesFieldsTrackedForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'field_name'          => new sfWidgetFormTextarea(),
      'user_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'UsersTablesFieldsTracked', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'field_name'          => new sfValidatorString(),
      'user_ref'            => new sfValidatorDoctrineChoice(array('model' => 'Users')),
    ));

    $this->widgetSchema->setNameFormat('users_tables_fields_tracked[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTablesFieldsTracked';
  }

}
