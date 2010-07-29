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
    $this->institutions = Doctrine::getTable('Collections')->fetchByInstitutionList();
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
  public function executeRefGtu()
  {
    if(!$this->form) 
    {
      $this->form = new SpecimenSearchFormFilter() ;  
      $this->form->addGtuTagValue(0);
    }
  }  

}
