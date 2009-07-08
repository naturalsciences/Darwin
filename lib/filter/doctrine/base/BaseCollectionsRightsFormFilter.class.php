<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CollectionsRights filter form base class.
 *
 * @package    filters
 * @subpackage CollectionsRights *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCollectionsRightsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'collection_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'user_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => true)),
      'rights'         => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'collection_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Collections', 'column' => 'id')),
      'user_ref'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Users', 'column' => 'id')),
      'rights'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('collections_rights_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsRights';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'collection_ref' => 'ForeignKey',
      'user_ref'       => 'ForeignKey',
      'rights'         => 'Number',
    );
  }
}