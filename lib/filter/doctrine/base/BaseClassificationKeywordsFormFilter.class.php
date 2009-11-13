<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * ClassificationKeywords filter form base class.
 *
 * @package    filters
 * @subpackage ClassificationKeywords *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseClassificationKeywordsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation' => new sfWidgetFormFilterInput(),
      'record_id'           => new sfWidgetFormFilterInput(),
      'keyword_type'        => new sfWidgetFormFilterInput(),
      'keyword'             => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'referenced_relation' => new sfValidatorPass(array('required' => false)),
      'record_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'keyword_type'        => new sfValidatorPass(array('required' => false)),
      'keyword'             => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('classification_keywords_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassificationKeywords';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'referenced_relation' => 'Text',
      'record_id'           => 'Number',
      'keyword_type'        => 'Text',
      'keyword'             => 'Text',
    );
  }
}