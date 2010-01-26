<?php

class peoplewidgetComponents extends sfComponents
{
  public function executeComment()
  {}
  public function executeProperties()
  {}

  public function executeAddress()
  {
    $this->addresses =  Doctrine::getTable('PeopleAddresses')->findByPersonUserRef($this->eid);
  }
  
  public function executeComm()
  {
    $this->comms =  Doctrine::getTable('PeopleComm')->findByPersonUserRef($this->eid);
  }
}
/**
PeopleRelationships
*/