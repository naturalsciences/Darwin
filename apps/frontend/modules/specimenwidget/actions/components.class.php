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
//     $this->collections = Doctrine::getTable('Collections')->findAll();
    $this->form = new SpecimensForm();
  }

  public function executeAcquisitionCategory()
  {
    $this->form = new SpecimensForm();
  }

  public function executeSpecimenCount()
  {
    $this->form = new SpecimensForm();
  }

  public function executeLinkTaxon()
  {}

  public function executeLinkHabitat()
  {}
}
