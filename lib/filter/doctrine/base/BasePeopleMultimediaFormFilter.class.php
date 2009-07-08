<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PeopleMultimedia filter form base class.
 *
 * @package    filters
 * @subpackage PeopleMultimedia *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePeopleMultimediaFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'person_user_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
      'object_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
      'category'        => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'person_user_ref' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
      'object_ref'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Multimedia', 'column' => 'id')),
      'category'        => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_multimedia_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleMultimedia';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'person_user_ref' => 'ForeignKey',
      'object_ref'      => 'ForeignKey',
      'category'        => 'Text',
    );
  }
}