<?php

/**
 * taxonomy actions.
 *
 * @package    darwin
 * @subpackage taxonomy
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class taxonomyActions extends DarwinActions
{
  public $widgetCategory = 'catalogue_taxonomy_widget';

  public function executeChoose(sfWebRequest $request)
  {
    $this->searchForm = new TaxonomyFormFilter(array('table'=> 'taxonomy'));
    $this->setLayout(false);
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless(
      $taxa = Doctrine::getTable('Taxonomy')->findExcept($request->getParameter('id')),
      sprintf('Object taxonomy does not exist (%s).', array($request->getParameter('id')))
    );

    try
    {
      $taxa->delete();
    }
    catch(Doctrine_Connection_Pgsql_Exception $e)
    {
      $this->form = new TaxonomyForm($taxa);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form->getErrorSchema()->addError($error); 
      $this->setTemplate('edit');
      return ;
    }
    $this->redirect('taxonomy/index');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new TaxonomyForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new TaxonomyForm();
    $this->processForm($request,$this->form);
    $this->setTemplate('new');
  }
    
  public function executeEdit(sfWebRequest $request)
  {
    $taxa = Doctrine::getTable('Taxonomy')->findExcept($request->getParameter('id'));

    $this->forward404Unless($taxa,'Taxa not Found');
    $this->form = new TaxonomyForm($taxa);
    
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy',$taxa->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $taxa = Doctrine::getTable('Taxonomy')->find($request->getParameter('id'));

    $this->forward404Unless($taxa,'Taxa not Found');
    $this->form = new TaxonomyForm($taxa);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy',$taxa->getId());
    $this->processForm($request,$this->form);

    $this->loadWidgets();
    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->searchForm = new TaxonomyFormFilter(array('table'=> 'taxonomy'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try{
	$form->save();

	$this->redirect('taxonomy/edit?id='.$form->getObject()->getId());
      }
      catch(Doctrine_Exception $e)
      {
	$error = new sfValidatorError(new savedValidator(),$e->getMessage());
	$form->getErrorSchema()->addError($error); 
      }
    }
  }
}