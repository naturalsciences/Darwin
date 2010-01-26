<?php

/**
 * people actions.
 *
 * @package    darwin
 * @subpackage people
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class peopleActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeSearch(sfWebRequest $request)
  {
  }

  public function executeComplete(sfWebRequest $request)
  {
    $this->people = Doctrine::getTable('People')->searchPysical($request->getParameter('name',''));
  }

  public function executeAddress(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->address =  Doctrine::getTable('PeopleAddresses')->find($request->getParameter('id'));
    else
    {
     $this->address = new PeopleAddresses();
     $this->address->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new PeopleAddressesForm($this->address);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('people_addresses'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	  }
	  catch(Exception $e)
	  {
	    return $this->renderText($e->getMessage());
	  }
	  return $this->renderText('ok');
	}
    }
  }

  public function executeDeleteAddress(sfWebRequest $request)
  {
    $r = Doctrine::getTable('PeopleAddresses')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such address');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  public function executeComm(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->comm =  Doctrine::getTable('PeopleComm')->find($request->getParameter('id'));
    else
    {
     $this->comm = new PeopleComm();
     $this->comm->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new PeopleCommForm($this->comm);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('people_comm'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	  }
	  catch(Exception $e)
	  {
	    return $this->renderText($e->getMessage());
	  }
	  return $this->renderText('ok');
	}
    }
  }

  public function executeDeleteComm(sfWebRequest $request)
  {
    $r = Doctrine::getTable('PeopleComm')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such commucation mean');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }
}
