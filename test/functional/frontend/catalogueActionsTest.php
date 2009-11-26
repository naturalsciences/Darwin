<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('executeSearch')->
  get('/catalogue/search?searchTaxon[name]=falco&searchTaxon[table]=taxonomy')->
  with('request')->begin()->
    isParameter('module', 'catalogue')->
    isParameter('action', 'search')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul > li',4)->
    checkElement('li:first','Falco Peregrinus')->
  end()->
  info('executeTree');

$items = Doctrine::getTable('Taxonomy')->getByNameLike('duchesnus');

$browser->
  get('/catalogue/tree?table=taxonomy&id='.$items[0]->getId())->
  
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('ul > li',2)->
    checkElement('li:first','/Animalia/')->
    checkElement('li:last','/Duchesnus/')->
  end()->
  info('Relation')->
  info('DeleteRelation')->
  info('SaveRelation')

;
