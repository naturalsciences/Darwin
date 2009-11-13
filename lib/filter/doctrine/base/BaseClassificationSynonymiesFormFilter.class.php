<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * ClassificationSynonymies filter form base class.
 *
 * @package    filters
 * @subpackage ClassificationSynonymies *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseClassificationSynonymiesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'referenced_relation' => new sfWidgetFormFilterInput(),
      'record_id'           => new sfWidgetFormFilterInput(),
      'group_id'            => new sfWidgetFormFilterInput(),
      'group_name'          => new sfWidgetFormFilterInput(),
      'basionym_record_id'  => new sfWidgetFormFilterInput(),
      'order_by'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'referenced_relation' => new sfValidatorPass(array('required' => false)),
      'record_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'group_name'          => new sfValidatorPass(array('required' => false)),
      'basionym_record_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_by'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('classification_synonymies_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassificationSynonymies';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'referenced_relation' => 'Text',
      'record_id'           => 'Number',
      'group_id'            => 'Number',
      'group_name'          => 'Text',
      'basionym_record_id'  => 'Number',
      'order_by'            => 'Number',
    );
  }
}