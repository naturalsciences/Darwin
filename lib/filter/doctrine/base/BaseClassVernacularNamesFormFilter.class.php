<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * ClassVernacularNames filter form base class.
 *
 * @package    filters
 * @subpackage ClassVernacularNames *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseClassVernacularNamesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name' => new sfWidgetFormFilterInput(),
      'record_id'  => new sfWidgetFormFilterInput(),
      'community'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name' => new sfValidatorPass(array('required' => false)),
      'record_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'community'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('class_vernacular_names_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassVernacularNames';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'table_name' => 'Text',
      'record_id'  => 'Number',
      'community'  => 'Text',
    );
  }
}