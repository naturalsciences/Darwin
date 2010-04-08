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
      'user_ref'     => new sfWidgetFormInputHidden(),
      'category'     => new sfWidgetFormInputHidden(),
      'group_name'   => new sfWidgetFormInputHidden(),
      'order_by'     => new sfWidgetFormInputText(),
      'col_num'      => new sfWidgetFormInputText(),
      'mandatory'    => new sfWidgetFormInputCheckbox(),
      'visible'      => new sfWidgetFormInputCheckbox(),
<<<<<<< HEAD:lib/form/doctrine/base/BaseMyPreferencesForm.class.php
      'is_available' => new sfWidgetFormInputCheckbox(),
      'opened'       => new sfWidgetFormInputCheckbox(),
      'color'        => new sfWidgetFormTextarea(),
=======
      'opened'       => new sfWidgetFormInputCheckbox(),
      'color'        => new sfWidgetFormTextarea(),
      'is_available' => new sfWidgetFormInputCheckbox(),
>>>>>>> 14718673772fd2b24fae3e62c613e6bce80b9512:lib/form/doctrine/base/BaseMyPreferencesForm.class.php
      'icon_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => true)),
      'title_perso'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'user_ref'     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_ref', 'required' => false)),
      'category'     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'category', 'required' => false)),
      'group_name'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'group_name', 'required' => false)),
      'order_by'     => new sfValidatorInteger(array('required' => false)),
      'col_num'      => new sfValidatorInteger(array('required' => false)),
      'mandatory'    => new sfValidatorBoolean(array('required' => false)),
      'visible'      => new sfValidatorBoolean(array('required' => false)),
<<<<<<< HEAD:lib/form/doctrine/base/BaseMyPreferencesForm.class.php
      'is_available' => new sfValidatorBoolean(array('required' => false)),
      'opened'       => new sfValidatorBoolean(array('required' => false)),
      'color'        => new sfValidatorString(array('required' => false)),
=======
      'opened'       => new sfValidatorBoolean(array('required' => false)),
      'color'        => new sfValidatorString(array('required' => false)),
      'is_available' => new sfValidatorBoolean(array('required' => false)),
>>>>>>> 14718673772fd2b24fae3e62c613e6bce80b9512:lib/form/doctrine/base/BaseMyPreferencesForm.class.php
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
