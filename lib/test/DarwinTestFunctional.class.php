<?php

class DarwinTestFunctional extends sfTestFunctional
{
  public function loadData($configuration)
  {
    new sfDatabaseManager($configuration);
    self::initiateDB($configuration);
    Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');
    return $this;
  }

  public static function initiateDB()
  {
    $conn = Doctrine_Manager::connection();
    $conn->exec("SELECT nextval('taxonomy_id_seq')");
    $conn->exec("SELECT setval('taxonomy_id_seq',10000)");
    $conn->exec("SELECT nextval('expeditions_id_seq')");
    $conn->exec("SELECT setval('expeditions_id_seq',10000)");
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
