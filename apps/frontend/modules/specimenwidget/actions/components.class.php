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
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }

  public function executeRefCollection()
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

  public function executeLinkTaxon()
  {}

  public function executeLinkHabitat()
  {}

}
