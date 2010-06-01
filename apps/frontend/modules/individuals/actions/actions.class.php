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
  public function executeEdit(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$spec_individual = new SpecimenIndividuals(array('specimen_ref' => $this->specimen->getId()));
	if($request->hasParameter('individual_id') && $request->getParameter('individual_id'))
	  $spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id'));
	$this->individual = new SpecimenIndividualsForm($spec_individual);
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
      return $this->renderText('Specimen does not exist');
    $individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id',0));
    if(!$individual)
      return $this->renderText('Individual does not exist');
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
