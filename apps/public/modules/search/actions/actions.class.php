<?php

/**
 * search actions.
 *
 * @package    darwin
 * @subpackage search
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends DarwinActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new PublicSearchFormFilter();
    $this->form->addGtuTagValue(0);    
  }

  public function executeAndSearch(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));

    $form = new PublicSearchFormFilter();
    $form->addGtuTagValue($number);
    return $this->renderPartial('andSearch',array('form' => $form['Tags'][$number], 'row_line'=>$number));
  } 
  
  public function executePurposeTag(sfWebRequest $request)
  {
    $this->tags = Doctrine::getTable('TagGroups')->getPropositions($request->getParameter('value'), '', 'country');
  }
  
  public function executeTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )
      ->findWithParents($request->getParameter('id'));
  }
   
  public function executeSearch(sfWebRequest $request)
  {
    // Initialize the order by and paging values: order by collection_name here
    $this->setCommonValues('search', 'collection_name', $request);
    // Modify the s_url to call the searchResult action when on result page and playing with pager
    $this->s_url = 'search/searchResult?back=1' ;  
    $this->form = new PublicSearchFormFilter();
    // If the search has been triggered by clicking on the search button or with pinned specimens
    if(($request->isMethod('post') && $request->getParameter('specimen_search_filters','') !== '' ))
    {
      // Store all post parameters
      $criterias = $request->getPostParameters(); 
      $this->form->bind($criterias['specimen_search_filters']) ;    
    }
    if($this->form->isBound())
    {
      if ($this->form->isValid())
      {        
        if($request->hasParameter('criteria'))
        {
          $this->setTemplate('index');
          return;
        }
        else
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
            $this->search = $this->pagerLayout->execute();
          $this->field_to_show = $this->getVisibleColumns($this->form);

          $this->common_names = Doctrine::getTable('ClassVernacularNames')->findAllCommonNames() ;
          return;
        } 
      }
    }
    $this->setTemplate('index'); 
    $this->form->addGtuTagValue(0);        
  }   
  
  public function executeSearchResult(sfWebRequest $request)
  {
    // Do the same as a executeSearch...
    $this->executeSearch($request) ;
    // ... and render partial searchSuccess
    return $this->renderPartial('searchSuccess');
  } 

  public function executeView(sfWebRequest $request)
  {
    $this->specimen = Doctrine::getTable('specimenSearch')->findOneBySpecRef($request->getParameter('id'));  
    
    $this->institute = Doctrine::getTable('People')->findOneById($this->specimen->getCollectionInstitutionRef()) ;
    $this->manager = Doctrine::getTable('UsersComm')->fetchByUser($this->specimen->getCollectionMainManagerRef());      
    $this->common_names = Doctrine::getTable('ClassVernacularNames')->findAllCommonNames() ;    
    if ($tag = $this->specimen->getGtuCountryTagValue()) $this->tags = explode(';',$tag) ; 
    else $this->tags = false ;
  }
    
  /**
  * Compute different sources to get the columns that must be showed
  * 1) from form request 2) from session 3) from default value
  * @param sfForm $form The SpecimenSearch form with the 'fields' field defined
  * @return array of fields with check or uncheck or a list of visible fields separated by |
  */
  private function getVisibleColumns(sfForm $form)
  {
    $flds = array('collection','taxon','type','gtu','codes','chrono','taxon_common_name', 'chrono_common_name',
              'litho_common_name','lithology_common_name','mineral_common_name',
              'litho','lithology','mineral','sex','stage');
    $flds = array_fill_keys($flds, 'uncheck');

    if($form->isBound())
    {
      $req_fields = $form->getValue('col_fields');
      $req_fields_array = explode('|',$req_fields);

    }

    if(empty($req_fields_array))
      $req_fields_array = explode('|', $form->getDefault('col_fields'));
    foreach($req_fields_array as $k => $val)
    {
      $flds[$val] = 'check';
    }
    return $flds;
  } 
  
  /**
  * Return tags for a GTU without the country part
  */
  public function executeCompleteTag(sfWebRequest $request)
  {
    if($request->hasParameter('id') && $request->getParameter('id'))
      $gtu = Doctrine::getTable('Gtu')->findExcept($request->getParameter('id') );
    $this->forward404Unless($gtu);
    $str = "" ;
    foreach($gtu->TagGroups as $group)
    {
      if ($group->getSubGroupName() == 'country')
      {
        $str .= '<ul class="country_tags">';
        $tags = explode(";",$group->getTagValue());
        foreach($tags as $value)
          if (strlen($value))
            $str .=  '<li>' . trim($value).'</li>';
        $str .= '</ul><div class="clear" />';
      }
    }
    return $this->renderText($str); 
  }   
}
