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
  protected function getWidgets()
  {
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('people_widget');
    if(! $this->widgets) $this->widgets=array();
  }
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new PeopleFormFilter();
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->form = new PeopleFormFilter();
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));



    if($request->getParameter('people_filters','') !== '')
    {
      $this->form->bind($request->getParameter('people_filters'));

      if ($this->form->isValid())
      {
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        $this->currentPage = ($request->getParameter('page', '') == '')? 1: $request->getParameter('page');
        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager(
	    $this->form->getQuery(),
	    $this->currentPage,
	    $this->form->getValue('rec_per_page')
	  ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $pagerSlidingSize)
	    ),
	  $this->getController()->genUrl('people/search?is_choose='.$this->is_choose.'&page=').'{%page_number}'
	);

        // Sets the Pager Layout templates
        $this->pagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
        $this->pagerLayout->setSelectedTemplate('<li>{%page}</li>');
        $this->pagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->items = $this->pagerLayout->execute();
      }
    }
   
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PeopleForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PeopleForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($people = Doctrine::getTable('People')->find(array($request->getParameter('id'))), sprintf('people does not exist (%s).', $request->getParameter('id')));
    $this->form = new PeopleForm($people);
    $this->getWidgets();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($people = Doctrine::getTable('People')->find(array($request->getParameter('id'))), sprintf('people does not exist (%s).', $request->getParameter('id')));
    $this->form = new PeopleForm($people);

    $this->processForm($request, $this->form);
    $this->getWidgets();
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($people = Doctrine::getTable('People')->find(array($request->getParameter('id'))), sprintf('people does not exist (%s).', $request->getParameter('id')));
    $people->delete();

    $this->redirect('people/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $people = $form->save();

      $this->redirect('people/edit?id='.$people->getId());
    }
  }




  /***
  * Screen for people and institutions widgets
  **/
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
