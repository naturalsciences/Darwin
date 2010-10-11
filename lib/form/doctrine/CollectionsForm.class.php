<?php

/**
 * Collections form.
 *
 * @package    form
 * @subpackage Collections
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CollectionsForm extends BaseCollectionsForm
{
  public function configure()
  {
    unset($this['path'],
          $this['code_auto_increment'],
          $this['code_last_value'],
          $this['code_prefix'],
          $this['code_prefix_separator'],
          $this['code_suffix'],
          $this['code_suffix_separator'],
          $this['code_part_code_auto_copy']
         );
    $this->widgetSchema['is_public'] = new sfWidgetFormInputCheckbox(array ('default' => 'true'), array('title' => 'checked = public'));          
    $this->validatorSchema['is_public'] = new sfValidatorBoolean() ;
    $this->widgetSchema['code'] = new sfWidgetFormInputText();
    $this->widgetSchema['code']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema['name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['institution_ref'] = new widgetFormButtonRef(array(
       'model' => 'Institutions',
       'link_url' => 'institution/choose?with_js=1',
       'method' => 'getFamilyName',
       'box_title' => $this->getI18N()->__('Choose Institution'),
     ));
    $this->widgetSchema['institution_ref']->setLabel('Institution');

    $this->widgetSchema['main_manager_ref'] = new widgetFormButtonRef(array(
       'model' => 'Users',
       'link_url' => 'user/choose',
       'method' => 'getFormatedName',
       'box_title' => $this->getI18N()->__('Choose Manager'),
     ));
    $this->widgetSchema['main_manager_ref']->setLabel('Main manager');

    $this->widgetSchema['parent_ref'] = new sfWidgetFormChoice(array(
      'choices' =>  array(),
    ));
    $this->widgetSchema['parent_ref']->setLabel('Parent collection');


    $this->validatorSchema['collection_type'] = new sfValidatorChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'), 'required' => true));

   if(! $this->getObject()->isNew() || isset($this->options['duplicate']))
     $this->widgetSchema['parent_ref']->setOption('choices', Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($this->getObject()->getInstitutionRef()) );
   elseif(isset($this->options['new_with_error']))
     $this->widgetSchema['parent_ref']->setOption('choices', Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($this->options['institution']));   	

     $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'checkSelfAttached')))
     );

    $subForm = new sfForm();
    $this->embedForm('CollectionsRights',$subForm);   
    foreach(Doctrine::getTable('CollectionsRights')->getAllUserRef($this->getObject()->getId()) as $key=>$vals)
    {
      $form = new CollectionsRightsForm($vals);
      $this->embeddedForms['CollectionsRights']->embedForm($key, $form);
    }
    //Re-embedding the container
    $this->embedForm('CollectionsRights', $this->embeddedForms['CollectionsRights']); 
    
//    $this->embedRelation('CollectionsRights');
    $subForm = new sfForm();
    $this->embedForm('newVal',$subForm);
    
    $subForm = new sfForm();
    $this->embedForm('CollectionsAdmin',$subForm);   
    foreach(Doctrine::getTable('CollectionsAdmin')->getCollectionAdmin($this->getObject()->getId()) as $key=>$vals)
    {
      $form = new CollectionsAdminForm($vals);
      $this->embeddedForms['CollectionsAdmin']->embedForm($key, $form);
    }
    //Re-embedding the container    
    $this->embedForm('CollectionsAdmin', $this->embeddedForms['CollectionsAdmin']); 
    
//    $this->embedRelation('CollectionsRights');
    $subForm = new sfForm();
    $this->embedForm('newAdmin',$subForm);  
  }
  
  public function addValue($num,$user_id,$rights)
  {
    $val = ($rights == "encoder"?new CollectionsRights():new CollectionsAdmin()) ;
    $val->Collections = $this->getObject();      
    $val->setUserRef($user_id) ;
    if($rights == "encoder") 
    {
      $form = new CollectionsRightsForm($val,array('user_id'=>$user_id));  
      $this->embeddedForms['newVal']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newVal', $this->embeddedForms['newVal']);
    }
    else
    {
      $form = new CollectionsAdminForm($val,array('user_id'=>$user_id));  
      $this->embeddedForms['newAdmin']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newAdmin', $this->embeddedForms['newAdmin']);    
    }
  }

  public function checkSelfAttached($validator, $values)
  {
    if(! empty($values['id']) )
    {
      if($values['parent_ref'] == $values['id'])
      {
	      $error = new sfValidatorError($validator, "A collection can't be attached to itself");
        throw new sfValidatorErrorSchema($validator, array('parent_ref' => $error));
      }
    }
    return $values;
  }
  
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['newVal']))
    {
		  foreach($taintedValues['newVal'] as $key=>$newVal)
		  {
		    if (!isset($this['newVal'][$key]))
		    {
		      $this->addValue($key,$newVal['user_ref'],'encoder');
		    }
		  }
    }
    if(isset($taintedValues['newAdmin']))
    {
		  foreach($taintedValues['newAdmin'] as $key=>$newVal)
		  {
		    if (!isset($this['newAdmin'][$key]))
		    {
		      $this->addValue($key,$newVal['user_ref'],'admin');
		    }
		  }
    }    
    parent::bind($taintedValues, $taintedFiles);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
   if (null === $forms)
   {
	    $value = $this->getValue('CollectionsRights');
	    foreach($this->embeddedForms['CollectionsRights']->getEmbeddedForms() as $name => $form)
    	{
    	  if (!isset($value[$name]['user_ref']))
	      {
	        $form->getObject()->delete();
	        unset($this->embeddedForms['CollectionsRights'][$name]);
	      } 
	    }	
	    $value = $this->getValue('CollectionsAdmin');
	    foreach($this->embeddedForms['CollectionsAdmin']->getEmbeddedForms() as $name => $form)
    	{
    	  if (!isset($value[$name]['user_ref']))
	      {
	        $form->getObject()->delete();
	        unset($this->embeddedForms['CollectionsAdmin'][$name]);
	      } 
	    }
	    $value = $this->getValue('newAdmin') ;
      foreach($this->embeddedForms['newAdmin']->getEmbeddedForms() as $name => $form)
      {
        if($value[$name]['user_ref'] == $this->getValue('main_manager_ref'))
	        unset($this->embeddedForms['newAdmin'][$name]);
      }	    
   }
   return parent::saveEmbeddedForms($con, $forms);
  }
}
