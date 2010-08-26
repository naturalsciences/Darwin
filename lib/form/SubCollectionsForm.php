<?php

/**
 * Collections form.
 *
 * @package    form
 * @subpackage Collections
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SubCollectionsForm extends sfForm
{
  public function configure()
  {
    $this->widgetSchema['CollectionsRights'] = new sfWidgetCollectionList(array('choices' => array()));
    if(isset($this->options['collection_ref'])) 
    {
      $this->widgetSchema['CollectionsRights']->addOption('collection_parent',$this->options['collection_ref']) ;    
      $this->widgetSchema['CollectionsRights']->addOption('old_right',$this->options['old_right']) ;
    }
    $this->validatorSchema['CollectionsRights'] = new sfValidatorPass();    
    $this->widgetSchema->setNameFormat('sub_collection[%s]');
  }
  
  public function save()
  {
	  $value = $this->getValue('CollectionsRights'); // checked by user values
	  if(!$value) $value = array() ;
    $old_right = $this->getWidget('CollectionsRights')->getOption('old_right'); // old checked values
	  if(!$old_right) $old_right = array() ;    
    $obj_to_save = array('collection_ref'=>'','user_ref' => $this->user) ;
	  foreach($this->getWidget('CollectionsRights')->getChoices() as $key => $form)
	  {
	    if(in_array($key,$value)) // then this collection is checked 
	    {
	    	if(!in_array($key,$old_right)) //false ? so it's a new right
	    	{
		        $collectionRights = new CollectionsRights() ;
		        $obj_to_save['collection_ref'] = $key ;
		        $collectionRights->fromArray($obj_to_save);
       	    $collectionRights->save();	
	      }
	      //else nothing to do  	 
	    } 
	    else
	    {
	    	if(in_array($key,$old_right)) //true ? so user has right but not for long
	    	{
	    	  $this->collectionRights = new CollectionsRights() ;	
	    	  $obj_to_save['collection_ref'] = $key ;  	  
	    	  $this->collectionRights->fromArray($obj_to_save);
		      $this->collectionRights->deleteCollectionRight();
	       }
	      	//else nothing to do  
	    }
    }
  }
  public function getJavascripts()
  {
    $javascripts=parent::getJavascripts();
    $javascripts[]='/js/qtiped_forms.js';
    return $javascripts; 
  }
}
