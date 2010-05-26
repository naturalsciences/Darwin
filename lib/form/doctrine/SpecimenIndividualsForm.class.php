<?php

/**
 * SpecimenIndividuals form.
 *
 * @package    form
 * @subpackage SpecimenIndividuals
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimenIndividualsForm extends BaseSpecimenIndividualsForm
{
  public function configure()
  { 
    $this->widgetSchema['specimen_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['type'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctTypes',
        'method' => 'getType',
        'key_method' => 'getType',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['sex'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSexes',
        'method' => 'getSex',
        'key_method' => 'getSex',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['state'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStates',
        'method' => 'getState',
        'key_method' => 'getState',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['stage'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStages',
        'method' => 'getStage',
        'key_method' => 'getStage',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['social_status'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSocialStatuses',
        'method' => 'getSocialStatus',
        'key_method' => 'getSocialStatus',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
    $this->widgetSchema['rock_form'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctRockForms',
        'method' => 'getRockForm',
        'key_method' => 'getRockForm',
        'add_empty' => false,
        'change_label' => '',
        'add_label' => '',
    ));
  }
}