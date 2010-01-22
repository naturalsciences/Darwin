<?php

/**
 * VernacularNames form base class.
 *
 * @method VernacularNames getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseVernacularNamesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'vernacular_class_ref'       => new sfWidgetFormInputHidden(),
      'name'                       => new sfWidgetFormTextarea(),
      'name_indexed'               => new sfWidgetFormTextarea(),
      'name_ts'                    => new sfWidgetFormTextarea(),
      'country_language_full_text' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'vernacular_class_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'vernacular_class_ref', 'required' => false)),
      'name'                       => new sfValidatorString(),
      'name_indexed'               => new sfValidatorString(),
      'name_ts'                    => new sfValidatorString(array('required' => false)),
      'country_language_full_text' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('vernacular_names[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'VernacularNames';
  }

}
