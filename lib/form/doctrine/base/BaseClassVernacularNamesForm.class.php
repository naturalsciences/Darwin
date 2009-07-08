<?php

/**
 * ClassVernacularNames form base class.
 *
 * @package    form
 * @subpackage class_vernacular_names
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseClassVernacularNamesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'table_name' => new sfWidgetFormTextarea(),
      'record_id'  => new sfWidgetFormInput(),
      'community'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => 'ClassVernacularNames', 'column' => 'id', 'required' => false)),
      'table_name' => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'  => new sfValidatorInteger(),
      'community'  => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('class_vernacular_names[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassVernacularNames';
  }

}
