<?php

/**
 * VernacularNames form.
 *
 * @package    form
 * @subpackage VernacularNames
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensCodesForm extends BaseSpecimensCodesForm
{
  public function configure()
  {
    $this->useFields(array('id', 'referenced_relation', 'record_id', 'code_category', 'code_prefix', 'code', 'code_suffix'));
    $choices = array('main'=> 'Main', 'secondary' => 'Secondary', 'temporary' => 'Temporary') ;
    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();    
    $this->widgetSchema['code_category'] = new sfWidgetFormChoice(array(
        'choices' => $choices
      ));
    $this->validatorSchema['code_category'] = new sfValidatorChoice(array('required' => true, 'choices'=>array_keys($choices)));
    $this->widgetSchema['code_prefix'] = new sfWidgetFormInput();
    $this->widgetSchema['code_prefix']->setAttributes(array('class'=>'small_size'));
    $this->validatorSchema['code_prefix'] = new sfValidatorString(array('required' => false));
    $this->widgetSchema['code'] = new sfWidgetFormInput();
    $this->widgetSchema['code']->setAttributes(array('class'=>'small_size'));
    $this->validatorSchema['code'] = new sfValidatorString(array('required' => false));
    $this->widgetSchema['code_suffix'] = new sfWidgetFormInput();
    $this->widgetSchema['code_suffix']->setAttributes(array('class'=>'small_size'));
    $this->validatorSchema['code_suffix'] = new sfValidatorString(array('required' => false));
    $this->mergePostValidator(new SpecimensCodesValidatorSchema());
  }
}