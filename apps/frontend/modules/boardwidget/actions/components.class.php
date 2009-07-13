<?php

/**
 * account actions.
 *
 * @package    darwin
 * @subpackage board_widget
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class boardwidgetComponents extends sfComponents
{
  public function executeSavedSearch()
  {
    $this->searches = Doctrine::getTable('MySavedSearches')
      ->findByUser($this->getUser()->getAttribute('db_user')->getId());
  }

  public function executeSavedSpecimens()
  {
    $this->specimens = Doctrine::getTable('MySavedSpecimens')
      ->findByUser($this->getUser()->getAttribute('db_user')->getId());
  }
  
  public function executeAddTaxon()
  {}

  public function executeAddSpecimen()
  {}
  
}
