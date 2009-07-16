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
    $this->form = new LoginForm();
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('login'));
      if ($this->form->isValid())
      {
        sfContext::getInstance()->getLogger()->debug($this->form->getValue('username'));
        $this->getUser()->setAttribute('db_user',$this->form->user);
        $this->getUser()->setAuthenticated(true);
        $lang = Doctrine::getTable("UsersLanguages")->getPreferedLanguage($this->form->user->getId());
        $this->getUser()->setCulture($lang->getLanguageCountry());
        $this->redirect('board/index');
      }
    }
  }

  public function executeLogout()
  {
    $this->getUser()->setAuthenticated(false);
    $this->redirect('account/login');
  }
}
