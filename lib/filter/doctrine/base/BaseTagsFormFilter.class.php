<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Tags filter form base class.
 *
 * @package    filters
 * @subpackage Tags *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'label'         => new sfWidgetFormFilterInput(),
      'label_indexed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'label'         => new sfValidatorPass(array('required' => false)),
      'label_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tags';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'label'         => 'Text',
      'label_indexed' => 'Text',
    );
  }
}