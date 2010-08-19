<?php

/**
 * collecting_tools actions.
 *
 * @package    darwin
 * @subpackage collecting_tools
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class collecting_toolsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->collecting_toolss = Doctrine::getTable('CollectingTools')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new CollectingToolsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new CollectingToolsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($collecting_tools = Doctrine::getTable('CollectingTools')->find(array($request->getParameter('id'))), sprintf('Object collecting_tools does not exist (%s).', $request->getParameter('id')));
    $this->form = new CollectingToolsForm($collecting_tools);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($collecting_tools = Doctrine::getTable('CollectingTools')->find(array($request->getParameter('id'))), sprintf('Object collecting_tools does not exist (%s).', $request->getParameter('id')));
    $this->form = new CollectingToolsForm($collecting_tools);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($collecting_tools = Doctrine::getTable('CollectingTools')->find(array($request->getParameter('id'))), sprintf('Object collecting_tools does not exist (%s).', $request->getParameter('id')));
    $collecting_tools->delete();

    $this->redirect('collecting_tools/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try
      {
        $form->save();
        $this->redirect('collecting_tools/edit?id='.$form->getObject()->getId());
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
