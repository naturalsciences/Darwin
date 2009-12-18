<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/expedition/index')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', 'Expedition List')->
    checkElement('.search_expedition')->
  end()
;
