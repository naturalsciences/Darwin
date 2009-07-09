<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MySavedSearches filter form base class.
 *
 * @package    filters
 * @subpackage MySavedSearches *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMySavedSearchesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'search_criterias'         => new sfWidgetFormFilterInput(),
      'favorite'                 => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'modification_date_time'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'visible_fields_in_result' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'search_criterias'         => new sfValidatorPass(array('required' => false)),
      'favorite'                 => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'modification_date_time'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'visible_fields_in_result' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_saved_searches_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

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
      'modification_date_time'   => 'Date',
      'visible_fields_in_result' => 'Text',
    );
  }
}