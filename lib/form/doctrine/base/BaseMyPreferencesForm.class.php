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
      'user_ref'    => new sfWidgetFormInputHidden(),
      'category'    => new sfWidgetFormInputHidden(),
      'group_name'  => new sfWidgetFormInputHidden(),
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
      'user_ref'    => new sfValidatorDoctrineChoice(array('model' => 'MyPreferences', 'column' => 'user_ref', 'required' => false)),
      'category'    => new sfValidatorDoctrineChoice(array('model' => 'MyPreferences', 'column' => 'category', 'required' => false)),
      'group_name'  => new sfValidatorDoctrineChoice(array('model' => 'MyPreferences', 'column' => 'group_name', 'required' => false)),
      'order_by'    => new sfValidatorInteger(),
      'col_num'     => new sfValidatorInteger(),
      'mandatory'   => new sfValidatorBoolean(),
      'visible'     => new sfValidatorBoolean(),
      'opened'      => new sfValidatorBoolean(),
      'color'       => new sfValidatorString(array('required' => false)),
      'icon_ref'    => new sfValidatorDoctrineChoice(array('model' => 'Multimedia', 'required' => false)),
      'title_perso' => new sfValidatorString(array('required' => false)),
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
