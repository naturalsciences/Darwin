<?php

/**
 * board actions.
 *
 * @package    darwin
 * @subpackage board
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class specimenActions extends sfActions
{

  public function loadWidgets()
  {
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user')->getId())
      ->getWidgets('specimen_widget');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->loadWidgets();
    $this->form = new SpecimensForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'),'You must submit your data with Post Method');
    $this->form = new SpecimensForm();
    $this->processForm($request, $this->form);

    $this->loadWidgets();
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->loadWidgets();
    $specimen = Doctrine::getTable('Specimens')->find($request->getParameter('id'));
    $this->forward404Unless($specimen,'Specimen not Found');
    
    $this->form = new SpecimensForm($specimen);
    $this->setTemplate('new');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->loadWidgets();

    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $specimen = Doctrine::getTable('Specimens')->find($request->getParameter('id'));
    $this->forward404Unless($specimen,'Specimen not Found');
    $this->form = new SpecimensForm($specimen);

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      try{
        $specimen = $form->save();
        $this->redirect('specimen/edit?id='.$specimen->getId());
      }
      catch(Exception $e)
      {
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }
}
