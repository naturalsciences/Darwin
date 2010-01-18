<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  get('/synonym/index')->

  with('request')->begin()->
    isParameter('module', 'synonym')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body', '!/This is a temporary page/')->
  end()
;
