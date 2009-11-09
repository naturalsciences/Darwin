<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  get('/people/search')->

  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'search')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input#people_name')->
  end()->

 get('/people/complete?name=Pollux')->

  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'complete')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('li')-> // @TODO must be changed 
  end()
;
