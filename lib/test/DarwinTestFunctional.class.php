<?php

class DarwinTestFunctional extends sfTestFunctional
{
  public function loadData()
  {
    Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');
    return $this;
  }

  public function login($user,$pass)
  {
    $this->
      info('** initial login **')->
      get('account/login')->
      click('Log in',array('login' => array(
        'username' => 'root',
        'password' => 'evil'
        )))->
      with('user')->begin()->
        isAuthenticated(true)->
      end()
    ;
    return $this;
  }
  
}