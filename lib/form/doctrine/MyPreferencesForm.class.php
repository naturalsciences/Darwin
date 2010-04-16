<?php

/**
 * MyPreferences form.
 *
 * @package    form
 * @subpackage MyPreferences
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MyPreferencesForm extends BaseMyPreferencesForm
{
  public function configure()
  {
  	unset ($this['title'], 
  		  $this['color'], 
  		  $this['icon_ref'], 
  		  $this['is_available'], 
  		  $this['visible'], 
  		  $this['opened'] 		  
     ) ;
  	$w = $this->getObject() ;
  	if ($this->options['level'] > 4)
	  	$choices = array('unused'=> '', 'is_available' => '', 'visible' => '', 'opened' => '') ;
	else
		$choices = array('is_available' => '', 'visible' => '', 'opened' => '') ;
	$this->widgetSchema['widget_choice'] = new sfWidgetFormChoice(array(
	     'choices' => $choices, 
	     'expanded' => true,
	     'renderer_options' => array('formatter' => array($this, 'formatter'))     
));
  $this->widgetSchema['mandatory'] = new sfWidgetFormInputHidden() ;
  $this->widgetSchema['title_perso'] = new sfWidgetFormInputText() ;
  $this->validatorSchema['widget_choice'] = new sfValidatorChoice(array('choices' => array_keys($choices),'required' => false));
  $this->validatorSchema['mandatory'] = new sfValidatorChoice(array('choices' => array(true => true, false => false), 'required' => false));
  $this->setDefault('widget_choice',$w->getWidgetField());
  $this->widgetSchema['widget_choice']->setLabel($w->getGroupName()) ;
  }
  
  
  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $i => $input)
    {
      $rows[] = $widget->renderContentTag(
   	  'td', 
             $input['input'].$widget->getOption('label_separator').$input['label'],
		   array('class' => 'widget_selection')
           );
    }
 
    return $widget->renderContentTag('ul', implode($widget->getOption('separator'), $rows), array('class' => 'radio_list'));
  }
  
  
  public function updateObject($values = null)
  {
  	if ($values['title_perso'] == '') $values['title_perso'] = $values['group_name'] ;
  	if ($values['mandatory']) $value['widget_choice'] = 'opened' ;
	parent::updateObject($values) ;
  }
  
}
