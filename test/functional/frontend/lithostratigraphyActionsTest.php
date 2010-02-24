<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/lithostratigraphy/index')->
  
  with('request')->begin()->
    isParameter('module', 'lithostratigraphy')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('form#catalogue_filter table#search tbody td:first input#searchCatalogue_name', 1)->
    checkElement('form#catalogue_filter table#search tbody td:first input#searchCatalogue_table', 1)->
    checkElement('form#catalogue_filter table#search tbody td:nth-child(2) select#searchCatalogue_level_ref', 1)->
    checkElement('div.new_link', 1)->
  end()->
  info('Search without criterias')->
  click('Search')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('div[class="pager paging_info"] table tr td:nth-child(2)', '/8/')->
    checkElement('table.results tbody tr:nth-child(2) td:nth-child(2)', '/Invalid/')->
  end()->
  info('Edit this record')->
  click('table.results tbody tr:nth-child(2) td.edit a')->
  with('response')->begin()->
    isStatusCode(200)->
    setField('lithostratigraphy[name]', '')->
    setField('lithostratigraphy[level_ref]', '')->
  end()->
  click('Save')->
  with('form')->begin()->
    hasErrors(2)->
    isError('name', 'required')->
    isError('level_ref', 'required')->
    setField('lithostratigraphy[name]', 'Proutprout')->
    setField('lithostratigraphy[level_ref]', '65')->
  end()->
  click('Save')->
  info('Test the record has been saved in DB');

$unit = Doctrine::getTable('lithostratigraphy')->findOneByName('Proutprout');
$browser->
  test()->is($unit->getName(),'Proutprout', 'We have the new encoded unit');
$browser->
  info('Create a new record and save it then test its existence in DB')->
  get('lithostratigraphy/new')->
  with('response')->begin()->
    isStatusCode('200')->
  end()->
  click('Save', array('lithostratigraphy'=>array('name'=>'Paxien', 'level_ref'=>'66', 'parent_ref'=>$unit->getId())));
$unit = Doctrine::getTable('lithostratigraphy')->findOneByName('Paxien');
$browser->
  test()->is($unit->getName(),'Paxien', 'We have the new encoded unit');