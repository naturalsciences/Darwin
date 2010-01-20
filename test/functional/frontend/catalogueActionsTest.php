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
    checkElement('table tbody tr',5)->
    checkElement('tbody tr:first span','/Falco Peregrinus/')->
  end()->
  info('executeTree');

$items = Doctrine::getTable('Taxonomy')->findByNameLike('duchesnus');

$browser->
  get('/catalogue/tree?table=taxonomy&id='.$items[0]->getId())->
  
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body > ul > li',2)->
    checkElement('body > ul li:first','/Animalia/')->
    checkElement('body > ul li:last','/Duchesnus/')->
  end()->

  info('Relation');

$items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'current_name');

$browser->  
  get('/catalogue/relation?type=rename&table=taxonomy&id=4&relid='.$items[0][0])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.catalogue_ref','Falco Peregrinus (Duchesnus Brulus 1912)')->
    checkElement('.catalogue_action_type','is renamed in :')->
    checkElement('form')->
    checkElement('#relation_catalogue_name','/Falco Peregrinus Tunstall, 1771/')->
  end();

$items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'recombined from');

$browser->  
  get('/catalogue/relation?type=recombined&table=taxonomy&id=4&relid='.$items[0][0])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.catalogue_ref','Falco Peregrinus (Duchesnus Brulus 1912)')->
    checkElement('.catalogue_action_type','is recombined from :')->
    checkElement('form')->
    checkElement('#relation_catalogue_name','/Falco Peregrinus recombinus/')->
  end()->

  info('SaveRelation')->

  get('/catalogue/relation?type=recombined&table=taxonomy&id=4')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.catalogue_ref','Falco Peregrinus (Duchesnus Brulus 1912)')->
    checkElement('.catalogue_action_type','is recombined from :')->
    checkElement('form')->
    checkElement('#relation_catalogue_name',' ')->
  end()->
  
  get('/catalogue/saveRelation?type=recombined&table=taxonomy&id=4&record_id_2=2&relation_id=')->
  with('response')->begin()->
    isStatusCode(200)->
  end();

  $browser->test()->like($browser->getResponse()->getContent(),'/ok/','Content is ok');

  $items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'recombined from');
 
  $browser->
  get('/catalogue/relation?type=recombined&table=taxonomy&id=4&relid='.$items[1][0])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.catalogue_ref','Falco Peregrinus (Duchesnus Brulus 1912)')->
    checkElement('.catalogue_action_type','is recombined from :')->
    checkElement('form')->
    checkElement('#relation_catalogue_name','/Falco Peregrinus/')->
  end()->

  info('DeleteRelation')->

  get('/catalogue/deleteRelation?relid='.$items[1][0])->
  with('response')->begin()->
    isStatusCode(200)->
  end()->

  get('/catalogue/relation?type=recombined&table=taxonomy&id=4&relid='.$items[1][0])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.catalogue_ref','Falco Peregrinus (Duchesnus Brulus 1912)')->
    checkElement('.catalogue_action_type','is recombined from :')->
    checkElement('form')->
    checkElement('#relation_catalogue_name',' ')->
  end()

;
