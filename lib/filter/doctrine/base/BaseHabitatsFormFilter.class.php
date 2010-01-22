<?php

/**
 * Habitats filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseHabitatsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'code'                           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'code_indexed'                   => new sfWidgetFormFilterInput(),
      'description'                    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'description_ts'                 => new sfWidgetFormFilterInput(),
      'description_language_full_text' => new sfWidgetFormFilterInput(),
      'habitat_system'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
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

    $this->setupInheritance();

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
