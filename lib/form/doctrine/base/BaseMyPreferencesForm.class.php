<?php

/**
 * MyPreferences form base class.
 *
 * @package    form
 * @subpackage my_preferences
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMyPreferencesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'category'    => new sfWidgetFormTextarea(),
      'group_name'  => new sfWidgetFormTextarea(),
      'order_by'    => new sfWidgetFormInput(),
      'col_num'     => new sfWidgetFormInput(),
      'mandatory'   => new sfWidgetFormInputCheckbox(),
      'visible'     => new sfWidgetFormInputCheckbox(),
      'opened'      => new sfWidgetFormInputCheckbox(),
      'color'       => new sfWidgetFormTextarea(),
      'icon_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
      'title_perso' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'MyPreferences', 'column' => 'id', 'required' => false)),
      'user_ref'    => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'category'    => new sfValidatorString(array('max_length' => 2147483647)),
      'group_name'  => new sfValidatorString(array('max_length' => 2147483647)),
      'order_by'    => new sfValidatorInteger(),
      'col_num'     => new sfValidatorInteger(),
      'mandatory'   => new sfValidatorBoolean(),
      'visible'     => new sfValidatorBoolean(),
      'opened'      => new sfValidatorBoolean(),
      'color'       => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'icon_ref'    => new sfValidatorDoctrineChoice(array('model' => 'Multimedia', 'required' => false)),
      'title_perso' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_preferences[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyPreferences';
  }

}
