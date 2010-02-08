<?php

/**
 * UsersLanguages form base class.
 *
 * @method UsersLanguages getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUsersLanguagesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'users_ref'          => new sfWidgetFormInputHidden(),
      'language_country'   => new sfWidgetFormInputHidden(),
      'mother'             => new sfWidgetFormInputCheckbox(),
      'preferred_language' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'users_ref'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'users_ref', 'required' => false)),
      'language_country'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'language_country', 'required' => false)),
      'mother'             => new sfValidatorBoolean(array('required' => false)),
      'preferred_language' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('users_languages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersLanguages';
  }

}
