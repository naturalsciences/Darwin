<?php

class userswidgetComponents extends sfComponents
{
  public function executeAddress()
  {
    $this->addresses =  Doctrine::getTable('UsersAddresses')->findByPersonUserRef($this->eid);
  }
  
  public function executeComm()
  {
    $this->comms =  Doctrine::getTable('UsersComm')->findByPersonUserRef($this->eid);
  }
  
  public function executeLang()
  {
    $this->langs =  Doctrine::getTable('UsersLanguages')->findByUsersRef($this->eid);
  }
}
