<?php

/**
 * catalogue actions.
 *
 * @package    darwin
 * @subpackage catalogue
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class catalogueActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeRelation(sfWebRequest $request)
  {
    $tableName = $this->getTableName($request->getParameter('table'));
    $this->linkItem = Doctrine::getTable($tableName)->find($request->getParameter('id'));
    $this->relation = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('relid',0));
    if(! $this->relation)
    {
      $this->relation = new CatalogueRelationships();
      $this->renamedItem = new $tableName();
    }
    else
    {
      $this->renamedItem = Doctrine::getTable($tableName)->find($this->relation->getRecordId_2());
    }

//     $this->form = new CatalogueRelationshipsForm();
    $this->searchForm = new SearchCatalogueForm(array('table'=> $request->getParameter('table') ));
  }
  
  public function executeDeleteRelation(sfWebRequest $request)
  {
    $r = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('relid'));
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
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
    $r->setRecordId_1($ref_item);
    $r->setRecordId_2($linked_item);
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

  protected function getTableName($item)
  {
    switch($item)
    {
	  case 'taxonomy' : return 'Taxonomy';
    }
  }
  
  public function executeTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable( $this->getTableName($request->getParameter('table')) )
      ->findWithParents($request->getParameter('id'));
    $this->setLayout(false);
  }

  public function executeSearch(sfWebRequest $request)
  {
    $item = $request->getParameter('searchTaxon',array('') );
    $this->searchForm = new SearchCatalogueForm(array(),array('table' => $item['table']));
    $this->searchResults($this->searchForm,$request);
    $this->setLayout(false);
  }

  protected function searchResults($form, $request)
  {
    if($request->getParameter('searchTaxon','') !== '')
    {
      $form->bind($request->getParameter('searchTaxon'));
      if ($form->isValid())
      {
 	$this->items = Doctrine::getTable( $this->getTableName($form->getValue('table')) )
 	  ->getByNameLike($form->getValue('name'));
      }
    }
  }
}
