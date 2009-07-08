<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UsersTablesFieldsTracked filter form base class.
 *
 * @package    filters
 * @subpackage UsersTablesFieldsTracked *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersTablesFieldsTrackedFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name' => new sfWidgetFormFilterInput(),
      'field_name' => new sfWidgetFormFilterInput(),
      'user_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'table_name' => new sfValidatorPass(array('required' => false)),
      'field_name' => new sfValidatorPass(array('required' => false)),
      'user_ref'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('users_tables_fields_tracked_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTablesFieldsTracked';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'table_name' => 'Text',
      'field_name' => 'Text',
      'user_ref'   => 'ForeignKey',
    );
  }
}