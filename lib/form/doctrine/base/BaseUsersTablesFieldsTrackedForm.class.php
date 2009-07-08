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
      'id'         => new sfWidgetFormInputHidden(),
      'table_name' => new sfWidgetFormTextarea(),
      'field_name' => new sfWidgetFormTextarea(),
      'user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'UsersTablesFieldsTracked', 'column' => 'id', 'required' => false)),
      'table_name' => new sfValidatorString(array('max_length' => 2147483647)),
      'field_name' => new sfValidatorString(array('max_length' => 2147483647)),
      'user_ref'   => new sfValidatorDoctrineChoice(array('model' => 'Users')),
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
