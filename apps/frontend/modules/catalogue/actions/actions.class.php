<?php

/**
 * catalogue actions.
 *
 * @package    darwin
 * @subpackage catalogue
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class catalogueActions extends DarwinActions
{

  public function executeRelation(sfWebRequest $request)
  {
    $modelName = DarwinTable::getModelForTable($request->getParameter('table'));
    $this->linkItem = Doctrine::getTable($modelName)->findExcept($request->getParameter('id'));
    $this->relation = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('relid',0));
    if(! $this->relation)
    {
      $this->relation = new CatalogueRelationships();
      $this->remoteItem = new $modelName();
    }
    else
    {
      $this->remoteItem = Doctrine::getTable($modelName)->findExcept($this->relation->getRecordId2());
    }
    $filterFormName = DarwinTable::getFilterForTable($request->getParameter('table'));
    $this->searchForm = new $filterFormName(array('table'=>$request->getParameter('table')));
  }

  public function executeSaveRelation(sfWebRequest $request)
  {
    $tableName = $request->getParameter('table');
    $ref_item = $request->getParameter('id');
    $linked_item = $request->getParameter('record_id_2');
    $link_type = $request->getParameter('type');
    
    $r = new CatalogueRelationships();
    if(is_numeric($request->getParameter('relation_id')))
    $r = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('relation_id'));
    $r->setReferencedRelation($tableName);
    $r->setRecordId1($ref_item);
    $r->setRecordId2($linked_item);
    $r->setRelationshipType($link_type == 'rename' ? 'current_name' : 'recombined from');
    try{
      $r->save();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }
  
  public function executeTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )
      ->findWithParents($request->getParameter('id'));
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->setCommonValues('catalogue', 'name_indexed', $request);
    $item = $request->getParameter('searchCatalogue',array('') );
    $formFilterName = DarwinTable::getFilterForTable($item['table']);
    $this->searchForm = new $formFilterName(array('table' => $item['table']));
    $this->searchResults($this->searchForm,$request);
    $this->setLayout(false);
  }

  public function executeDeleteRelated(sfWebRequest $request)
  {
    $r = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such item');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  protected function searchResults($form, $request)
  {
    if($request->getParameter('searchCatalogue','') !== '')
    {
      $form->bind($request->getParameter('searchCatalogue'));
      if ($form->isValid())
      {
        $query = $form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager($query,
                             $this->currentPage,
                             $form->getValue('rec_per_page')
	                    ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $this->pagerSlidingSize)
	    ),
	  $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
	);

        // Sets the Pager Layout templates
        $this->setDefaultPaggingLayout($this->pagerLayout);
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->items = $this->pagerLayout->execute();
	
      }
    }
  }
}
