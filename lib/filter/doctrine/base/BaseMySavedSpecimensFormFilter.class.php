<?php

/**
 * MySavedSpecimens filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMySavedSpecimensFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'specimen_ids'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'favorite'               => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'modification_date_time' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'specimen_ids'           => new sfValidatorPass(array('required' => false)),
      'favorite'               => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'modification_date_time' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_saved_specimens_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSpecimens';
  }

  public function getFields()
  {
    return array(
      'user_ref'               => 'Number',
      'name'                   => 'Text',
      'specimen_ids'           => 'Text',
      'favorite'               => 'Boolean',
      'modification_date_time' => 'Text',
    );
  }
}
