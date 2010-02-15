<?php

/**
 * Users form base class.
 *
 * @method Users getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'is_physical'           => new sfWidgetFormInputCheckbox(),
      'sub_type'              => new sfWidgetFormTextarea(),
      'formated_name'         => new sfWidgetFormTextarea(),
      'formated_name_indexed' => new sfWidgetFormTextarea(),
      'formated_name_ts'      => new sfWidgetFormTextarea(),
      'title'                 => new sfWidgetFormTextarea(),
      'family_name'           => new sfWidgetFormTextarea(),
      'given_name'            => new sfWidgetFormTextarea(),
      'additional_names'      => new sfWidgetFormTextarea(),
      'birth_date_mask'       => new sfWidgetFormInputText(),
      'birth_date'            => new sfWidgetFormTextarea(),
      'gender'                => new sfWidgetFormChoice(array('choices' => array('M' => 'M', 'F' => 'F'))),
      'db_user_type'          => new sfWidgetFormInputText(),
      'people_id'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'add_empty' => true)),
      'approval_level'        => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'is_physical'           => new sfValidatorBoolean(),
      'sub_type'              => new sfValidatorString(array('required' => false)),
      'formated_name'         => new sfValidatorString(array('required' => false)),
      'formated_name_indexed' => new sfValidatorString(array('required' => false)),
      'formated_name_ts'      => new sfValidatorString(array('required' => false)),
      'title'                 => new sfValidatorString(),
      'family_name'           => new sfValidatorString(),
      'given_name'            => new sfValidatorString(array('required' => false)),
      'additional_names'      => new sfValidatorString(array('required' => false)),
      'birth_date_mask'       => new sfValidatorInteger(array('required' => false)),
      'birth_date'            => new sfValidatorString(array('required' => false)),
      'gender'                => new sfValidatorChoice(array('choices' => array(0 => 'M', 1 => 'F'), 'required' => false)),
      'db_user_type'          => new sfValidatorInteger(array('required' => false)),
      'people_id'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'required' => false)),
      'approval_level'        => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Users';
  }

}
