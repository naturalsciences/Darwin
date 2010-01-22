<?php

/**
 * MultimediaKeywords filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMultimediaKeywordsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => true)),
      'keyword'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'keyword_indexed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'object_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Multimedia'), 'column' => 'id')),
      'keyword'         => new sfValidatorPass(array('required' => false)),
      'keyword_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia_keywords_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MultimediaKeywords';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'object_ref'      => 'ForeignKey',
      'keyword'         => 'Text',
      'keyword_indexed' => 'Text',
    );
  }
}
