<?php

/**
 * specimen components actions.
 *
 * @package    darwin
 * @subpackage speicmen_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class specimensearchwidgetComponents extends sfComponents
{
  public function executeRefCollection()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeRefTaxon()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }
  public function executeRefLitho()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }
  public function executeRefLithology()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }
  public function executeRefChrono()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }
  public function executeRefMineral()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }

  public function executeSpecIds()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }

  public function executeRefGtu()
  {
    if(!$this->form) 
    {
      $this->form = new SpecimenSearchFormFilter() ;  
      $this->form->addGtuTagValue(0);
    }
  }

  public function executeType()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeSex()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeStage()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  } 

  public function executeStatus()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeSocial()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeRockform()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeCodes()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
    if(! $this->form->isBound() && count($this->form['Codes']) == 0)
      $this->form->addCodeValue(0);
  }

  public function executeMethods()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeTools()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }

  public function executeWhatSearched()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }

  public function executeLocalisation()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }

  public function executeContainer()
  {
    if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;  
  }
  
  public function executeLatlong()
  {
     if(!$this->form) $this->form = new SpecimenSearchFormFilter() ;
  }
}
