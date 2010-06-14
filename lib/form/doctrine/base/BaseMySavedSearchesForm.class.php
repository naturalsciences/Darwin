<?php

/**
 * MySavedSearches form base class.
 *
 * @method MySavedSearches getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
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
      'user_ref'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->get('user_ref')), 'empty_value' => $this->getObject()->get('user_ref'), 'required' => false)),
      'name'                     => new sfValidatorChoice(array('choices' => array($this->getObject()->get('name')), 'empty_value' => $this->getObject()->get('name'), 'required' => false)),
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
