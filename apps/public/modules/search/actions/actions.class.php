<?php

/**
 * search actions.
 *
 * @package    darwin
 * @subpackage search
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class searchActions extends DarwinActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->specimen_searchs = Doctrine::getTable('SpecimenSearch')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->specimen_search = Doctrine::getTable('SpecimenSearch')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->specimen_search);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new SpecimenSearchForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new SpecimenSearchForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($specimen_search = Doctrine::getTable('SpecimenSearch')->find(array($request->getParameter('id'))), sprintf('Object specimen_search does not exist (%s).', $request->getParameter('id')));
    $this->form = new SpecimenSearchForm($specimen_search);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($specimen_search = Doctrine::getTable('SpecimenSearch')->find(array($request->getParameter('id'))), sprintf('Object specimen_search does not exist (%s).', $request->getParameter('id')));
    $this->form = new SpecimenSearchForm($specimen_search);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($specimen_search = Doctrine::getTable('SpecimenSearch')->find(array($request->getParameter('id'))), sprintf('Object specimen_search does not exist (%s).', $request->getParameter('id')));
    $specimen_search->delete();

    $this->redirect('search/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $specimen_search = $form->save();

      $this->redirect('search/edit?id='.$specimen_search->getId());
    }
  }
}
