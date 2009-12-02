<?php

/**
 * People form base class.
 *
 * @method People getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePeopleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'is_physical'           => new sfWidgetFormInputCheckbox(),
      'sub_type'              => new sfWidgetFormTextarea(),
      'public_class'          => new sfWidgetFormChoice(array('choices' => array('public' => 'public', 'private' => 'private'))),
      'formated_name'         => new sfWidgetFormTextarea(),
      'formated_name_indexed' => new sfWidgetFormTextarea(),
      'formated_name_ts'      => new sfWidgetFormTextarea(),
      'title'                 => new sfWidgetFormTextarea(),
      'family_name'           => new sfWidgetFormTextarea(),
      'given_name'            => new sfWidgetFormTextarea(),
      'additional_names'      => new sfWidgetFormTextarea(),
      'birth_date_mask'       => new sfWidgetFormInputText(),
      'birth_date'            => new sfWidgetFormDate(),
      'gender'                => new sfWidgetFormChoice(array('choices' => array('M' => 'M', 'F' => 'F'))),
      'db_people_type'        => new sfWidgetFormInputText(),
      'end_date_mask'         => new sfWidgetFormInputText(),
      'end_date'              => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'is_physical'           => new sfValidatorBoolean(),
      'sub_type'              => new sfValidatorString(array('required' => false)),
      'public_class'          => new sfValidatorChoice(array('choices' => array('public' => 'public', 'private' => 'private'), 'required' => false)),
      'formated_name'         => new sfValidatorString(array('required' => false)),
      'formated_name_indexed' => new sfValidatorString(array('required' => false)),
      'formated_name_ts'      => new sfValidatorString(array('required' => false)),
      'title'                 => new sfValidatorString(array('required' => false)),
      'family_name'           => new sfValidatorString(),
      'given_name'            => new sfValidatorString(array('required' => false)),
      'additional_names'      => new sfValidatorString(array('required' => false)),
      'birth_date_mask'       => new sfValidatorInteger(array('required' => false)),
      'birth_date'            => new sfValidatorDate(array('required' => false)),
      'gender'                => new sfValidatorChoice(array('choices' => array('M' => 'M', 'F' => 'F'), 'required' => false)),
      'db_people_type'        => new sfValidatorInteger(array('required' => false)),
      'end_date_mask'         => new sfValidatorInteger(array('required' => false)),
      'end_date'              => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'People';
  }

}
