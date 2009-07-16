<?php

/**
 * UsersLanguages form base class.
 *
 * @package    form
 * @subpackage users_languages
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUsersLanguagesForm extends BaseFormDoctrine
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
      'users_ref'         => new sfValidatorDoctrineChoice(array('model' => 'UsersLanguages', 'column' => 'users_ref', 'required' => false)),
      'language_country'  => new sfValidatorDoctrineChoice(array('model' => 'UsersLanguages', 'column' => 'language_country', 'required' => false)),
      'mother'            => new sfValidatorBoolean(),
      'prefered_language' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('users_languages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsersLanguages';
  }

}
