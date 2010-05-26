<?php

class myUser extends sfBasicSecurityUser
{
  public function getId()
  {
    return $this->getAttribute('db_user_id');
  }
  public function getDbUserType()
  {
    return $this->getAttribute('db_user_type');
  }
}
