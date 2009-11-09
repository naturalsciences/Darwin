<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/taxonomy/index')->
  
  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.tree')->
    checkElement('.search')->
  end()->

  info('executeSearch')->

  get('/taxonomy/search?searchTaxon[name]=falco')->
  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'search')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul > li',2)->
    checkElement('li:first','Falco Peregrinus')->
  end()->

 get('/taxonomy/search?searchTaxon[name]=falco&searchTaxon[level]=48')-> //species

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul > li',1)->
    checkElement('li:first','Falco Peregrinus')->
  end()->

  info('executeTree');
$items = Doctrine::getTable('Taxonomy')->getByNameLike('duchenus');

$browser->
  get('/taxonomy/tree?id='.$items[0]->getId())->
  
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul > li',3)->
    checkElement('li:first','/Animalia/')->
    checkElement('li:last','/Duchenus/')->
  end()
;