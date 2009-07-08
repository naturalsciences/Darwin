<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MultimediaKeywords filter form base class.
 *
 * @package    filters
 * @subpackage MultimediaKeywords *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMultimediaKeywordsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
      'keyword'         => new sfWidgetFormFilterInput(),
      'keyword_indexed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'object_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Multimedia', 'column' => 'id')),
      'keyword'         => new sfValidatorPass(array('required' => false)),
      'keyword_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia_keywords_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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