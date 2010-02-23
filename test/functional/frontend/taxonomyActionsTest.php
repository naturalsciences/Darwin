<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/taxonomy/edit?id=-1')->
    with('response')->begin()->
    isStatusCode(404)->
  end()->
  get('/taxonomy/index')->
  
  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.search_results_content')->
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
    hasErrors(3)->
    isError('name', 'required')->
    isError('level_ref', 'required')->
    isError('status', 'required')->
  end()->

  click('Save', array('taxonomy' => array(
    'name'  => 'tchet savadje (tchantchès 1830)',
    'level_ref' => '48',
    'status' => 'valid', //Of course!
    'extinct'   => '',
    'parent_ref'=> '',
  )))->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect();

$nitems = Doctrine::getTable('Taxonomy')->findByName('tchet savadje (tchantchès 1830)');

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
    'newVal' => array(
      '0' => array(
	'id' => '',
	'referenced_relation' => 'taxonomy',
	'record_id' =>'',
	'keyword_type' => 'name_part',
	'keyword' => 'tchet',
      ),
      '1' => array(
	'id' => '',
	'referenced_relation' => 'taxonomy',
	'record_id' =>'',
	'keyword_type' => 'pub_year',
	'keyword' => '1830',
      ),
      '2' => array(
	'id' => '',
	'referenced_relation' => 'taxonomy',
	'record_id' =>'',
	'keyword_type' => 'name_part',
	'keyword' => 'savadje',
      ),
      '3' => array(
	'id' => '',
	'referenced_relation' => 'taxonomy',
	'record_id' =>'',
	'keyword_type' => 'author',
	'keyword' => 'tchantchès',
      ),
    )
  )))->

  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value="tchet savadje (tchantchès 1830)"]')->
    checkElement('#catalogue_keywords > table >  tbody > tr',3)->
    checkElement('#catalogue_keywords > table > tbody > tr > td:first span',"/Author part/")->
    checkElement('#catalogue_keywords table[alt="author"] tr',1)->
    checkElement('#catalogue_keywords table[alt="name_part"] tr',2)->
    checkElement('#catalogue_keywords table[alt="pub_year"] tr',1)->
  end()->

  click('Delete')->
  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'taxonomy')->
    isParameter('action', 'index')->
  end();

  $nitems = Doctrine::getTable('Taxonomy')->findByName('tchet savadje (tchantchès 1830)');

  $browser->
  test()->is($nitems->count(),0, 'We have no matching taxa');
