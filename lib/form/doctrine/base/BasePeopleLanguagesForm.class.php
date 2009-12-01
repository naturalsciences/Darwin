<?php

/**
 * PeopleLanguages form base class.
 *
 * @method PeopleLanguages getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BasePeopleLanguagesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'people_ref'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'add_empty' => false)),
      'language_country'  => new sfWidgetFormTextarea(),
      'mother'            => new sfWidgetFormInputCheckbox(),
      'prefered_language' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'people_ref'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People'))),
      'language_country'  => new sfValidatorString(array('required' => false)),
      'mother'            => new sfValidatorBoolean(array('required' => false)),
      'prefered_language' => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_languages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleLanguages';
  }

}
