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

    // If the search has been triggered by clicking on the search button or with pinned specimens
    if(($request->isMethod('post') && $request->getParameter('users','') !== '' ))
    {
      // Store all post parameters
      $criterias = $request->getPostParameters(); 
      $this->form->bind($criterias['specimen_search_filters']) ;    
    }
    if($this->form->isBound())
    {
      if ($this->form->isValid())
      {        
      }
    }
    $this->setTemplate('index'); 
  }
}