<?php

/**
 * account actions.
 *
 * @package    darwin
 * @subpackage account
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class accountActions extends DarwinActions
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
    if ($request->hasParameter('l_err') && $request->getParameter('l_err','0') == '1')
    {
      $error = new sfValidatorError(new savedValidator(),'Bad login or password');
      $this->form->getErrorSchema()->addError($error);
    }
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('login'));
      if ($this->form->isValid())
      {
        $this->getUser()->setAuthenticated(true);
        sfContext::getInstance()->getLogger()->debug('LOGIN: '.$this->form->getValue('username').' '.$this->form->user->getId() );
        $this->getUser()->setAttribute('db_user_id',$this->form->user->getId());
        $this->getUser()->setAttribute('db_user_type',$this->form->user->getDbUserType());
        $lang = Doctrine::getTable("UsersLanguages")->getPreferredLanguage($this->form->user->getId());
        if($lang) //prevent from crashing if lang is not set
        {
            $this->getUser()->setCulture($lang->getLanguageCountry());
        }
        else
          $this->getUser()->setCulture('en') ;
        $this->redirect('@homepage');
      }
    }
  }

  public function executeLogout(sfWebRequest $request)
  {
    $referer = $this->getRequest()->getReferer();
    $this->getUser()->getAttributeHolder()->clear();  
    $this->getUser()->clearCredentials();
    $this->getUser()->setAuthenticated(false);
    if(!$referer)
      $this->redirect('@homepage');
    else
      $this->redirect($referer);
  }

  public function executeLostPwd(sfWebRequest $request)
  {
    $this->form = new LostPwdForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('lost_pwd'));
      if ($this->form->isValid())
      {
        $user = Doctrine::getTable('Users')->getUserByLoginAndEMail($this->form->getValue('user_name'), 
                                                                    $this->form->getValue('user_email')
                                                                   );
        
      }
    }
  }

}
