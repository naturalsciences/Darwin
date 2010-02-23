<?php
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('executeSearch')->
  get('/catalogue/search?searchCatalogue[name]=falco&searchCatalogue[table]=taxonomy')->
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

$items = Doctrine::getTable('Taxonomy')->findByName('Falco Peregrinus (Duchesnus Brulus 1912)');

$browser->
  get('/catalogue/tree?table=taxonomy&id='.$items[0]->getId())->
  
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('body > ul > li',3)->
    checkElement('body > ul li:first','/Eucaryota/')->
    checkElement('body > ul li:last','/Duchesnus/')->
  end()->

  info('Relation');

$items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'current_name');

$browser->  
  get('/catalogue/relation?type=rename&table=taxonomy&rid=4&id='.$items[0]['id'])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('label[for="catalogue_relationships_record_id_2"]','Renamed in')->
    checkElement('form')->
    checkElement('#catalogue_relationships_record_id_2_name','/Falco Peregrinus Tunstall, 1771/')->
  end();

$items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'recombined from');

$browser->  
  get('/catalogue/relation?type=recombined&table=taxonomy&rid=4&id='.$items[0]['id'])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('label[for="catalogue_relationships_record_id_2"]','Recombined From')->
    checkElement('form')->
    checkElement('#catalogue_relationships_record_id_2_name','/Falco Peregrinus recombinus/')->
  end()->

  info('SaveRelation')->

  get('/catalogue/relation?type=recombined&table=taxonomy&rid=4')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('label[for="catalogue_relationships_record_id_2"]','Recombined From')->
    checkElement('form')->
    checkElement('#catalogue_relationships_record_id_2_name','')->
  end()->
  
  click('Save', 
    array('catalogue_relationships' => array(
	'record_id_2'  => '2',
        'referenced_relation' => 'taxonomy',
	'relationship_type' => 'recombined from',
	'record_id_1' =>'4',
      )
    )
  );
  $browser->test()->like($browser->getResponse()->getContent(),'/ok/','Content is ok');

  $items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'recombined from');
 
  $browser->
  get('/catalogue/relation?type=recombined&table=taxonomy&rid=4&id='.$items[1]['id'])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('label[for="catalogue_relationships_record_id_2"]','Recombined From')->
    checkElement('form')->
    checkElement('#catalogue_relationships_record_id_2_name','/Falco Peregrinus/')->
  end()->

  info('DeleteRelated')->

  get('/catalogue/deleteRelated?table=catalogue_relationships&id='.$items[1]['id'])->
  with('response')->begin()->
    isStatusCode(200)->
  end()->

  get('/catalogue/relation?type=recombined&table=taxonomy&rid=4&id='.$items[1]['id'])->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('label[for="catalogue_relationships_record_id_2"]','Recombined From')->
    checkElement('form')->
    checkElement('#relation_catalogue_name','')->
  end();
