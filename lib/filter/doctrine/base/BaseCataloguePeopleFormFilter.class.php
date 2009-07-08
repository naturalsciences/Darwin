<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CataloguePeople filter form base class.
 *
 * @package    filters
 * @subpackage CataloguePeople *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCataloguePeopleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'      => new sfWidgetFormFilterInput(),
      'record_id'       => new sfWidgetFormFilterInput(),
      'people_type'     => new sfWidgetFormFilterInput(),
      'people_sub_type' => new sfWidgetFormFilterInput(),
      'order_by'        => new sfWidgetFormFilterInput(),
      'people_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'table_name'      => new sfValidatorPass(array('required' => false)),
      'record_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'people_type'     => new sfValidatorPass(array('required' => false)),
      'people_sub_type' => new sfValidatorPass(array('required' => false)),
      'order_by'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'people_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('catalogue_people_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CataloguePeople';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'table_name'      => 'Text',
      'record_id'       => 'Number',
      'people_type'     => 'Text',
      'people_sub_type' => 'Text',
      'order_by'        => 'Number',
      'people_ref'      => 'ForeignKey',
    );
  }
}