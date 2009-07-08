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
      'id'                       => new sfWidgetFormInputHidden(),
      'user_ref'                 => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'name'                     => new sfWidgetFormTextarea(),
      'search_criterias'         => new sfWidgetFormTextarea(),
      'favorite'                 => new sfWidgetFormInputCheckbox(),
      'modification_date_time'   => new sfWidgetFormDateTime(),
      'visible_fields_in_result' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorDoctrineChoice(array('model' => 'MySavedSearches', 'column' => 'id', 'required' => false)),
      'user_ref'                 => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'name'                     => new sfValidatorString(array('max_length' => 2147483647)),
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
