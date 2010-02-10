<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/people/index')->
  
  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.search_results_content')->
    checkElement('#people_filters_family_name')->
  end()->


  click('Search', array('people_filters' => array(
    'family_name' => array('text' => 'poil'),
    'is_physical' => 1,)
    )
  )->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.results_container')->
    checkElement('.results_container .results > tbody > tr',2)->
    checkElement('.results_container .results > tbody > tr.hidden',1)->
  end()->

  get('/people/index')->
  click('Search', array('people_filters' => array(
    'family_name' => array('text' => 'ntol'),
    'is_physical' => 1,)
    )
  )->

  with('response')->begin()->
    isStatusCode(200)->
  end();

  $browser->test()->like($browser->getResponse()->getContent(),'/No Matching Items/','Content is ok');

  $browser->
  get('/people/index')->
  click('New')->

  with('response')->begin()->
    isStatusCode(200)->
  end()->

  click('Save', array('people' => array(
    'family_name'  => '',
    'additional_names' => '',
  )))->

  with('form')->begin()->
    hasErrors(1)->
    isError('family_name', 'required')->
  end()->

  click('Save', array('people' => array(
    'family_name'  => 'Dupont',
    'additional_names' => 'jr'
  )))->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect();

$nitems = Doctrine::getTable('People')->findByAdditionalNames('jr');

  $browser->
  test()->is($nitems[0]->getFamilyName(),'Dupont', 'We have the new encoded people');

  $browser->
  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()->

  click('Save', array('people' => array(
    'family_name'  => 'Dupond',
    'additional_names' => 'jr'
  )))->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value="Dupond"]')->

  end()->

  click('Delete')->
  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'people')->
    isParameter('action', 'index')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
  end();

$nitems = Doctrine::getTable('People')->findByAdditionalNames('jr');

  $browser->
  test()->is($nitems->count(),0, 'We have no matching people');
