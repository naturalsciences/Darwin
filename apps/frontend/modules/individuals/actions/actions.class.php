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
  protected $table = 'specimen_individuals';

  public function executeEdit(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$spec_individual = new SpecimenIndividuals();
	$spec_individual->setSpecimenRef($this->specimen->getId());
	if($request->hasParameter('individual_id') && $request->getParameter('individual_id'))
	  $this->forward404Unless($spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('individual_id')), sprintf('Specimen individual does not exist (%s).', $request->getParameter('individual_id')));
	$this->individual = new SpecimenIndividualsForm($spec_individual);

	if($request->isMethod('post'))
	{
	  $this->individual->bind( $request->getParameter('specimen_individuals') );
	  if( $this->individual->isValid())
	  {
	    try
	    {
		$this->individual->save();
		$this->redirect('individuals/overview?spec_id='.$this->individual->getObject()->getSpecimenRef());
	    }
	    catch(Doctrine_Exception $ne)
	    {
	      $e = new DarwinPgErrorParser($ne);
	      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	      $this->individual->getErrorSchema()->addError($error); 
	    }
	  }
	}
	$this->loadWidgets();
  }

  public function executeOverview(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$this->individuals = Doctrine::getTable('SpecimenIndividuals')->findBySpecimenRef($this->specimen->getId());
  }

}
