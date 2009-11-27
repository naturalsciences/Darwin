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

  public function executeRelation(sfWebRequest $request)
  {
    $modelName = Catalogue::getModelForTable($request->getParameter('table'));
    $this->linkItem = Doctrine::getTable($modelName)->find($request->getParameter('id'));
    $this->relation = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('relid',0));
    if(! $this->relation)
    {
      $this->relation = new CatalogueRelationships();
      $this->remoteItem = new $modelName();
    }
    else
    {
      $this->remoteItem = Doctrine::getTable($modelName)->find($this->relation->getRecordId_2());
    }
    $this->searchForm = new SearchCatalogueForm(array('table'=> $request->getParameter('table') ));
  }

  public function executeDeleteComment(sfWebRequest $request)
  {
    $r = Doctrine::getTable('Comments')->find($request->getParameter('id'));
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  public function executeComment(sfWebRequest $request)
  {
    if($request->hasParameter('cid'))
      $this->comment =  Doctrine::getTable('Comments')->find($request->getParameter('cid'));
    else
    {
     $this->comment = new Comments();
     $this->comment->setRecordId($request->getParameter('id'));
     $this->comment->setReferencedRelation($request->getParameter('table'));
    }
     
    $this->form = new CommentsForm($this->comment);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('comments'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	  }
	  catch(Exception $e)
	  {
	    return $this->renderText($e->getMessage());
	  }
	  return $this->renderText('ok');
	}
    }
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
  
  public function executeTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable( Catalogue::getModelForTable($request->getParameter('table')) )
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
 	$this->items = Doctrine::getTable( Catalogue::getModelForTable($form->getValue('table')) )
 	  ->getByNameLike($form->getValue('name'));
      }
    }
  }
}
