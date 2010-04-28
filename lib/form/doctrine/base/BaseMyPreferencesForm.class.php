<?php

/**
 * MyPreferences form base class.
 *
 * @method MyPreferences getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMyPreferencesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'user_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'category'     => new sfWidgetFormTextarea(),
      'group_name'   => new sfWidgetFormTextarea(),
      'order_by'     => new sfWidgetFormInputText(),
      'col_num'      => new sfWidgetFormInputText(),
      'mandatory'    => new sfWidgetFormInputCheckbox(),
      'visible'      => new sfWidgetFormInputCheckbox(),
      'is_available' => new sfWidgetFormInputCheckbox(),
      'opened'       => new sfWidgetFormInputCheckbox(),
      'color'        => new sfWidgetFormTextarea(),
      'icon_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => true)),
      'title_perso'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'user_ref'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'category'     => new sfValidatorString(array('required' => false)),
      'group_name'   => new sfValidatorString(),
      'order_by'     => new sfValidatorInteger(array('required' => false)),
      'col_num'      => new sfValidatorInteger(array('required' => false)),
      'mandatory'    => new sfValidatorBoolean(array('required' => false)),
      'visible'      => new sfValidatorBoolean(array('required' => false)),
      'is_available' => new sfValidatorBoolean(array('required' => false)),
      'opened'       => new sfValidatorBoolean(array('required' => false)),
      'color'        => new sfValidatorString(array('required' => false)),
      'icon_ref'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'required' => false)),
      'title_perso'  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_preferences[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyPreferences';
  }

}
