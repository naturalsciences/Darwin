<?php

class stagingActions extends DarwinActions
{

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('import'));
    $this->setCommonValues('staging', 'id', $request);

    $this->import = Doctrine::getTable('Imports')->find($request->getParameter('import'));
    $this->form = new StagingFormFilter(null, array('import' =>$this->import));
    $filters = $request->getParameter('staging_filters');
    if(!isset($filters['slevel'])) $filters['slevel'] = 'specimen';

    $this->form->bind($filters);
    if($this->form->isValid())
    {
      $query = $this->form->getQuery();
      $query->andWhere('import_ref = ?',$this->import->getId());
      // Define the pager
      $pager = new DarwinPager($query, $this->form->getValue('current_page'), $this->form->getValue('rec_per_page'));

      /*// Replace the count query triggered by the Pager to get the number of records retrieved
      $count_q = clone $query;
      // Remove from query the group by and order by clauses
      $count_q = $count_q->select('count( distinct spec_ref)')->removeDqlQueryPart('groupby')->removeDqlQueryPart('orderby')->limit(0);
      // Initialize an empty count query
      $counted = new DoctrineCounted();
      // Define the correct select count() of the count query
      $counted->count_query = $count_q;
      // And replace the one of the pager with this new one
      $pager->setCountQuery($counted); */  

      $this->setCommonValues('search', 'collection_name', $request);
      $params = $request->isMethod('post') ? $request->getPostParameters() : $request->getGetParameters();

      $this->s_url = 'staging/search'.'?import='.$request->getParameter('import');
      $this->o_url = '';//'&orderby='.$this->orderBy.'&orderdir='.$this->orderDir;

      $this->pagerLayout = new PagerLayoutWithArrows(
          new DarwinPager(
            $query,
            $this->currentPage,
            $this->form->getValue('rec_per_page')
          ),
          new Doctrine_Pager_Range_Sliding(
            array('chunk' => $this->pagerSlidingSize)
          ),
          $this->getController()->genUrl($this->s_url.$this->o_url) . '/page/{%page_number}'
        );

      $this->setDefaultPaggingLayout($this->pagerLayout);
      $this->pagerLayout->setTemplate('<li data-page="{%page_number}"><a href="{%url}">{%page}</a></li>');

      // If pager not yet executed, this means the query has to be executed for data loading
      if (! $this->pagerLayout->getPager()->getExecuted())
        $this->search = $this->pagerLayout->execute();

      $this->displayModel = new DisplayImportDna();
      
      $this->fields = $this->displayModel->getColumnsForLevel($this->form->getValue('slevel'));
    }

  }
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('import'));
    $this->import = Doctrine::getTable('Imports')->find($request->getParameter('import'));
    $this->form = new StagingFormFilter(null, array('import' =>$this->import));
    $filters = $request->getParameter('staging_filters');
    if(!isset($filters['slevel'])) $filters['slevel'] = 'specimen';
    $this->form->bind($filters);
  }
  
  protected function getFieldsForLevel($level)
  {
  $fields = array('specimens' =>
        array(
          'spec_ref',
          'category',
          'expedition_ref',
          'expedition_name',
          'station_visible',
          'gtu_ref',
          'gtu_code',
//           'gtu_parent_ref',
//           'gtu_path',
//           'gtu_from_date_mask',
//           'gtu_from_date',
//           'gtu_to_date_mask',
//           'gtu_to_date',
//           'gtu_tag_values_indexed',
//           'gtu_country_tag_value',
          'taxon_ref',
          'taxon_name',
//           'taxon_name_indexed',
//           'taxon_name_order_by',
//           'taxon_level_ref',
//           'taxon_level_name',
//           'taxon_status',
//           'taxon_path',
//           'taxon_parent_ref',
//           'taxon_extinct',
          'chrono_ref',
          'chrono_name',
//           'chrono_name_indexed',
//           'chrono_name_order_by',
//           'chrono_level_ref',
//           'chrono_level_name',
//           'chrono_status',
//           'chrono_path',
//           'chrono_parent_ref',
          'litho_ref',
          'litho_name',
//           'litho_name_indexed',
//           'litho_name_order_by',
//           'litho_level_ref',
//           'litho_level_name',
//           'litho_status',
//           'litho_path',
//           'litho_parent_ref',
          'lithology_ref',
          'lithology_name',
//           'lithology_name_indexed',
//           'lithology_name_order_by',
//           'lithology_level_ref',
//           'lithology_level_name',
//           'lithology_status',
//           'lithology_path',
//           'lithology_parent_ref',
          'mineral_ref',
          'mineral_name',
//           'mineral_name_indexed',
//           'mineral_name_order_by',
//           'mineral_level_ref',
//           'mineral_level_name',
//           'mineral_status',
//           'mineral_path',
//           'mineral_parent_ref',
//           'host_taxon_ref',
//           'host_taxon_name',
//           'host_taxon_name_indexed',
//           'host_taxon_name_order_by',
//           'host_taxon_level_ref',
//           'host_taxon_level_name',
//           'host_taxon_status',
//           'host_taxon_path',
//           'host_taxon_parent_ref',
//           'host_taxon_extinct',
         'ig_ref',
         'ig_num',
//           'ig_num_indexed',
//           'ig_date_mask',
//           'ig_date',
          'acquisition_category',
//           'acquisition_date_mask',
//           'acquisition_date',
//           'with_types',
//           'with_individuals',
        ),
        'individuals' => array(
          'individual_ref',
          'individual_type',
          'individual_sex',
          'individual_state',
          'individual_stage',
          'individual_social_status',
          'individual_rock_form',
          'individual_count_min',
          'individual_count_max',
        )
      );
      $fields['parts'] = array(
          'part_ref',
          'part',
          'part_status',
          'building',
          'floor',
          'room',
          'row',
          'shelf',
          'container_type',
          'container_storage',
          'container',
          'sub_container_type',
          'sub_container_storage',
          'sub_container',
          'part_count_min',
          'part_count_max',
        ) ;
      if($level == 'tissue') $level = 'parts';
      if($level == 'dna') $level = 'parts';
      return $fields[$level];
  }
  
  public function executeEdit(sfWebRequest $request)
  {  
    if($this->getUser()->isA(Users::REGISTERED_USER)) $this->forwardToSecureAction();   
    $staging = Doctrine::getTable('Staging')->findOneById($request->getParameter('id'));
    $this->fields = $staging->getFields() ;
    $form_fields = array() ;   
    if($this->fields)
    {
      foreach($this->fields as $key => $values)
        $form_fields[] = $values['fields'] ;
    }
    $this->form = new StagingForm($staging, array('fields' => $form_fields));
  } 
   
  public function executeUpdate(sfWebRequest $request)
  {
    if($this->getUser()->isA(Users::REGISTERED_USER)) $this->forwardToSecureAction(); 
    $staging = Doctrine::getTable('Staging')->findOneById($request->getParameter('id'));
    $this->fields = $staging->getFields() ;
    $form_fields = array() ;   
    if($this->fields)
    {
      foreach($this->fields as $key => $values)
        $form_fields[] = $values['fields'] ;
    }
    $this->form = new StagingForm($staging, array('fields' => $form_fields));
    
    $this->processForm($request,$this->form, $form_fields);

    $this->setTemplate('edit');
  }  
  
  protected function processForm(sfWebRequest $request, sfForm $form, array $fields)
  {
    if($this->getUser()->isA(Users::REGISTERED_USER)) $this->forwardToSecureAction();
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try
      {
        $form->save();
        $this->redirect('staging?import='.$form->getObject()->getId());
      }
      catch(Doctrine_Exception $ne)
      {
        $e = new DarwinPgErrorParser($ne);
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }  
}
