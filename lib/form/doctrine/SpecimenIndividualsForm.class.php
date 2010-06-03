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
    $this->widgetSchema['type'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctTypes',
        'method' => 'getType',
        'key_method' => 'getType',
        'add_empty' => false,
        'change_label' => 'Pick a type in the list',
        'add_label' => 'Add an other type',
    ));
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
    $this->widgetSchema['stage'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctStages',
        'method' => 'getStage',
        'key_method' => 'getStage',
        'add_empty' => false,
        'change_label' => 'Pick a stage in the list',
        'add_label' => 'Add an other stage',
    ));
    $this->widgetSchema['social_status'] = new widgetFormSelectComplete(array(
        'model' => 'SpecimenIndividuals',
        'table_method' => 'getDistinctSocialStatuses',
        'method' => 'getSocialStatus',
        'key_method' => 'getSocialStatus',
        'add_empty' => false,
        'change_label' => 'Pick a social status in the list',
        'add_label' => 'Add an other social status',
    ));
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

    $this->validatorSchema['accuracy'] = new sfValidatorChoice(array(
        'choices' => array(0,1),
        'required' => false,
        ));
    $this->setDefault('accuracy', 1);

  }
}