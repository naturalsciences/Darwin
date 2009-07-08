<?php

/**
 * PeopleLanguages form base class.
 *
 * @package    form
 * @subpackage people_languages
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BasePeopleLanguagesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'people_ref'        => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'language_country'  => new sfWidgetFormTextarea(),
      'mother'            => new sfWidgetFormInputCheckbox(),
      'prefered_language' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'PeopleLanguages', 'column' => 'id', 'required' => false)),
      'people_ref'        => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'language_country'  => new sfValidatorString(array('max_length' => 2147483647)),
      'mother'            => new sfValidatorBoolean(),
      'prefered_language' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('people_languages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleLanguages';
  }

}
