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
    $conn->exec("SELECT nextval('chronostratigraphy_id_seq')");
    $conn->exec("SELECT setval('chronostratigraphy_id_seq',10000)");
    $conn->exec("SELECT nextval('lithostratigraphy_id_seq')");
    $conn->exec("SELECT setval('lithostratigraphy_id_seq',10000)");
    $conn->exec("SELECT nextval('mineralogy_id_seq')");
    $conn->exec("SELECT setval('mineralogy_id_seq',10000)");
    $conn->exec("SELECT nextval('lithology_id_seq')");
    $conn->exec("SELECT setval('lithology_id_seq',10000)");
    $conn->exec("SELECT nextval('expeditions_id_seq')");
    $conn->exec("SELECT setval('expeditions_id_seq',10000)");
    $conn->exec("SELECT nextval('classification_synonymies_id_seq')");
    $conn->exec("SELECT setval('classification_synonymies_id_seq',1)");
    $conn->exec("SELECT nextval('gtu_id_seq')");
    $conn->exec("SELECT setval('gtu_id_seq',1)");
    $conn->exec("SELECT nextval('identifications_id_seq')");
    $conn->exec("SELECT setval('identifications_id_seq',10000)");

  }

  public function login($user,$pass)
  {
    $this->
      info('** initial login **')->
      get('account/login')->
      click('#login_page input[type="submit"]',array('login' => array(
        'username' => $user,
        'password' => $pass
        )))->
      with('user')->begin()->
        isAuthenticated(true)->
      end()
    ;
    return $this;
  }
  
}
