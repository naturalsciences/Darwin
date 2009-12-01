<?php

/**
 * MultimediaKeywords form base class.
 *
 * @method MultimediaKeywords getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseMultimediaKeywordsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => false)),
      'keyword'         => new sfWidgetFormTextarea(),
      'keyword_indexed' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'object_ref'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'))),
      'keyword'         => new sfValidatorString(),
      'keyword_indexed' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia_keywords[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MultimediaKeywords';
  }

}
