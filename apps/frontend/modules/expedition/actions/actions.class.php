<?php

/**
 * expedition actions.
 *
 * @package    darwin
 * @subpackage expedition
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class expeditionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));
    
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('searchExpedition'));
      if ($this->form->isValid())
      {
      }

    }

    /*$this->expeditions_list = Doctrine::getTable('Expeditions')
      ->createQuery('a')
      ->execute();*/
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ExpeditionsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new ExpeditionsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new ExpeditionsForm($expeditions);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new ExpeditionsForm($expeditions);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $expeditions->delete();

    $this->redirect('expedition/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $expeditions = $form->save();

      $this->redirect('expedition/edit?id='.$expeditions->getId());
    }
  }
}
