<?php

/**
 * Individuals actions.
 *
 * @package    darwin
 * @subpackage individuals
 * @author     DB team <collections@naturalsciences.be>
 */
class individualsActions extends DarwinActions
{
  protected $widgetCategory = 'individuals_widget';

  public function executeEdit(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$this->loadWidgets();
	$spec_individual = new SpecimenIndividuals(array('specimen_ref' => $this->specimen->getId()));
	if($request->hasParameter('individual_id') && $request->getParameter('individual_id'))
	  $this->forward404Unless($spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id')), sprintf('Specimen individual does not exist (%s).', $request->getParameter('individual_id')));
	$this->individual = new SpecimenIndividualsForm($spec_individual);
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'),'You must submit your data with Post Method');
    $this->individual = new SpecimenIndividualsForm();
    $this->processForm($request, $this->individual);

    $this->loadWidgets();

    $this->setTemplate('edit');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'),$this->getI18N()->__('You must submit your data with Post Method'));
    $this->forward404Unless($spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id')),$this->getI18N()->__('Specimen individual not found'));
    $this->individual = new SpecimenIndividualsForm($spec_individual);

    $this->processForm($request, $this->individual);

    $this->loadWidgets();

    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      try
      {
        $individual = $form->save();
        $this->redirect('individuals/edit?spec_id='.$individual->getSpecimenRef().'&individual_id='.$individual->getId());
      }
      catch(Doctrine_Exception $ne)
      {
	$e = new DarwinPgErrorParser($ne);
	$error = new sfValidatorError(new savedValidator(),$e->getMessage());
	$form->getErrorSchema()->addError($error); 
      }
    }
  }

  public function executeOverview(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$this->individuals = Doctrine::getTable('SpecimenIndividuals')->findBySpecimenRef($this->specimen->getId());
  }

  public function executeDelete(sfWebRequest $request)
  {
    $specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0));
    if(!$specimen)
      return $this->renderText($this->getI18N()->__('Specimen does not exist'));
    $individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id',0));
    if(!$individual)
      return $this->renderText($this->getI18N()->__('Individual does not exist'));
    try
    {
      $individual->delete();
      return $this->renderText('ok');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      return $this->renderText($e->getMessage());
    }
  }

}
