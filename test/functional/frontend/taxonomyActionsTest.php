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

  get('/taxonomy/new/')->
  with('response')->begin()->
    isStatusCode(200)->
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
  end()->

  click('Save', array('taxonomy' => array(
    'name'  => 'tchet savadje (tchantchès 1830)',
    'level_ref' => '48',
    'status' => 'valid', //Of course!
    'extinct'   => '',
    'parent_ref'=> '0',
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
