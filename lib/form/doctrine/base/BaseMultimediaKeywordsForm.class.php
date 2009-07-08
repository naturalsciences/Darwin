<?php

/**
 * MultimediaKeywords form base class.
 *
 * @package    form
 * @subpackage multimedia_keywords
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMultimediaKeywordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => false)),
      'keyword'         => new sfWidgetFormTextarea(),
      'keyword_indexed' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'MultimediaKeywords', 'column' => 'id', 'required' => false)),
      'object_ref'      => new sfValidatorDoctrineChoice(array('model' => 'Multimedia')),
      'keyword'         => new sfValidatorString(array('max_length' => 2147483647)),
      'keyword_indexed' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia_keywords[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MultimediaKeywords';
  }

}
