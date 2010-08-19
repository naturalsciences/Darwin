<?php

/**
 * collecting_methods actions.
 *
 * @package    darwin
 * @subpackage collecting_methods
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class collecting_methodsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->collecting_methodss = Doctrine::getTable('CollectingMethods')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CollectingMethodsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CollectingMethodsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($collecting_methods = Doctrine::getTable('CollectingMethods')->find(array($request->getParameter('id'))), sprintf('Object collecting_methods does not exist (%s).', $request->getParameter('id')));
    $this->form = new CollectingMethodsForm($collecting_methods);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($collecting_methods = Doctrine::getTable('CollectingMethods')->find(array($request->getParameter('id'))), sprintf('Object collecting_methods does not exist (%s).', $request->getParameter('id')));
    $this->form = new CollectingMethodsForm($collecting_methods);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($collecting_methods = Doctrine::getTable('CollectingMethods')->find(array($request->getParameter('id'))), sprintf('Object collecting_methods does not exist (%s).', $request->getParameter('id')));
    $collecting_methods->delete();

    $this->redirect('collecting_methods/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try
      {
        $form->save();
        $this->redirect('collecting_methods/edit?id='.$form->getObject()->getId());
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
