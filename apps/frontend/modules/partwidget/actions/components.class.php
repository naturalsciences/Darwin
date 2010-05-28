<?php

/**
 * specimen components actions.
 *
 * @package    darwin
 * @subpackage speicmen_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class partwidgetComponents extends sfComponents
{

  protected function defineForm()
  {
    if(! isset($this->form) )
    {
	if(isset($this->eid) && $this->eid != null)
	{
	  $spec = Doctrine::getTable('SpecimenParts')->find($this->eid);
	  $this->form = new SpecimenPartsForm($spec);
	}
	else
	{
	  $this->form = new SpecimenPartsForm();
	}
    }
  }

  public function executePartCount()
  {
    $this->defineForm();
  }

  public function executeSpecPart()
  {
    $this->defineForm();
  }

  public function executeComplete()
  {
    $this->defineForm();
  }

  public function executeLocalisation()
  {
    $this->defineForm();
  }

  public function executeContainer()
  {
    $this->defineForm();
  }

  public function executeRefCodes()
  {
    $this->defineForm();
  }

//   public function executeRefIdentifications()
//   {
//     $this->defineForm();
//   }
}
