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
  protected $widgetCategory = 'catalogue_taxonomy_widget';
  protected $table = 'taxonomy';

  public function executeChoose(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new TaxonomyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
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
      $this->redirect('taxonomy/index');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form = new TaxonomyForm($taxa);
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $new_taxa = new Taxonomy() ;
    $taxa = $this->getRecordIfDuplicate('Taxonomy', $request);
    // if there is no duplicate $taxa is an empty array
    $new_taxa->fromArray($taxa) ; 
    $this->form = new TaxonomyForm($new_taxa);
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

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$taxa->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $taxa = Doctrine::getTable('Taxonomy')->find($request->getParameter('id'));

    $this->forward404Unless($taxa,'Taxa not Found');
    $this->form = new TaxonomyForm($taxa);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$taxa->getId());
    $this->processForm($request,$this->form);

    $this->loadWidgets();
    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new TaxonomyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try
      {
        $form->save();
        $this->redirect('taxonomy/edit?id='.$form->getObject()->getId());
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
