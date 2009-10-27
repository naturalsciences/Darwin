<?php

/**
 * taxonomy actions.
 *
 * @package    darwin
 * @subpackage taxonomy
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class taxonomyActions extends sfActions
{
  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TaxonomyForm();
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $taxa = Doctrine::getTable('Taxonomy')->find($request->getParameter('id'));
    $this->forward404Unless($taxa,'Taxa not Found');
    $this->form = new TaxonomyForm($taxa);
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->searchForm = new SearchTaxonForm();
    $this->executeSearchResults($this->searchForm, $request);
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->searchForm = new SearchTaxonForm();
    $this->executeSearchResults($this->searchForm,$request);
  }

  private function executeSearchResults($form, $request)
  {
    if($request->getParameter('searchTaxon','') !== '')
    {
      $form->bind($request->getParameter('searchTaxon'));
      if ($form->isValid())
      {
	$this->taxons = Doctrine::getTable('Taxonomy')
	  ->getByNameLike($form->getValue('name'), $form->getValue('level'));
      }
    }

  }
}