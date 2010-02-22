<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new sfTestFunctional(new sfBrowser());

$browser->
  get('/cataloguepeople/people?table=taxonomy&rid=4')->

  with('request')->begin()->
    isParameter('module', 'cataloguepeople')->
    isParameter('action', 'people')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;
