<?php

/**
 * search actions.
 *
 * @package    darwin
 * @subpackage search
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class registerActions extends DarwinActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new RegisterForm();
    $this->form->addLoginInfos(0);
    $this->form->addComm(0);
    $this->form->addLanguages(0);

    // If the search has been triggered by clicking on the search button or with pinned specimens
    if(($request->isMethod('post') && $request->getParameter('users','') !== '' ))
    {
      // Store all post parameters
      $criterias = $request->getPostParameters();
      $this->form->bind($criterias['users']) ;
    }
    if($this->form->isBound())
    {
      if ($this->form->isValid())
      {
        try
        {
          $this->user = $this->form->save();
          print_r($this->user->toArray());
//           $this->user->addUserWidgets();
        }
        catch(Doctrine_Exception $ne)
        {
          $e = new DarwinPgErrorParser($ne);
          $error = new sfValidatorError(new savedValidator(),$e->getMessage());
          $this->form->getErrorSchema()->addError($error);
        }
      }
    }
    $this->setTemplate('index');
  }
}