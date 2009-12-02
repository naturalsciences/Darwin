<?php

/**
 * UsersTablesFieldsTracked form base class.
 *
 * @method UsersTablesFieldsTracked getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersTablesFieldsTrackedForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'field_name'          => new sfWidgetFormTextarea(),
      'user_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'field_name'          => new sfValidatorString(),
      'user_ref'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'))),
    ));

    $this->widgetSchema->setNameFormat('users_tables_fields_tracked[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersTablesFieldsTracked';
  }

}
