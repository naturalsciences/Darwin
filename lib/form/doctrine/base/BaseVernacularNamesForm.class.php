<?php

/**
 * VernacularNames form base class.
 *
 * @package    form
 * @subpackage vernacular_names
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseVernacularNamesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'vernacular_class_ref'       => new sfWidgetFormInputHidden(),
      'name'                       => new sfWidgetFormTextarea(),
      'name_ts'                    => new sfWidgetFormTextarea(),
      'country_language_full_text' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'vernacular_class_ref'       => new sfValidatorDoctrineChoice(array('model' => 'VernacularNames', 'column' => 'vernacular_class_ref', 'required' => false)),
      'name'                       => new sfValidatorString(),
      'name_ts'                    => new sfValidatorString(array('required' => false)),
      'country_language_full_text' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('vernacular_names[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'VernacularNames';
  }

}
