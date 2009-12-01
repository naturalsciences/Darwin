<?php

/**
 * UsersLanguages form base class.
 *
 * @method UsersLanguages getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseUsersLanguagesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'users_ref'         => new sfWidgetFormInputHidden(),
      'language_country'  => new sfWidgetFormInputHidden(),
      'mother'            => new sfWidgetFormInputCheckbox(),
      'prefered_language' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'users_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'users_ref', 'required' => false)),
      'language_country'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'language_country', 'required' => false)),
      'mother'            => new sfValidatorBoolean(array('required' => false)),
      'prefered_language' => new sfValidatorBoolean(array('required' => false)),
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
