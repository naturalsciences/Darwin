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
    * It's also the same action that is used to open a saved search reopened, a list of pinned specimens
    * or when clicking on the back to criterias button
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeSearch(sfWebRequest $request)
  {
    $this->is_specimen_search = false;
    // Initialize the order by and paging values: order by collection_name here
    $this->setCommonValues('specimensearch', 'collection_name', $request);
    // Modify the s_url to call the searchResult action when on result page and playing with pager
    $this->s_url = 'specimensearch/searchResult'.'?is_choose='.$this->is_choose;
    // Initialize filter
    $this->form = new SpecimenSearchFormFilter();
    // If the search has been triggered by clicking on the search button or with pinned specimens
    if(($request->isMethod('post') && $request->getParameter('specimen_search_filters','') !== '' ) || 
       $request->hasParameter('pinned') 
      )
    {
      // Store all post parameters
      $criterias = $request->getPostParameters();
      // If pinned specimens called
      if($request->hasParameter('pinned'))
      {
        // Get all ids pinned
        $ids = implode(',',$this->getUser()->getAllPinned() );
        if($ids == '')
          $ids = '0';
        $this->is_pinned_only_search=true;
        // Set the list of ids as criteria
        $criterias['specimen_search_filters']['spec_ids'] = $ids;
        // and the list of visible fields from the list of default visible fields defined in form configuration
        $criterias['specimen_search_filters']['col_fields'] = $this->form->getDefault('col_fields');
      }
      // If instead it's a call to a stored specimen search
      elseif($request->hasParameter('spec_search'))
      {
        // Get the saved search concerned
        $saved_search = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('spec_search'), $this->getUser()->getId());
        // Forward 404 if we don't get the search requested
        $this->forward404Unless($saved_search);

        $criterias['specimen_search_filters']['spec_ids'] = $saved_search->getSearchedIdString();
        if($criterias['specimen_search_filters']['spec_ids'] == '')
          $criterias['specimen_search_filters']['spec_ids'] = '0';
        $this->is_specimen_search = $saved_search->getId();
      }
      $this->form->bind($criterias['specimen_search_filters']) ;
    }
    // If search_id parameter is given it means we try to open an already saved search with its criterias
    elseif($request->getParameter('search_id','') != '')
    {
      // Get the saved search asked
      $saved_search = Doctrine::getTable('MySavedSearches')->getSavedSearchByKey($request->getParameter('search_id'), $this->getUser()->getId()) ;
      // If not available, not found -> forward on 404 page
      $this->forward404Unless($saved_search);

      if($saved_search->getisOnlyId())
        $this->is_specimen_search = $saved_search->getId();
      // Get all search criterias from DB
      $criterias = unserialize($saved_search->getSearchCriterias());
      // Transform all visible fields stored as a string with | as separator and store it into col_fields field
      $criterias['specimen_search_filters']['col_fields'] = implode('|',$saved_search->getVisibleFieldsInResult()) ;
      // If data were set, in other terms specimen_search_filters array is available...
      if(isset($criterias['specimen_search_filters']))
      {
        // Bring all the required/necessary widgets on page
        Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
        if($saved_search->getisOnlyId() && $criterias['specimen_search_filters']['spec_ids']=='')
          $criterias['specimen_search_filters']['spec_ids'] = '0';
        $this->form->bind($criterias['specimen_search_filters']) ;
      }
    }

    if($this->form->isBound())
    {
      if ($this->form->isValid())
      {
        $this->getUser()->storeRecPerPage( $this->form->getValue('rec_per_page'));
        // When criteria parameter is given, it means we go back to criterias
        if($request->hasParameter('criteria'))
        {
          $this->setTemplate('index');
          // Bring all the required/necessary widgets on page
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
          $ordered_searched = 'spec_ref, ';
          
          if($this->form->getValue('what_searched') == 'individual')
            $ordered_searched = ' individual_ref , ';
          $query = $this->form->getQuery()->orderby($this->orderBy . ' ' . $this->orderDir);

          if($this->form->getValue('what_searched') != 'part')
          {
            $query->orderby($ordered_searched. $this->orderBy . ' ' . $this->orderDir);
            $query->addGroupBy($this->orderBy);
          }
          // Define in one line a pager Layout based on a pagerLayoutWithArrows object
          // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
          $pager = new Doctrine_Pager($query,
            $this->currentPage,
            $this->form->getValue('rec_per_page')
          );
          $count_q = clone $query;//$pager->getCountQuery();
          $count_q = $count_q->select('count( distinct spec_ref)')->removeDqlQueryPart('groupby')->removeDqlQueryPart('orderby');
          if($this->form->getValue('what_searched') == 'individual')
            $count_q->select('count( distinct individual_ref)');
          $counted = new DoctrineCounted();
          $counted->count_query = $count_q;
          $pager->setCountQuery($counted);
          $this->pagerLayout = new PagerLayoutWithArrows($pager,
                                                        new Doctrine_Pager_Range_Sliding(array('chunk' => $this->pagerSlidingSize)),
                                                        $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
                                                        );
          // Sets the Pager Layout templates
          $this->setDefaultPaggingLayout($this->pagerLayout);
          // If pager not yet executed, this means the query has to be executed for data loading
          if (! $this->pagerLayout->getPager()->getExecuted())
            $this->specimensearch = $this->pagerLayout->execute();
          $spec_list = array();
          foreach($this->specimensearch as $key=>$specimen)
            $spec_list[] = $specimen->getSpecRef() ;
          $this->codes = Doctrine::getTable('Codes')->getCodesRelatedArray('specimens',$spec_list) ;
          $this->field_to_show = $this->getVisibleColumns($this->getUser(), $this->form);

          return;
        }
      }
    }

    $this->setTemplate('index');
    if(isset($criterias['specimen_search_filters']))
      Doctrine::getTable('SpecimenSearch')->getRequiredWidget($criterias['specimen_search_filters'], $this->getUser()->getId(), 'specimensearch_widget');
    $this->loadWidgets();
//    $this->form->addGtuTagValue(0); 
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
    $flds = array('category','collection','taxon','type','gtu','codes','chrono',
              'litho','lithologic','mineral','expedition','count','individual_type','sex','state','stage','social_status','rock_form','individual_count');
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

  /**
  * This search is ajaxly called to render only the results -> i.e. triggered from a pager action
  * @param sfWebRequest $request the request passed
  * @return A partial with the results
  */
  public function executeSearchResult(sfWebRequest $request)
  {
    // Do the same as a executeSearch...
    $this->executeSearch($request) ;
    // ... and render partial searchSuccess
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
    return $this->renderPartial('andSearch',array('form' => $form['Tags'][$number], 'row_line'=>$number));
  }  

  public function executeAddCode(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));

    $form = new SpecimenSearchFormFilter();
    $form->addCodeValue($number);
    return $this->renderPartial('specimensearchwidget/codeline',array('code' => $form['Codes'][$number], 'row_line'=>$number));
  }
}
