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
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();  
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
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();
    $taxa = new Taxonomy() ;
    $taxa = $this->getRecordIfDuplicate($request->getParameter('duplicate_id','0'), $taxa);
    // if there is no duplicate $taxa is an empty array
    $this->form = new TaxonomyForm($taxa);
  }

  public function executeCreate(sfWebRequest $request)
  {
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();
    $this->form = new TaxonomyForm();
    $this->processForm($request,$this->form);
    $this->setTemplate('new');
  }
    
  public function executeEdit(sfWebRequest $request)
  {
    if($request->getParameter('id') < 1 || $this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();
    $taxa = Doctrine::getTable('Taxonomy')->findExcept($request->getParameter('id'));

    $this->no_right_col = Doctrine::getTable('Taxonomy')->testNoRightsCollections('taxon_ref',$request->getParameter('id'), $this->getUser()->getId());

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
    $this->user_allowed = ($this->getUser()->getDbUserType() < Users::ENCODER?false:true) ;    
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
  
  public function executeView(sfWebRequest $request)
  {
    $this->taxon = Doctrine::getTable('Taxonomy')->findExcept($request->getParameter('id'));
    $this->forward404Unless($this->taxon,'Taxa not Found');
    $this->form = new TaxonomyForm($this->taxon);    
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$this->taxon->getId());
  }  
}
