<?php

/**
 * lithology actions.
 *
 * @package    darwin
 * @subpackage lithology
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class lithologyActions extends DarwinActions
{
  protected $widgetCategory = 'catalogue_lithology_widget';
  protected $table = 'lithology';

  public function executeChoose(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new LithologyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
    $this->setLayout(false);
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless(
      $unit = Doctrine::getTable('Lithology')->findExcept($request->getParameter('id')),
      sprintf('Object lithology does not exist (%s).', array($request->getParameter('id')))
    );

    try
    {
      $unit->delete();
      $this->redirect('lithology/index');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form = new InstitutionsForm($institution);
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $litho = new Lithology() ;
    $duplic = $request->getParameter('duplicate_id','0');
    $litho = $this->getRecordIfDuplicate($duplic, $litho);
    $this->form = new LithologyForm($litho);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new LithologyForm();
    $this->processForm($request,$this->form);
    $this->setTemplate('new');
  }
    
  public function executeEdit(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Lithology')->findExcept($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->form = new LithologyForm($unit);
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$unit->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Lithology')->find($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->form = new LithologyForm($unit);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$unit->getId());
    $this->processForm($request,$this->form);

    $this->loadWidgets();
    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new LithologyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try{
	$form->save();
	$this->redirect('lithology/edit?id='.$form->getObject()->getId());
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
    $this->litho = Doctrine::getTable('Lithology')->findExcept($request->getParameter('id'));
    $this->forward404Unless($this->litho,'Lithologic unit not Found');
    $this->form = new LithologyForm($this->litho);    
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$this->litho->getId());
  }
}
