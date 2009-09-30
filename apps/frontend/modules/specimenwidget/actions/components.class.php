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

  public function executeRefCollection()
  {
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }

  public function executeAcquisitionCategory()
  {
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }

  public function executeSpecimenCount()
  {
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }

  public function executeLinkTaxon()
  {}

  public function executeLinkHabitat()
  {}

  public function executeTool()
  {
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }

  public function executeMethod()
  {
    if( isset($this->options) )
        $this->form = $this->options;
    else
        $this->form = new SpecimensForm();
  }
}
