<?php

/**
 * ClassificationKeywords form base class.
 *
 * @package    form
 * @subpackage classification_keywords
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseClassificationKeywordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'table_name'   => new sfWidgetFormTextarea(),
      'record_id'    => new sfWidgetFormInput(),
      'keyword_type' => new sfWidgetFormTextarea(),
      'keyword'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'ClassificationKeywords', 'column' => 'id', 'required' => false)),
      'table_name'   => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'    => new sfValidatorInteger(),
      'keyword_type' => new sfValidatorString(array('max_length' => 2147483647)),
      'keyword'      => new sfValidatorString(array('max_length' => 2147483647)),
    ));

    $this->widgetSchema->setNameFormat('classification_keywords[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassificationKeywords';
  }

}
