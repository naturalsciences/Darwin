<?php

class stagingActions extends DarwinActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->import = Doctrine::getTable('Imports')->find($request->getParameter('import'));
    $this->form = new StagingFormFilter(null, array('import' =>$this->import));

    $this->form->bind($request->getParameter('staging_filters'));
    if($this->form->isValid())
    {
      $query = $this->form->getQuery();
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

      unset($params['staging_filters']['current_page']);
      $this->pagerLayout = new PagerLayoutWithArrows($pager,
                                                    new Doctrine_Pager_Range_Sliding(array('chunk' => $this->pagerSlidingSize)),
                                                    'search/search?specimen_search_filters[current_page]={%page_number}&'.http_build_query($params)
                                                    );
      // Sets the Pager Layout templates
      $this->setDefaultPaggingLayout($this->pagerLayout);
      $this->pagerLayout->setTemplate('<li data-page="{%page_number}"><a href="{%url}">{%page}</a></li>');

      // If pager not yet executed, this means the query has to be executed for data loading
      if (! $this->pagerLayout->getPager()->getExecuted())
        $this->search = $this->pagerLayout->execute();

      $this->fields = $this->getFieldsForLevel($this->form->getValue('slevel'));
    }

  }
  
  protected function getFieldsForLevel($level)
  {
  $fields = array('specimens' =>
        array(
          'spec_ref',
          'category',
//           'collection_ref',
//           'collection_code',
//           'collection_name',
//           'collection_is_public',
//           'collection_institution_ref',
//           'collection_institution_formated_name',
//           'collection_institution_formated_name_ts',
//           'collection_institution_formated_name_indexed',
//           'collection_institution_sub_type',
//           'collection_main_manager_ref',
//           'collection_main_manager_formated_name',
//           'collection_main_manager_formated_name_ts',
//           'collection_main_manager_formated_name_indexed',
//           'collection_parent_ref',
//           'collection_path',
          'expedition_ref',
          'expedition_name',
//           'expedition_name_ts',
//           'expedition_name_indexed',
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
}
