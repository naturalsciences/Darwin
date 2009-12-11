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
    $this->property = null;
    if($request->hasParameter('rid'))
    {
      $this->property = Doctrine::getTable('CatalogueProperties')->find($request->getParameter('rid'));
    }

    if(! $this->property)
    {
     $this->property = new CatalogueProperties();
     $this->property->setRecordId($request->getParameter('id'));
     $this->property->setReferencedRelation($request->getParameter('table'));
    }
    $this->form = new CataloguePropertiesForm($this->property);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('catalogue_properties'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	    $this->form->getObject()->refreshRelated();
	    $this->form = new CataloguePropertiesForm($this->form->getObject());
	    $this->message = 'Your property was saved';
	  }
	  catch(Exception $e)
	  {
            $error = new sfValidatorError(new savedValidator(),$e->getMessage());
            $this->form->getErrorSchema()->addError($error); 
	  }
	}
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $r = Doctrine::getTable('CatalogueProperties')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such Property');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  public function executeAddValue(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $prop = null;

    if($request->hasParameter('id') && $request->getParameter('id'))
      $prop = Doctrine::getTable('CatalogueProperties')->find($request->getParameter('id') );

    $form = new CataloguePropertiesForm($prop);
    $form->addValue($number);
    return $this->renderPartial('prop_value',array('form' => $form['newVal'][$number]));
  }

}
