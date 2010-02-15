<?php

/**
 * chronostratigraphy actions.
 *
 * @package    darwin
 * @subpackage chronostratigraphy
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class chronostratigraphyActions extends sfActions
{
  public function executeChoose(sfWebRequest $request)
  {
    $this->searchForm = new ChronostratigraphyFormFilter(array('table'=> 'chronostratigraphy'));
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
    catch(Doctrine_Connection_Pgsql_Exception $e)
    {
      $this->form = new ChronostratigraphyForm($unit);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form->getErrorSchema()->addError($error); 
      $this->setTemplate('edit');
      return ;
    }
    $this->redirect('chronostratigraphy/index');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ChronostratigraphyForm();
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
    $this->form = new ChronostratigraphyForm($unit);
    
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('catalogue_chronostratigraphy_widget');
    if(! $this->widgets) $this->widgets=array();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('chronostratigraphy',$unit->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Chronostratigraphy')->find($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->form = new ChronostratigraphyForm($unit);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('chronostratigraphy',$unit->getId());
    $this->processForm($request,$this->form);

    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('catalogue_chronostratigraphy_widget');
    if(! $this->widgets) $this->widgets=array();

    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->searchForm = new ChronostratigraphyFormFilter(array('table'=> 'chronostratigraphy'));
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
      catch(sfStopException $e)
      { throw $e; }
      catch(Exception $e)
      {
	$error = new sfValidatorError(new savedValidator(),$e->getMessage());
	$form->getErrorSchema()->addError($error); 
      }
    }
  }

}