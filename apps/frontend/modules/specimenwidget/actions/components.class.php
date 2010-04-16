<?php

/**
 * account actions.
 *
 * @package    darwin
 * @subpackage board_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class specimenwidgetComponents extends sfComponents
{

  protected function defineForm()
  {
    if(! isset($this->form) )
    {
	if(isset($this->eid) && $this->eid != null)
	{
	  $spec = Doctrine::getTable('Specimens')->find($this->eid);
	  $this->form = new SpecimensForm($spec);
	}
	else
	{
	  $this->form = new SpecimensForm();
	}
    }
  }

  public function executeRefCollection()
  {
    $this->defineForm();
  }

  public function executeRefExpedition()
  {
    $this->defineForm();
  }

  public function executeRefIgs()
  {
    $this->defineForm();
  }

  public function executeAcquisitionCategory()
  {
    $this->defineForm();
  }

  public function executeSpecimenCount()
  {
    $this->defineForm();
  }

  public function executeTool()
  {
    $this->defineForm();
  }

  public function executeMethod()
  {
    $this->defineForm();
  }

  public function executeRefTaxon()
  {
    $this->defineForm();
  }

  public function executeRefChrono()
  {
    $this->defineForm();
  }

  public function executeRefLitho()
  {
    $this->defineForm();
  }

  public function executeRefLithology()
  {
    $this->defineForm();
  }

  public function executeRefMineral()
  {
    $this->defineForm();
  }

  public function executeRefGtu()
  {
    $this->defineForm();
  }

  public function executeRefHosts()
  {
    $this->defineForm();
  }

  public function executeRefCodes()
  {
    $this->defineForm();
  }

  public function executeLinkHabitat()
  {}

}
