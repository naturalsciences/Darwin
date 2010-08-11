<?php

/**
 * MySavedSearches form.
 *
 * @package    form
 * @subpackage MySavedSearches
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MySavedSearchesForm extends BaseMySavedSearchesForm
{
  public function configure()
  {

    $this->widgetSchema['search_criterias'] = new sfWidgetFormInputHidden() ;
    $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden() ;
    $this->widgetSchema['name'] = new sfWidgetFormInputText() ;
    $this->widgetSchema['name']->setAttribute('class','medium_size');      
    $this->widgetSchema['favorite']->setAttribute('class','hidden');    
    $this->widgetSchema['modification_date_time'] = new sfWidgetFormInputText() ;    
    $default_name ="My search on ".date('Y/m/d H:i:s') ;

    if($this->getObject()->isNew())
      $this->widgetSchema['modification_date_time']->setDefault($this->getI18N('Not Saved Yet'));
    else
    $this->widgetSchema['modification_date_time']->setAttribute('class','medium_size');    
    $this->widgetSchema['modification_date_time']->setAttribute('disabled','disabled');
    
    if($this->getObject()->getName() == "")
      $this->widgetSchema['name']->setDefault($default_name) ;

    $choices = Doctrine::getTable('MySavedSearches')->getAllFields() ;
    $this->widgetSchema['visible_fields_in_result'] = new sfWidgetFormChoice(array(
	  'choices' => $choices, 
	  'expanded' => true,
	  'multiple' => true,
	  'renderer_options' => array('formatter' => array($this, 'formatter'))     
    ));
    

    $this->validatorSchema['visible_fields_in_result'] = new sfValidatorChoice(array('choices' => $choices,'multiple' => true));
//    $this->validatorSchema['visible_fields_in_result']->cleanMultiple($choices) ;
    $this->validatorSchema['name'] = new sfValidatorString() ;
    $this->validatorSchema['modification_date_time'] = new sfValidatorString(array('required' => false)) ;
    $this->validatorSchema['user_ref'] = new sfValidatorString(array('required' => false)) ;
  }
  
  public function formatter($widget, $inputs)
  {
    $rows = array();
    foreach ($inputs as $i => $input)
    {
      $rows[] = $widget->renderContentTag(
            'tr',
      	    '<td>'.$input['label'].'</td><td>'.$input['input'].'</td>'
           );
    }
 
    return $widget->renderContentTag('tbody', implode($widget->getOption('separator'), $rows));
  }
  
  public function save($con = null)
  {
    $values = $this->getValues();
    $this->getObject()->fromArray($values) ;
    $string_fields = implode('|',$values['visible_fields_in_result']) ;
    $this->getObject()->setModificationDateTime(date('Y/m/d H:i:s'));
    $this->getObject()->setVisibleFieldsInResult($string_fields) ;
    $this->getObject()->save();
  }
  
  
}
