<?php

/**
 * chronostratigraphy actions.
 *
 * @package    darwin
 * @subpackage chronostratigraphy
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class chronostratigraphyActions extends DarwinActions
{
  protected $widgetCategory = 'catalogue_chronostratigraphy_widget';
  protected $table = 'chronostratigraphy';
  
  public function preExecute()
  {
    if (strstr('view,index',$this->getActionName()) )
    {
      if(! $this->getUser()->isAtLeast(Users::ENCODER))
      {
        $this->forwardToSecureAction();
      }
    }
  }
  
  public function executeChoose(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new ChronostratigraphyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
    $this->setLayout(false);
  }

  public function executeDelete(sfWebRequest $request)
  {   
    $this->forward404Unless(
      $unit = Doctrine::getTable('Chronostratigraphy')->findExcept($request->getParameter('id')),
      sprintf('Object chronostratigraphy does not exist (%s).', array($request->getParameter('id')))
    );

    try
    {
      $unit->delete();
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form = new ChronostratigraphyForm($unit);
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
      return ;
    }
    $this->redirect('chronostratigraphy/index');
  }

  public function executeNew(sfWebRequest $request)
  {
    $chrono = new Chronostratigraphy();
    $duplic = $request->getParameter('duplicate_id','0');    
    $chrono = $this->getRecordIfDuplicate($duplic, $chrono);
    // if there is no duplicate $chrono is an empty array
    $this->form = new ChronostratigraphyForm($chrono);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new ChronostratigraphyForm();
    $this->processForm($request,$this->form);
    $this->setTemplate('new');
  }
    
  public function executeEdit(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Chronostratigraphy')->findExcept($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->no_right_col = Doctrine::getTable('Chronostratigraphy')->testNoRightsCollections('chrono_ref',$request->getParameter('id'), $this->getUser()->getId());

    $this->form = new ChronostratigraphyForm($unit);  
    $this->loadWidgets();
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$unit->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Chronostratigraphy')->find($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->no_right_col = Doctrine::getTable('Chronostratigraphy')->testNoRightsCollections('chrono_ref',$request->getParameter('id'), $this->getUser()->getId());    
    $this->form = new ChronostratigraphyForm($unit);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$unit->getId());
    $this->processForm($request,$this->form);

    $this->loadWidgets();

    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    $this->searchForm = new ChronostratigraphyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {  
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try{
	      $form->save();
	      $this->redirect('chronostratigraphy/edit?id='.$form->getObject()->getId());
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
    $this->chrono = Doctrine::getTable('Chronostratigraphy')->findExcept($request->getParameter('id'));
    $this->forward404Unless($this->chrono,'Chrono not Found');
    $this->form = new ChronostratigraphyForm($this->chrono);    
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable($this->table,$this->chrono->getId());
  }  
}
