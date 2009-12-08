<?php

/**
 * MySavedSearches form base class.
 *
 * @method MySavedSearches getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMySavedSearchesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'                 => new sfWidgetFormInputHidden(),
      'name'                     => new sfWidgetFormInputHidden(),
      'search_criterias'         => new sfWidgetFormTextarea(),
      'favorite'                 => new sfWidgetFormInputCheckbox(),
      'modification_date_time'   => new sfWidgetFormTextarea(),
      'visible_fields_in_result' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'user_ref'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_ref', 'required' => false)),
      'name'                     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'name', 'required' => false)),
      'search_criterias'         => new sfValidatorString(),
      'favorite'                 => new sfValidatorBoolean(array('required' => false)),
      'modification_date_time'   => new sfValidatorString(),
      'visible_fields_in_result' => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('my_saved_searches[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSearches';
  }

}
