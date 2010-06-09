<?php

/**
 * specimen individuals components actions.
 *
 * @package    darwin
 * @subpackage individuals_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class individualswidgetComponents extends sfComponents
{

  protected function defineForm()
  {
    if(! isset($this->form) )
    {
	if(isset($this->eid) && $this->eid != null)
	{
	  $spec_individual = Doctrine::getTable('SpecimenIndividuals')->find($this->eid);
	  $this->form = new SpecimenIndividualsForm($spec_individual);
	  $this->individual_id = $this->form->getObject()->getId();
	  $this->spec_id = $this->form->getObject()->getSpecimenRef();
	}
	else
	{
	  $this->form = new SpecimenIndividualsForm();
	  $this->individual_id = 0;
	  $this->spec_id = 0;
	}
    }
    else
    {
      $this->individual_id = $this->form->getObject()->getId();
      $this->spec_id = $this->form->getObject()->getSpecimenRef();
    }
    if(! isset($this->module) )
    {
      $this->module = 'individuals';
    }
  }

  public function executeType()
  {
    $this->defineForm();
  }

  public function executeSex()
  {
    $this->defineForm();
  }

  public function executeStage()
  {
    $this->defineForm();
  }

  public function executeSocialStatus()
  {
    $this->defineForm();
  }

  public function executeRockForm()
  {
    $this->defineForm();
  }

  public function executeSpecimenIndividualCount()
  {
    $this->defineForm();
  }

  public function executeSpecimenIndividualComments()
  {
    $this->defineForm();
  }
  
  public function executeRefIdentifications()
  {
    $this->defineForm();
  }

  public function executeRefProperties()
  {
    if(isset($this->form) )
      $this->eid = $this->form->getObject()->getId() ;
  }

}
