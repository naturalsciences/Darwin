<?php

/**
 * savesearch actions.
 *
 * @package    darwin
 * @subpackage savesearch
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class savesearchActions extends sfActions
{

  public function executeSaveSearch(sfWebRequest $request)
  {
    if($request->getParameter('id'))
    {
      $saved_searches = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('id'), $this->getUser()->getId());
    }
    else
    {
      $criterias = serialize($request->getPostParameters());

      $saved_searches = new MySavedsearches() ;
      $saved_searches->setSearchCriterias($criterias) ;
    }
    $saved_searches->setUserRef($this->getUser()->getId()) ;
    
    //$saved_searches->setVisibleFieldsInResult('collection_name');
    $this->form = new MySavedSearchesForm($saved_searches);

    if($request->getParameter('my_saved_searches') != '')
    {
      $this->form->bind($request->getParameter('my_saved_searches'));

      if ($this->form->isValid())
      {
        try{
          $this->form->save();
          return $this->renderText('ok');
        }
        catch(Doctrine_Exception $ne)
        {
          $e = new DarwinPgErrorParser($ne);
          return $this->renderText($e->getMessage());
        }
      }
    }
  }

  public function executeDeleteSavedSearch(sfWebRequest $request)
  {
    $r = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such item');
    try{
      $r->delete();
      if(! $request->isXmlHttpRequest())
        return $this->redirect('savesearch/index');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $this->renderText($e->getMessage());
    }
    return $this->renderText("ok");
    //$this->redirect('@homepage');
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->searches = Doctrine::getTable('MySavedSearches')->addUserOrder(null,$this->getUser()->getId())->execute();
  }
}
