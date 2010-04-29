<?php

class userswidgetComponents extends sfComponents
{
  public function executeAddress()
  {
    $this->addresses =  Doctrine::getTable('UsersAddresses')->fetchByUser($this->eid);
  }
  
  public function executeComm()
  {
    $this->comms =  Doctrine::getTable('UsersComm')->fetchByUser($this->eid);
  }
  
  public function executeLang()
  {
    $this->langs =  Doctrine::getTable('UsersLanguages')->getLangByUser($this->eid);
  }
  
  public function executeInfo()
  {
     $this->login_info =  Doctrine::getTable('UsersLoginInfos')->getInfoForUser($this->eid);
  }
}
