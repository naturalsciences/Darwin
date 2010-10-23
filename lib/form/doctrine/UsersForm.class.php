<?php

/**
 * Users form.
 *
 * @package    form
 * @subpackage Users
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class UsersForm extends BaseUsersForm
{
  public function configure()
  {
    if ($this->options['mode'] == 'new') 
    {
      $this->useFields(array('is_physical','sub_type','title','family_name','given_name','additional_names','gender')) ;
      $this->widgetSchema['sub_type'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                   'table_method' => 'getDistinctSubType',
                                                                   'method' => 'getSubType',
                                                                   'key_method' => 'getSubType',
                                                                   'add_empty' => true,
                                                                   'change_label' => 'Pick a sub type in the list',
                                                                   'add_label' => 'Add another sub type',
                                                                          )
                                                                    );  
      $this->widgetSchema['title'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                        'table_method' => 'getDistinctTitle',
                                                                        'method' => 'getTitle',
                                                                        'key_method' => 'getTitle',
                                                                        'add_empty' => true,
                                                                        'change_label' => 'Pick a title in the list',
                                                                        'add_label' => 'Add another title',
                                                                         )
                                                                   );  
      $this->widgetSchema['is_physical'] = new sfWidgetFormInputCheckbox(array ('default' => 'true')); 
      $this->widgetSchema['gender'] = new sfWidgetFormChoice(array('choices' => array('M' => 'M', 'F' => 'F'))) ;
      $this->validatorSchema['gender'] = new sfValidatorChoice(array('choices' => array('M' => 'M', 'F' => 'F'), 'required' => false)); 
      $this->widgetSchema['title']->setAttributes(array('class'=>'small_size')) ;     
      $this->validatorSchema['title'] =  new sfValidatorString(array('required' => false));                                                    
      $this->validatorSchema['sub_type'] =  new sfValidatorString(array('required' => false));                                                                          
    }
    elseif($this->options['is_physical'])
    {
      $this->useFields(array('title','family_name','given_name','additional_names','gender','people_id')) ;      
      $this->widgetSchema['people_id'] = new widgetFormButtonRef(array('model' => 'People',
                                                                'method' => 'getFormatedName',
                                                                'link_url' => 'people/choose?with_js=1',
                                                                'nullable' => true,
                                                                'box_title' => $this->getI18N()->__('Choose Yourself'),
                                                                      )
                                                                );   
      $this->widgetSchema['title'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                        'table_method' => 'getDistinctTitle',
                                                                        'method' => 'getTitle',
                                                                        'key_method' => 'getTitle',
                                                                        'add_empty' => true,
                                                                        'change_label' => 'Pick a title in the list',
                                                                        'add_label' => 'Add another title',
                                                                         )
                                                                   );       
      $this->widgetSchema['title']->setAttributes(array('class'=>'small_size')) ;     
      $this->validatorSchema['title'] =  new sfValidatorString(array('required' => false)); 
      $this->validatorSchema['people_id'] = new sfValidatorInteger(array('required' => false)) ;                                                                
    }
    else
    {
      $this->useFields(array('sub_type','family_name','given_name','additional_names','people_id')) ;
      $this->widgetSchema['sub_type'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                   'table_method' => 'getDistinctSubType',
                                                                   'method' => 'getSubType',
                                                                   'key_method' => 'getSubType',
                                                                   'add_empty' => true,
                                                                   'change_label' => 'Pick a sub type in the list',
                                                                   'add_label' => 'Add another sub type',
                                                                          )
                                                                    );
      $this->widgetSchema['people_id'] = new widgetFormButtonRef(array('model' => 'People',
                                                                'method' => 'getFormatedName',
                                                                'link_url' => 'institution/choose',
                                                                'nullable' => true,
                                                                'box_title' => $this->getI18N()->__('Choose Yourself'),
                                                                      )
                                                                );   
      $this->validatorSchema['sub_type'] =  new sfValidatorString(array('required' => false));
      $this->validatorSchema['people_id'] = new sfValidatorInteger(array('required' => false)) ;
    }
                                         
    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name']->setAttributes(array('class'=>'medium_size'));    
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name']->setAttributes(array('class'=>'medium_size'));

    $this->widgetSchema['additional_names'] = new sfWidgetFormInput();
    $this->widgetSchema['additional_names']->setAttributes(array('class'=>'medium_size'));
     
    /*$this->widgetSchema['password']  = new sfWidgetFormInputPassword();
    $this->widgetSchema['password_again']  = new sfWidgetFormInputPassword();
    $this->validatorSchema['password']  = new sfValidatorString(array('required' => false, 'trim' => true, 'min_length' => 5));
    $this->validatorSchema['password']->setMessage('min_length','this password is too short (%min_length% characters min).');
    $this->validatorSchema['password_again']  = new sfValidatorString(array('required' => false));*/
    
/*    $this->validatorSchema->setPostValidator(
                                  new sfValidatorSchemaCompare('password', '==', 'password_again',
	                                array(),
                                	array('invalid' => 'Passwords must be equals and can not contains spaces')
                                                              )
                                            );*/
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(date('Y').'/12/31');
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['birth_date'] = new widgetFormJQueryFuzzyDate(
      array('culture'=> $this->getCurrentCulture(), 
            'image'=> '/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ),                                      
      array('class' => 'from_date')                
    );

    $this->validatorSchema['birth_date'] = new fuzzyDateValidator(
      array(
        'required' => false,                       
        'from_date' => true,                       
        'min' => $minDate,                         
        'max' => $maxDate,
        'empty_value' => $dateLowerBound,
      ),
      array('invalid' => 'Date provided is not valid')
    );
  }
}
