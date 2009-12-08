<?php

/**
 * property actions.
 *
 * @package    darwin
 * @subpackage property
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class propertyActions extends sfActions
{
  public function executeAdd(sfWebRequest $request)
  {
     $this->property = new CatalogueProperties();
     $this->property->setRecordId($request->getParameter('id'));
     $this->property->setReferencedRelation($request->getParameter('table'));
     $this->form = new CataloguePropertiesForm($this->property);
  }

  public function executeAddValue(sfWebRequest $request)
  {
    $this->form = new PropertiesValuesForm();
  }
  
  public function executeSave(sfWebRequest $request)
  {
    /*if($request->hasParameter('cid'))
      $this->comment =  Doctrine::getTable('Comments')->find($request->getParameter('cid'));
    else
    {
     $this->property = new CatalogueProperties();
     $this->property->setRecordId($request->getParameter('id'));
     $this->property->setReferencedRelation($request->getParameter('table'));
    }*/
     
    $this->property = new CatalogueProperties();
    $this->form = new CataloguePropertiesForm($this->property);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('catalogue_properties'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	    return $this->renderText( 'oook ');//$this->form->getObject()->getId());
	  }
	  catch(Exception $e)
	  {
            $error = new sfValidatorError(new savedValidator(),$e->getMessage());
            $this->form->getErrorSchema()->addError($error); 
	  }
	}
//         return $this->renderText($this->form->__toString());
    }
  //$this->setLayout('default');
    $this->setTemplate('add');
  }

}
