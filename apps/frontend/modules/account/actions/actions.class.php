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
  public function executeLogin($request)
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
  
  public function executeReload()
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
    $this->getUser()->setAuthenticated(false);
    $this->redirect('account/login');
  }
}
