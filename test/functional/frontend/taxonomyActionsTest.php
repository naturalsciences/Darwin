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
    checkElement('ul > li',4)->
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
    checkElement('ul > li',2)->
    checkElement('li:first','/Animalia/')->
    checkElement('li:last','/Duchenus/')->
  end()->

  get('/taxonomy/new/')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('legend','/Recombination/')->
    checkElement('legend:last','/Renamed/')->
    checkElement('.recombination tr',2)->
    checkElement('#taxonomy_recombination_2_record_id_2_name','')->
    checkElement('.renamed tr',1)->
  end()->

  click('Save', array('taxonomy' => array(
    'name'  => '',
    'level_ref' => '',
    'status' => '',
    'extinct'   => '',
    'parent_ref'=> '',
  )))->

  with('form')->begin()->
    hasErrors(2)->
    isError('name', 'required')->
    isError('status', 'required')->
  end()->

  click('Save', array('taxonomy' => array(
    'name'  => 'tchet savadje (tchantchès 1830)',
    'level_ref' => '48',
    'status' => 'valid', //Of course!
    'extinct'   => '',
    'parent_ref'=> '',
  )))->

  isRedirected()->
  followRedirect();

$nitems = Doctrine::getTable('Taxonomy')->getByNameLike('savadje');

  $browser->
  test()->is($nitems[0]->getName(),'tchet savadje (tchantchès 1830)', 'We have the new encoded taxa');

  $browser->
  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('legend','/Recombination/')->
    checkElement('legend:last','/Renamed/')->
    checkElement('input[value="tchet savadje (tchantchès 1830)"]')->
    checkElement('#taxonomy_current_name_record_id_2_name','')->
  end()->

  click('Save', array('taxonomy' => array(
    'name'  => 'tchet savadje (tchantchès 1830)',
    'level_ref' => '48',
    'status' => 'valid', //Of course!
    'extinct'   => '',
    'parent_ref'=> '0',
    'current_name' => array(
      'record_id_2' => $items[0]->getId(),
    ),
  )))->

  isRedirected()->
  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value="tchet savadje (tchantchès 1830)"]')->
    checkElement('#taxonomy_current_name_record_id_2_name','/Duchenus/')->
  end()->

  click('Delete')->
  isRedirected()->
  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'index')->
  end();

  $nitems = Doctrine::getTable('Taxonomy')->getByNameLike('savadje');

  $browser->
  test()->is($nitems->count(),0, 'We have no matching taxa');
