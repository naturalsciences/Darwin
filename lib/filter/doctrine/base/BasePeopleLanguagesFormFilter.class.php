<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * PeopleLanguages filter form base class.
 *
 * @package    filters
 * @subpackage PeopleLanguages *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BasePeopleLanguagesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'people_ref'        => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => true)),
      'language_country'  => new sfWidgetFormFilterInput(),
      'mother'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'prefered_language' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'people_ref'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'People', 'column' => 'id')),
      'language_country'  => new sfValidatorPass(array('required' => false)),
      'mother'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'prefered_language' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('people_languages_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PeopleLanguages';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'people_ref'        => 'ForeignKey',
      'language_country'  => 'Text',
      'mother'            => 'Boolean',
      'prefered_language' => 'Boolean',
    );
  }
}