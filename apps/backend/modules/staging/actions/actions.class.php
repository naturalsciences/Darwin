<?php

class stagingActions extends DarwinActions
{

  public function executeMarkok(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('import'));

    $this->import = Doctrine::getTable('Imports')->markOk($request->getParameter('import'));
    return $this->redirect('import/index');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $line = Doctrine::getTable('Staging')->find($request->getParameter('id'));
    $line->delete();
    if($request->isXmlHttpRequest())
    {
      return $this->renderText('ok');
    }
    return $this->redirect('staging/index?import=');
  }

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

      /** Let's Fetch id for codes */
      $ids = array();
      foreach($this->search as $k=>$v)
      {
        $ids[] = $v->getId();
      }
      
      $codes = Doctrine::getTable('Codes')->getCodesRelatedArray('staging',$ids) ;
      $linked = Doctrine::getTable('Staging')->findLinked($ids) ;
      foreach($this->search as $k=>$v)
      {
        foreach($codes as $code)
        {
          if($code['record_id'] == $v->getId())
          {
            $v->codes[] = $code;
          }
        }
        foreach($linked as $link)
        {
          if($link['record_id'] == $v->getId())
            $v->setLinkedInfo($link['cnt']);
        }
      }

      $this->displayModel = new DisplayImportDna();
      
      $this->fields = $this->displayModel->getColumnsForLevel($this->form->getValue('slevel'));
    }

  }
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('import'));
    $this->import = Doctrine::getTable('Imports')->find($request->getParameter('import'));
    $this->form = new StagingFormFilter(null, array('import' =>$this->import));
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
        return $this->redirect('staging/index?import='.$form->getObject()->getImportRef());
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
