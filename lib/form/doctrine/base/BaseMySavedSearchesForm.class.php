<?php

/**
 * MySavedSearches form base class.
 *
 * @package    form
 * @subpackage my_saved_searches
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMySavedSearchesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_ref'                 => new sfWidgetFormInputHidden(),
      'name'                     => new sfWidgetFormInputHidden(),
      'search_criterias'         => new sfWidgetFormTextarea(),
      'favorite'                 => new sfWidgetFormInputCheckbox(),
      'modification_date_time'   => new sfWidgetFormDateTime(),
      'visible_fields_in_result' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'user_ref'                 => new sfValidatorDoctrineChoice(array('model' => 'MySavedSearches', 'column' => 'user_ref', 'required' => false)),
      'name'                     => new sfValidatorDoctrineChoice(array('model' => 'MySavedSearches', 'column' => 'name', 'required' => false)),
      'search_criterias'         => new sfValidatorString(array('max_length' => 2147483647)),
      'favorite'                 => new sfValidatorBoolean(),
      'modification_date_time'   => new sfValidatorDateTime(),
      'visible_fields_in_result' => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('my_saved_searches[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSearches';
  }

}
