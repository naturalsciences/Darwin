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
}
/**
PeopleRelationships
PeopleComm
PeopleAddresses
*/