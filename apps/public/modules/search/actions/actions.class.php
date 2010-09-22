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
            $query->orderby($this->orderBy . ' ' . $this->orderDir . ', spec_ref ');
          $query->groupBy($this->orderBy . ', spec_ref ');
          // Define in one line a pager Layout based on a pagerLayoutWithArrows object
          // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
          $pager = new Doctrine_Pager($query,
            $this->currentPage,
            $this->form->getValue('rec_per_page')
          );
          // Replace the count query triggered by the Pager to get the number of records retrieved
          $count_q = clone $query;//$pager->getCountQuery();
          // Remove from query the group by and order by clauses
          $count_q = $count_q->select('count( distinct spec_ref)')->removeDqlQueryPart('groupby')->removeDqlQueryPart('orderby');
          //$count_q->select('count( distinct individual_ref)');
          // Initialize an empty count query
          $counted = new DoctrineCounted();
          // Define the correct select count() of the count query
          $counted->count_query = $count_q;
          // And replace the one of the pager with this new one
          $pager->setCountQuery($counted);         
          
          // Define in one line a pager Layout based on a pagerLayoutWithArrows object
          // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
          $this->pagerLayout = new PagerLayoutWithArrows($pager,
                                                        new Doctrine_Pager_Range_Sliding(array('chunk' => $this->pagerSlidingSize)),
                                                        $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
                                                        );
          // Sets the Pager Layout templates
          $this->setDefaultPaggingLayout($this->pagerLayout);
          // If pager not yet executed, this means the query has to be executed for data loading
          if (! $this->pagerLayout->getPager()->getExecuted())
            $this->search = $this->pagerLayout->execute();
          $this->field_to_show = $this->getVisibleColumns($this->form);
          $this->defineFields();
          $ids = $this->FecthIdForCommonNames() ;
          $this->common_names = Doctrine::getTable('ClassVernacularNames')->findAllCommonNames($ids) ;                    
          if(!count($this->common_names))
            $this->common_names = array('taxonomy'=> array(), 'chronostratigraphy' => array(), 'lithostratigraphy' => array(), 
                                        'lithology' => array(),'mineralogy' => array()) ;
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
    $this->forward404Unless($this->specimen);
    if(!$this->specimen->getCollectionIsPublic()) $this->forwardToSecureAction();
    
    $this->institute = Doctrine::getTable('People')->findOneById($this->specimen->getCollectionInstitutionRef()) ;
    $this->manager = Doctrine::getTable('UsersComm')->fetchByUser($this->specimen->getCollectionMainManagerRef());      
    $ids = $this->FecthIdForCommonNames() ;
    $this->common_names = Doctrine::getTable('ClassVernacularNames')->findAllCommonNames($ids) ;    
    
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
    $flds = array('category','collection','taxon','type','gtu','codes','chrono','taxon_common_name', 'chrono_common_name',
              'litho_common_name','lithologic_common_name','mineral_common_name', 'expedition', 'count', 'individual_type',
              'litho','lithologic','mineral','sex','state','stage','social_status','rock_form','individual_count');
    $flds = array_fill_keys($flds, 'uncheck');

    if($form->isBound())
    {
      $req_fields = $form->getValue('col_fields');
      if($form->getValue('taxon_common_name') != '' || $form->getValue('taxon_name') != '') $req_fields .= '|taxon|taxon_common_name';
      if($form->getValue('chrono_common_name') != '' || $form->getValue('chrono_name') != '') $req_fields .= '|chrono|chrono_common_name';      
      if($form->getValue('litho_common_name') != '' || $form->getValue('litho_name') != '') $req_fields .= '|litho|litho_common_name';
      if($form->getValue('lithologic_common_name') != '' || $form->getValue('lithologic_name') != '') $req_fields .= '|lithologic|lithologic_common_name'; 
      if($form->getValue('mineral_common_name') != '' || $form->getValue('mineral_name') != '') $req_fields .= '|mineral|mineral_common_name';    
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
   
  protected function defineFields()
  {
    $this->columns= array('individual'=>array());
    $this->columns['specimen'] = array(
      'category' => array(
        'category',
        $this->getI18N()->__('Category'),),
      'collection' => array(
        'collection_name',
        $this->getI18N()->__('Collection'),),
      'taxon' => array(
        'taxon_name_order_by',
        $this->getI18N()->__('Taxon'),),
      'type' => array(
        'with_types',
        $this->getI18N()->__('Type'),),
      'gtu' => array( ///
        false,
        $this->getI18N()->__('Country'),),
      'codes' => array( ///
        false,
        $this->getI18N()->__('Codes'),),
      'chrono' => array(
        'chrono_name_order_by',
        $this->getI18N()->__('Chronostratigraphic unit'),),
      'litho' => array(
        'litho_name_order_by',
        $this->getI18N()->__('Lithostratigraphic unit'),),
      'lithologic' => array(
        'lithologic_name_order_by',
        $this->getI18N()->__('Lithologic unit'),),
      'mineral' => array(
        'mineral_name_order_by',
        $this->getI18N()->__('Mineralogic unit'),),
      'expedition' => array(
        'expedition_name_indexed',
        $this->getI18N()->__('Expedition'),),
      'count' => array(
        'specimen_count_max',
        $this->getI18N()->__('Count'),),
    );

    $this->columns['individual'] = array(
      'taxon_common_name' => array(
        false,
        $this->getI18N()->__('Taxon common name'),),      
      'chrono_common_name' => array(
        false,
        $this->getI18N()->__('Chrono common name'),),
      'litho_common_name' => array(
        false,
        $this->getI18N()->__('Litho common name'),),
      'lithologic_common_name' => array(
        false,
        $this->getI18N()->__('Lithologic common name'),),
      'mineral_common_name' => array(
        false,
        $this->getI18N()->__('Mineral common name'),),      
      'individual_type' => array(
        'individual_type_group',
        $this->getI18N()->__('Type'),),        
      'sex' => array(
        'individual_sex',
        $this->getI18N()->__('Sex'),),
      'state' => array(
        'individual_state',
        $this->getI18N()->__('State'),),
      'stage' => array(
        'individual_stage',
        $this->getI18N()->__('Stage'),),
      'social_status' => array(
        'individual_social_status',
        $this->getI18N()->__('Social Status'),),
      'rock_form' => array(
        'individual_rock_form',
        $this->getI18N()->__('Rock Form'),),
      'individual_count' => array(
        'individual_count_max',
        $this->getI18N()->__('Individual Count'),),
      );
  }  
  
  private function FecthIdForCommonNames() 
  {
    $tab = array('taxonomy'=> array(), 'chronostratigraphy' => array(), 'lithostratigraphy' => array(), 'lithology' => array(),'mineralogy' => array()) ;
    if(isset($this->search))
    {
      foreach($this->search as $specimen)
      {
        if($specimen->getTaxonRef()) $tab['taxonomy'][] = $specimen->getTaxonRef() ;
        if($specimen->getChronoRef()) $tab['chronostratigraphy'][] = $specimen->getChronoRef() ;
        if($specimen->getLithoRef()) $tab['lithostratigraphy'][] = $specimen->getLithoRef() ;
        if($specimen->getLithologyRef()) $tab['lithology'][] = $specimen->getLithologyRef() ;
        if($specimen->getMineralRef()) $tab['mineralogy'][] = $specimen->getMineralRef() ;
      }
    }
    else
    {
      if($this->specimen->getTaxonRef()) $tab['taxonomy'][] = $this->specimen->getTaxonRef() ;
      if($this->specimen->getChronoRef()) $tab['chronostratigraphy'][] = $this->specimen->getChronoRef() ;
      if($this->specimen->getLithoRef()) $tab['lithostratigraphy'][] = $this->specimen->getLithoRef() ;
      if($this->specimen->getLithologyRef()) $tab['lithology'][] = $this->specimen->getLithologyRef() ;
      if($this->specimen->getMineralRef()) $tab['mineralogy'][] = $this->specimen->getMineralRef() ;   
    }
    return $tab ;
  }
}
