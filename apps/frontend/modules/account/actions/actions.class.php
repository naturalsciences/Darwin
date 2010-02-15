<?php

/**
 * account actions.
 *
 * @package    darwin
 * @subpackage account
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class accountActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
    $this->redirectIf($this->getUser()->isAuthenticated(),'@homepage');
    $this->form = new LoginForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('login'));
      if ($this->form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        sfContext::getInstance()->getLogger()->debug('LOGIN: '.$this->form->getValue('username').' '.$this->form->user->getId() );
        $this->getUser()->setAttribute('db_user_id',$this->form->user->getId());
        $lang = Doctrine::getTable("UsersLanguages")->getPreferredLanguage($this->form->user->getId());
        if($lang) //prevent from crashing if lang is set
        {
            $this->getUser()->setCulture($lang->getLanguageCountry());
        }
        $referer = $this->getRequest()->getReferer();
        $this->redirect($referer ? $referer : '@homepage');
      }
    }
  }
  
  public function executeProfile(sfWebRequest $request)
  {
    $this->user =  Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id') );
    $this->forward404Unless($this->user);


    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('users_widget');
  
    $this->form = new UsersForm($this->user);
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('users'));
      if($this->form->isValid())
      {
	$this->form->save();
	if($this->form->getValue('password') != '')
	{
	    $login_infos = $this->user->UsersLoginInfos;
	    if( count($login_infos) != 1)
	    {
	      print 'Ouups'; exit; // @TODO: replace this by a proper way
	    }
	    $login_infos[0]->sDebug();
            $login_infos[0]->setPassword(sha1(sfConfig::get('app_salt').$this->form->getValue('password')));
	    $login_infos[0]->save();
	}
        return $this->redirect('account/profile');
      }
    }
  }

  public function executeReload(sfWebRequest $request)
  {
    /**
    **********************************
    * WARNING TESTING PURPOSE ONLY!!!
    * @todo: remove this!
    **********************************/
    $u = Doctrine::getTable("UsersLoginInfos")->findOneByUserName('root');
    if($this->getUser()->isAuthenticated() && $u && $u->getUserRef() == $this->getUser()->getAttribute('db_user_id') )
    {
      DarwinTestFunctional::initiateDB();
      Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');
    }
    /**
    ********************************
    * WARING END OF TESTING DATA
    * @todo: remove this!
    ********************************/

    $this->getUser()->setAuthenticated(false);
    $this->redirect('account/login');
  }

  public function executeLogout()
  {
    $this->getUser()->clearCredentials();
    $this->getUser()->setAuthenticated(false);
    $this->redirect('account/login');
  }



 public function executeAddress(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->address =  Doctrine::getTable('UsersAddresses')->find($request->getParameter('id'));
    else
    {
     $this->address = new UsersAddresses();
     $this->address->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersAddressesForm($this->address);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_addresses'));
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


  public function executeGetTags(sfWebRequest $request)
  {
    $this->array_possible = Doctrine::getTable('UsersComm')->getTags($request->getParameter('type'));
  }

  public function executeComm(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->comm =  Doctrine::getTable('UsersComm')->find($request->getParameter('id'));
    else
    {
     $this->comm = new UsersComm();
     $this->comm->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersCommForm($this->comm);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_comm'));
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

  public function executeLang(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->lang =  Doctrine::getTable('UsersLanguages')->find($request->getParameter('id'));
    else
    {
     $this->lang = new UsersLanguages();
     $this->lang->setPeopleRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersLanguagesForm($this->lang);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_languages'));
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
