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
    $subForm = new sfForm();
    foreach ($this->options['collection'] as $index=>$record)
    {
      $val = new CollectionsRights();
	 $val->setUserRef($this->options['user_ref']) ;
	 $val->setCollectionRef($record->getId());
      $form = new SubCollectionsRightsForm($val,array('collection_ref' => $record->getId(),'old_rights' => $this->options['old_rights']));
      $subForm->embedForm($index, $form);
    }
    $this->embedForm('SubCollectionsRights',$subForm);
    $this->widgetSchema->setNameFormat('sub_collection[%s]');
  }
  
  public function save()
  {
	$value = $this->getValue('SubCollectionsRights');
	foreach($this->embeddedForms['SubCollectionsRights']->getEmbeddedForms() as $name => $form)
	{
	  if($value[$name]['check_right'])
	  {
	  	if(!$form->getWidget('check_right')->getAttribute('old_right')) //false ? so user don't have right on this collection yet
	  	{
		    $form->updateObject($value[$name]);
     	    $form->getObject()->save();	
	    	}
	    	//else nothing to do  	 
	  } 
	  else
	  {
	  	if($form->getWidget('check_right')->getAttribute('old_right')) //true ? so user has right but no for long
	  	{
		    $form->getObject()->deleteCollectionRight();
   		    unset($this->embeddedForms['SubCollectionsRights'][$name]);
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
