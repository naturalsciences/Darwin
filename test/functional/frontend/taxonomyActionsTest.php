<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/taxonomy/edit?id=-1')->
    with('response')->begin()->
    isStatusCode(200)->
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
	'keyword_type' => 'specific_epithet',
	'keyword' => 'savadje',
      ),
      '3' => array(
	'id' => '',
	'referenced_relation' => 'taxonomy',
	'record_id' =>'',
	'keyword_type' => 'scientific_name_authorship',
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
    checkElement('#catalogue_keywords > table > tbody > tr > td:first span',"/Publication Year/")->
    checkElement('#catalogue_keywords table[alt="scientific_name_authorship"] tr',1)->
    checkElement('#catalogue_keywords table[alt="specific_epithet"] tr',1)->
    checkElement('#catalogue_keywords table[alt="pub_year"] tr',1)->
    checkElement('div#taxonomy_parent_ref_warning', 1)->
    checkElement('table.classifications_edit tfoot tr td input#taxonomy_id', 1)->
    checkElement('table.classifications_edit tfoot tr td input#taxonomy_table', 1)->
    checkElement('table.classifications_edit tfoot tr td a#searchPUL', 1)->
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

  $item = Doctrine::getTable('Taxonomy')->findOneByStatusAndLevelRef('invalid',49);

  $browser->
  info('Mimic the parenty set of the only invalid taxon of level sub_species')->
  get('/taxonomy/choose?level='.$item->getLevelRef().'&caller_id='.$item->getId())->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('form select option',2)->
    checkElement('form select option:last[text="species"]',true)->
    checkElement('form input[id="searchCatalogue_table"][value="taxonomy"]', true)->
    checkElement('form input[id="searchCatalogue_level"][value="49"]', true)->
  end()->
  info('Check that the results when clicking on search retrieve the only "one" species encoded')->
  click('Search')->
  with('response')->begin()->
    checkElement('table.results tbody tr', 1)->
  end();
