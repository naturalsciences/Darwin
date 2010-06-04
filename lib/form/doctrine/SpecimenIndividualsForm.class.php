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
    unset($this['type_group'], $this['type_search']);
    $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['specimen_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['type_widget'] = new sfWidgetFormInputHidden(array('default'=>0));
    $this->widgetSchema['type'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctTypes',
        'method' => 'getType',
        'key_method' => 'getType',
        'add_empty' => false,
        'change_label' => 'Pick a type in the list',
        'add_label' => 'Add an other type',
    ));
    $this->widgetSchema['sex_widget'] = new sfWidgetFormInputHidden(array('default'=>0));
    $this->widgetSchema['sex'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSexes',
        'method' => 'getSex',
        'key_method' => 'getSex',
        'add_empty' => false,
        'change_label' => 'Pick a sex in the list',
        'add_label' => 'Add an other sex',
    ));
    $this->widgetSchema['state'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStates',
        'method' => 'getState',
        'key_method' => 'getState',
        'add_empty' => false,
        'change_label' => 'Pick a "sexual" state in the list',
        'add_label' => 'Add an other "sexual" state',
    ));
    $this->widgetSchema['stage_widget'] = new sfWidgetFormInputHidden(array('default'=>0));
    $this->widgetSchema['stage'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStages',
        'method' => 'getStage',
        'key_method' => 'getStage',
        'add_empty' => false,
        'change_label' => 'Pick a stage in the list',
        'add_label' => 'Add an other stage',
    ));
    $this->widgetSchema['social_status_widget'] = new sfWidgetFormInputHidden(array('default'=>0));
    $this->widgetSchema['social_status'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSocialStatuses',
        'method' => 'getSocialStatus',
        'key_method' => 'getSocialStatus',
        'add_empty' => false,
        'change_label' => 'Pick a social status in the list',
        'add_label' => 'Add an other social status',
    ));
    $this->widgetSchema['rock_form_widget'] = new sfWidgetFormInputHidden(array('default'=>0));
    $this->widgetSchema['rock_form'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctRockForms',
        'method' => 'getRockForm',
        'key_method' => 'getRockForm',
        'add_empty' => false,
        'change_label' => 'Pick a rock form in the list',
        'add_label' => 'Add an other rock form',
    ));

    $this->widgetSchema['accuracy'] = new sfWidgetFormChoice(array(
        'choices'  => array($this->getI18N()->__('exact'), $this->getI18N()->__('imprecise')),
        'expanded' => true,
    ));

    $this->setDefault('accuracy', 1);

    /* Validators */

    $this->validatorSchema['id'] = new sfValidatorInteger(array('required'=>false));
    $this->validatorSchema['specimen_ref'] = new sfValidatorInteger(array('required'=>false));
    $this->validatorSchema['type_widget'] = new sfValidatorPass();
    $this->validatorSchema['type'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('type')));
    $this->validatorSchema['sex_widget'] = new sfValidatorPass();
    $this->validatorSchema['sex'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('sex')));
    $this->validatorSchema['stage_widget'] = new sfValidatorPass();
    $this->validatorSchema['stage'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('stage')));
    $this->validatorSchema['state'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('state')));
    $this->validatorSchema['social_status_widget'] = new sfValidatorPass();
    $this->validatorSchema['social_status'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('social_status')));
    $this->validatorSchema['rock_form_widget'] = new sfValidatorPass();
    $this->validatorSchema['rock_form'] = new sfValidatorString(array('trim'=>true, 'required'=>false, 'empty_value'=>$this->getDefault('rock_form')));
    $this->validatorSchema['accuracy'] = new sfValidatorChoice(array(
        'choices' => array(0,1),
        'required' => false,
        ));
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(!isset($taintedValues['type_widget']))
    {
      $this->offsetUnset('type');
      unset($taintedValues['type']);
    }
    if(!isset($taintedValues['sex_widget']))
    {
      $this->offsetUnset('sex');
      unset($taintedValues['sex']);
      $this->offsetUnset('state');
      unset($taintedValues['state']);
    }
    if(!isset($taintedValues['stage_widget']))
    {
      $this->offsetUnset('stage');
      unset($taintedValues['stage']);
    }
    if(!isset($taintedValues['social_status_widget']))
    {
      $this->offsetUnset('social_status');
      unset($taintedValues['social_status']);
    }
    if(!isset($taintedValues['rock_form_widget']))
    {
      $this->offsetUnset('rock_form');
      unset($taintedValues['rock_form']);
    }
    if(!isset($taintedValues['accuracy']))
    {
      $this->offsetUnset('specimen_individuals_count_min');
      unset($taintedValues['specimen_individuals_count_min']);
      $this->offsetUnset('specimen_individuals_count_max');
      unset($taintedValues['specimen_individuals_count_max']);
    }
    parent::bind($taintedValues, $taintedFiles);
  }
}