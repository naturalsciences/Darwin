<?php

/**
 * specimensearch actions.
 *
 * @package    darwin
 * @subpackage specimensearch
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class specimensearchActions extends DarwinActions
{
  protected $widgetCategory = 'specimensearch_widget';

  public function executeIndex(sfWebRequest $request)
  {

    // Initialization of the Search expedition form    
    $this->form = new SpecimenSearchFormFilter();      
    $this->fields = '' ; //eventualy we can create a function called getDefaultFieldsToShow(), where we can set witch fields we want to be shown 
    // if Parameter name exist, so the referer is mysavedsearch
    if ($request->getParameter('search_id','') != '')
    {
      $saved_search = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('search_id')) ;
      $criterias = unserialize($saved_search->getSearchCriterias());
      $this->fields = $saved_search->getVisibleFieldsInResult() ;
      Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'],$saved_search->getUserRef(),'specimensearch_widget');
      $this->form->bind($criterias['specimen_search_filters']) ;
    }    
    else $this->form->addGtuTagValue(0);    
    //loadwidget at the end because we possibliy need to update some widget visibility before showing it
    $this->loadWidgets();
  }

  /**
    * Action executed when searching a specimen - trigger by the click on the search button
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeSearchResult(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    //$this->forward404Unless($request->isMethod('post'));
    $this->setCommonValues('specimensearch', 'collection_name', $request);
    $this->form = new SpecimenSearchFormFilter();
    if($request->getParameter('specimen_search_filters','') !== '')
    {
      // Bind form with data contained in specimensearch array
      //  die(print_r($request->getParameter('specimen_search_filters')));
      $this->form->bind($request->getParameter('specimen_search_filters'));
      // Test that the form binded is still valid (no errors)
      if ($this->form->isValid())
      {
        // Define all properties that will be either used by the data query or by the pager
        // They take their values from the request. If not present, a default value is defined
        $query = $this->form->getQuery()->orderby($this->orderBy . ' ' . $this->orderDir);
        // Define in one line a pager Layout based on a pagerLayoutWithArrows object
        // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
        $this->pagerLayout = new PagerLayoutWithArrows(new Doctrine_Pager($query,
                                                                          $this->currentPage,
                                                                          $this->form->getValue('rec_per_page')
                                                                         ),
                                                       new Doctrine_Pager_Range_Sliding(array('chunk' => $this->pagerSlidingSize)),
                                                       $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
                                                      );
        // Sets the Pager Layout templates
        $this->setDefaultPaggingLayout($this->pagerLayout);
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->specimensearch = $this->pagerLayout->execute();
        $this->field_to_show = array('category' => 'uncheck','collection' => 'uncheck','taxon' => 'uncheck','type' => 'uncheck','gtu' => 'uncheck','chrono' => 'uncheck',
            'litho' => 'uncheck','lithologic' => 'uncheck','mineral' => 'uncheck','expedition' => 'uncheck','count' => 'uncheck');   
        if ($request->getParameter('fields_to_show') != '') 
        {
           $tabs = explode('|',$request->getParameter('fields_to_show')) ;
          // set the fields to show
          foreach ($tabs as $tab)
            $this->field_to_show[$tab] = 'check' ;
        }
        else
          $this->field_to_show = array('category' => 'check','collection' => 'check','taxon' => 'check','type' => 'check','gtu' => 'check','chrono' => 'uncheck',
            'litho' => 'uncheck','lithologic' => 'uncheck','mineral' => 'uncheck','expedition' => 'uncheck','count' => 'uncheck');      
      }
    }
  }
  
  public function executeSearch(sfWebRequest $request)
  {
    $this->executeSearchResult($request) ;      
    return $this->renderPartial('searchSuccess');
  }  

  public function executeIndividualTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable('SpecimenIndividuals')
      ->getIndividualBySpecimen($request->getParameter('id'));
  }
  
  public function executePartTree(sfWebRequest $request)
  {
    $this->parts = Doctrine::getTable('SpecimenParts')
      ->findForIndividual($request->getParameter('id'));
    $this->individual = $request->getParameter('id') ;
    $parts_ids = array();
    foreach($this->parts as $part)
      $parts_ids[] = $part->getId();

    $codes_collection = Doctrine::getTable('Codes')->getCodesRelatedArray($this->table, $parts_ids);
    $this->codes = array();
    foreach($codes_collection as $code)
    {
      if(! isset($this->codes[$code->getRecordId()]))
        $this->codes[$code->getRecordId()] = array();
      $this->codes[$code->getRecordId()][] = $code;
    }      
  }
  
  public function executeGtuTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable('gtu')
      ->findWithParents($request->getParameter('id'));
  }  
    
  public function executeAndSearch(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));

    $form = new SpecimenSearchFormFilter();
    $form->addGtuTagValue($number);
    return $this->renderPartial('andSearch',array('form' => $form['Tags'][$number]));
  }  
  
  public function executeSaveSearch(sfWebRequest $request)
  {
    $criterias = serialize($request->getPostParameters())  ;
    $saved_searches = new MySavedsearches() ;
    $saved_searches->setSearchCriterias($criterias) ;
    $saved_searches->setUserRef($this->getUser()->getId()) ;
    $saved_searches->setVisibleFieldsInResult('collection_name');
    $this->form = new MySavedSearchesForm($saved_searches) ;
    
  }
  
  public function executeProcessSave(sfWebRequest $request)
  {
    $this->form = new MySavedSearchesForm();    
    $this->form->bind($request->getParameter('my_saved_searches'));
    if ($this->form->isValid())
    {
      try{
        $this->form->save();
      	return $this->redirect('specimensearch/index');
      }
      catch(Doctrine_Exception $ne)
      {
        $e = new DarwinPgErrorParser($ne);
        return $this->renderText($e->getMessage());
      }      
    }
  }
  public function executeDeleteSavedSearch(sfWebRequest $request)
  {
    $r = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such item');
    try{
      $r->delete();
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $this->renderText($e->getMessage());
    }
    return $this->renderText("ok");
    //$this->redirect('@homepage');
  }  
}
