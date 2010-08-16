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

  /**
   * Save the state of each col (visible / hidden ) in session 
   *
  */
  public function executeSaveCol(sfWebRequest $request)
  {
    $columns = explode('|',$request->getParameter('cols',''));
    $this->getUser()->storeVisibleCols($columns);
    return $this->renderText('ok');
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SpecimenSearchFormFilter();

    $this->form->setDefault('col_fields', $this->getVisibleColumns($this->getUser(), $this->form, true));
    $this->form->setDefault('rec_per_page',$this->getUser()->fetchRecPerPage());

    // if Parameter name exist, so the referer is mysavedsearch
    if ($request->getParameter('search_id','') != '')
    {
      $saved_search = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('search_id'), $this->getUser()->getId()) ;
      $criterias = unserialize($saved_search->getSearchCriterias());

      $this->fields = $saved_search->getVisibleFieldsInResultStr();
      Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
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
  public function executeSearch(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    //$this->forward404Unless($request->isMethod('post'));
    $this->setCommonValues('specimensearch', 'collection_name', $request);
    $this->s_url = 'specimensearch/searchResult'.'?is_choose='.$this->is_choose;
    $this->form = new SpecimenSearchFormFilter();

    if( ($request->isMethod('post') && $request->getParameter('specimen_search_filters','') !== '' ) || $request->hasParameter('pinned') )
    {
      $criterias = $request->getPostParameters();
      if($request->hasParameter('pinned'))
      {
        $ids=implode(',',$this->getUser()->getAllPinned() );
        $criterias['specimen_search_filters']['spec_ids'] = $ids;
      }
      $this->form->bind($criterias['specimen_search_filters']) ;
    }
    elseif($request->getParameter('search_id','') != '')
    {
      $saved_search = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('search_id'), $this->getUser()->getId()) ;

      $this->forward404Unless($saved_search);

      $criterias = unserialize($saved_search->getSearchCriterias());
      $criterias['specimen_search_filters']['col_fields'] = implode('|',$saved_search->getVisibleFieldsInResult()) ;
      if(isset($criterias['specimen_search_filters']))
      {
        Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
        $this->form->bind($criterias['specimen_search_filters']) ;
      }
    }

    if($this->form->isBound())
    {
      if ($this->form->isValid())
      {
        $this->getUser()->storeRecPerPage( $this->form->getValue('rec_per_page'));
        if($request->hasParameter('criteria'))
        {
          $this->setTemplate('index');
          Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
          $this->loadWidgets();
          return;
        }
        else
        {
          $q = Doctrine::getTable('MySavedSearches')
            ->addUserOrder(null, $this->getUser()->getId());
          $this->spec_lists = Doctrine::getTable('MySavedSearches')
            ->addIsSearch($q, false)
            ->execute();


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

          $this->field_to_show = $this->getVisibleColumns($this->getUser(), $this->form);

          return;
        }
      }
    }

    $this->setTemplate('index');
    if(isset($criterias['specimen_search_filters']))
      Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
    $this->loadWidgets();
  }
  
  /**
  * Compute different sources to get the columns that must be showed
  * 1) from form request 2) from session 3) from default value
  * @param sfBasicSecurityUser $user the user
  * @param sfForm $form The SpecimenSearch form with the 'fields' field defined
  * @param bool $as_string specify if you want the return to be a string (concat of visible cols)
  * @return array of fields with check or uncheck or a list of visible fields separated by |
  */
  private function getVisibleColumns(sfBasicSecurityUser $user, sfForm $form, $as_string = false)
  {
    $flds = array('category','collection','taxon','type','gtu','chrono',
              'litho','lithologic','mineral','expedition','count');
    $flds = array_fill_keys($flds, 'uncheck');

    if($form->isBound())
    {
      $req_fields = $form->getValue('col_fields');
      $req_fields_array = explode('|',$req_fields);

    }
    else
    {
      $req_fields_array = $user->fetchVisibleCols();
    }

    if(empty($req_fields_array))
      $req_fields_array = explode('|', $form->getDefault('col_fields'));
    if($as_string)
    {
      return  implode('|',$req_fields_array);
    }

    foreach($req_fields_array as $k => $val)
    {
      $flds[$val] = 'check';
    }
    return $flds;
  }

  public function executeSearchResult(sfWebRequest $request)
  {
    $this->executeSearch($request) ;      
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
}
