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
    $this->relation = null;
    if($request->hasParameter('id'))
    {
      $this->relation = Doctrine::getTable('CatalogueRelationships')->find($request->getParameter('id'));
    }
    if(! $this->relation)
    {
     $this->relation = new CatalogueRelationships();
     $this->relation->setRecordId_1($request->getParameter('rid'));
     $this->relation->setReferencedRelation($request->getParameter('table'));
     $this->relation->setRelationshipType($request->getParameter('type') == 'rename' ? 'current_name' : 'recombined from');
    }

    $this->form = new CatalogueRelationshipsForm($this->relation);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('catalogue_relationships'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	    $this->form->getObject()->refreshRelated();
	    $this->form = new CatalogueRelationshipsForm($this->form->getObject()); //Ugly refresh
	    return $this->renderText('ok');
	  }
	  catch(Doctrine_Exception $ne)
	  {
	    $e = new DarwinPgErrorParser($ne);
	    $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	    $form->getErrorSchema()->addError($error);
	  }
	}
    }
    $filterFormName = DarwinTable::getFilterForTable($request->getParameter('table'));
    $this->searchForm = new $filterFormName(array('table'=>$request->getParameter('table')));
  }
  
  public function executeTree(sfWebRequest $request)
  {
    $this->items = Doctrine::getTable( DarwinTable::getModelForTable($request->getParameter('table')) )
      ->findWithParents($request->getParameter('id'));
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->setCommonValues('catalogue', 'name_order_by', $request);
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
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
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
        $query = $form->getQuery()
	  ->orderBy($this->orderBy .' '.$this->orderDir);
	if($this->is_choose == 0)
	  $query->andWhere('id > 0');
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

  public function executeAddValue(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    
    $formName = DarwinTable::getFormForTable($request->getParameter('table'));
    $form = new $formName();

    $form->addKeyword($number, $request->getParameter('keyword'), $request->getParameter('value'));

    return $this->renderPartial('nameValue',array('form' => $form['newVal'][$number]));
  }
}
