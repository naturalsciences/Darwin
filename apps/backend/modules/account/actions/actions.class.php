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
   * Send an e-mail for password renew
   */
  protected function sendPwdRenewMail($userParams)
  {
    $message = $this->getMailer()->compose();
    $message->setFrom(array(sfConfig::get('app_mailer_sender') => 'DaRWIN 2 team'));
    if(is_array($userParams))
    {
      if (isset($userParams['mail']) && isset($userParams['name']) && isset($userParams['physical']) && isset($userParams['user_id']) && isset($userParams['hash']))
      {
        if(!empty($userParams['mail']))
        {
          $message->setTo($userParams['mail']);
          $message->setSubject($this->getI18N()->__('DaRWIN 2 password renew'));
          $message->setBody($this->getPartial('pwdRenewMail', array('userParams'=>$userParams)),'text/plain');
          $this->getMailer()->send($message);
        }
      }
    }
  }

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
        try
        {
          $user = Doctrine::getTable('Users')->getUserByLoginAndEMail($this->form->getValue('user_name'), 
                                                                      $this->form->getValue('user_email')
                                                                    );
          
          $renewHash = hash('sha1', sfConfig::get('app_salt').$user->UsersLoginInfos[0]->getUserName());
          $user->UsersLoginInfos[0]->setRenewHash($renewHash);
          $user->UsersLoginInfos[0]->save();
          
          $userParams['user_id'] = $user->getId();
          $userParams['hash'] = $user->UsersLoginInfos[0]->getRenewHash();
          $userParams['name'] = $user->getFormatedName();
          $userParams['physical'] = $user->getIsPhysical();
          $userParams['title'] = $user->getTitle();
          $userParams['mail'] = $user->UsersComm[0]->getEntry();

          // send an email to the registered user
          $this->sendPwdRenewMail($userParams);
          
          $this->redirect('account/pwdRenewMail?'.http_build_query(array('name'=>$userParams['name'],
                                                                         'physical'=>$userParams['physical'],
                                                                         'title'=>$userParams['title']
                                                                        )
                                                                  )
                         );
          
        }
        catch(Doctrine_Exception $ne)
        {
          $e = new DarwinPgErrorParser($ne);
          $error = new sfValidatorError(new savedValidator(),$e->getMessage());
          $this->form->getErrorSchema()->addError($error);
        }
      }
    }
  }

  public function executePwdRenewMail(sfWebRequest $request)
  {
    $this->params = array('physical'=> $request->getParameter('physical', 'physical'),
                          'name' => $request->getParameter('name', ''),
                          'title' => $request->getParameter('title', '')
                         );
  }

  public function executeRenewPwd(sfWebRequest $request)
  {
    if(!$request->hasParameter('id') || !$request->hasParameter('hash'))
      $this->forwardToSecureAction();
    $userLogin = Doctrine::getTable('UsersLoginInfos')->findOneByUserRefAndRenewHash($request->getParameter('id'), $request->getParameter('hash'));
    if(!$userLogin)
      $this->forwardToSecureAction();
    $this->form = new RenewPwdForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('renew_pwd'));
      if ($this->form->isValid())
      {
        try
        {
          $userLogin->setPassword($this->form->getValue('new_password'));
          $userLogin->setRenewHash('');
          $userLogin->save();
          $this->redirect($this->getContext()->getConfiguration()->generatePublicUrl('homepage'));
        }
        catch(Doctrine_Exception $ne)
        {
          $e = new DarwinPgErrorParser($ne);
          $error = new sfValidatorError(new savedValidator(),$e->getMessage());
          $this->form->getErrorSchema()->addError($error);
        }
      }
    }
  }

}
