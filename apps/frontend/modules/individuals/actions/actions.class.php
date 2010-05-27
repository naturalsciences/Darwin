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
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0));
	$this->forward404Unless($this->specimen->count());
	$spec_individual = new SpecimenIndividuals(array('specimen_ref' => $this->specimen->getId()));
	if($request->hasParameter('individual_id') && $request->getParameter('individual_id'))
	  $spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id'));
	$this->individual = new SpecimenIndividualsForm($spec_individual);
  }

  public function executeOverview(sfWebRequest $request)
  {
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0));
	$this->forward404Unless($this->specimen->count());
	$this->individuals = Doctrine::getTable('SpecimenIndividuals')->findBySpecimenRef($this->specimen->getId());
  }
}
