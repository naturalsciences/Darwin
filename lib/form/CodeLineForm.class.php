<?php
class CodeLineForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['category'] = new sfWidgetFormChoice(array(
      'choices' => Codes::getCategories()
    ));

    $this->validatorSchema['category'] = new sfValidatorChoice(array(
      'required' => false,
      'choices' => array_keys(Codes::getCategories())
    ));

    $this->widgetSchema['code_part'] = new sfWidgetFormInput(array(),array('class'=> 'medium_small_size'));
    $this->widgetSchema['code_from'] = new sfWidgetFormInput(array(),array('class'=> 'lsmall_size'));
    $this->widgetSchema['code_to'] = new sfWidgetFormInput(array(),array('class'=> 'lsmall_size'));

    $this->validatorSchema['code_part'] = new sfValidatorString(array('required'=>false,'trim'=>true));
    $this->validatorSchema['code_from'] = new sfValidatorString(array('required'=>false,'trim'=>true));
    $this->validatorSchema['code_to'] = new sfValidatorString(array('required'=>false,'trim'=>true));
    $this->mergePostValidator(new CodesLineValidatorSchema());

  }
}