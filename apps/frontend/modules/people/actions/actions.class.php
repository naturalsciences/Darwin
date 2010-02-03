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

  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new PeopleFormFilter();
  }

  public function executeSearchBoth(sfWebRequest $request)
  {}
  
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
	$this->orderBy = ($request->getParameter('orderby', '') == '') ? 'family_name' : $request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '') ? 'asc' : $request->getParameter('orderdir');
	$query = $this->form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        $this->currentPage = ($request->getParameter('page', '') == '')? 1: $request->getParameter('page');
	$this->s_url = 'people/search?&page='.$this->currentPage.'&is_choose='.$this->is_choose;

        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager(
	    $query,
	    $this->currentPage,
	    $this->form->getValue('rec_per_page')
	  ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $pagerSlidingSize)
	    ),
	  $this->getController()->genUrl($this->s_url.'&orderby='.$this->orderBy.'&orderdir='.$this->orderDir).'/page/{%page_number}'
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

  public function executeDetails(sfWebRequest $request)
  {
    $this->item = Doctrine::getTable('People')->find($request->getParameter('id'));
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


  public function executeGetTags(sfWebRequest $request)
  {
    $this->array_possible = Doctrine::getTable('PeopleComm')->getTags($request->getParameter('type'));
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


  public function executeLang(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->lang =  Doctrine::getTable('PeopleLanguages')->find($request->getParameter('id'));
    else
    {
     $this->lang = new PeopleLanguages();
     $this->lang->setPeopleRef($request->getParameter('ref_id'));
    }
     
    $this->form = new PeopleLanguagesForm($this->lang);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('people_languages'));
	if($this->form->isValid())
	{
	  try {
	    $this->form->save();
	    return $this->renderText('ok');
	  }
	  catch(Doctrine_Exception $e)
	  {
	    $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	    $this->form->getErrorSchema()->addError($error); 
	  }
	}
    }
  }

  public function executeDeleteLang(sfWebRequest $request)
  {
    $r = Doctrine::getTable('PeopleLanguages')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such language');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }


  public function executeDeleteRelation(sfWebRequest $request)
  {
    $r = Doctrine::getTable('PeopleRelationships')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such PeopleRelation');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  public function executeRelation(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->relation =  Doctrine::getTable('PeopleRelationships')->find($request->getParameter('id'));
    else
    {
     $this->relation = new PeopleRelationships();
     $this->relation->setPerson2Ref($request->getParameter('ref_id'));
    }
     
    $this->form = new PeopleRelationshipsForm($this->relation);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('people_relationships'));
	if($this->form->isValid())
	{
	  try {
	    $this->form->save();
	    return $this->renderText('ok');
	  }
	  catch(Doctrine_Exception $e)
	  {
	    $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	    $this->form->getErrorSchema()->addError($error); 
	  }
	}
    }
  }
}
