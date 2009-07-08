<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Habitats filter form base class.
 *
 * @package    filters
 * @subpackage Habitats *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseHabitatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                           => new sfWidgetFormFilterInput(),
      'path'                           => new sfWidgetFormFilterInput(),
      'code'                           => new sfWidgetFormFilterInput(),
      'code_indexed'                   => new sfWidgetFormFilterInput(),
      'description'                    => new sfWidgetFormFilterInput(),
      'description_ts'                 => new sfWidgetFormFilterInput(),
      'description_language_full_text' => new sfWidgetFormFilterInput(),
      'habitat_system'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                           => new sfValidatorPass(array('required' => false)),
      'path'                           => new sfValidatorPass(array('required' => false)),
      'code'                           => new sfValidatorPass(array('required' => false)),
      'code_indexed'                   => new sfValidatorPass(array('required' => false)),
      'description'                    => new sfValidatorPass(array('required' => false)),
      'description_ts'                 => new sfValidatorPass(array('required' => false)),
      'description_language_full_text' => new sfValidatorPass(array('required' => false)),
      'habitat_system'                 => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('habitats_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Habitats';
  }

  public function getFields()
  {
    return array(
      'id'                             => 'Number',
      'name'                           => 'Text',
      'path'                           => 'Text',
      'code'                           => 'Text',
      'code_indexed'                   => 'Text',
      'description'                    => 'Text',
      'description_ts'                 => 'Text',
      'description_language_full_text' => 'Text',
      'habitat_system'                 => 'Text',
    );
  }
}