<?php

/**
 * MySavedSearches filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseMySavedSearchesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'search_criterias'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'favorite'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'modification_date_time'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'visible_fields_in_result' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'search_criterias'         => new sfValidatorPass(array('required' => false)),
      'favorite'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'modification_date_time'   => new sfValidatorPass(array('required' => false)),
      'visible_fields_in_result' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_saved_searches_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MySavedSearches';
  }

  public function getFields()
  {
    return array(
      'user_ref'                 => 'Number',
      'name'                     => 'Text',
      'search_criterias'         => 'Text',
      'favorite'                 => 'Boolean',
      'modification_date_time'   => 'Text',
      'visible_fields_in_result' => 'Text',
    );
  }
}
