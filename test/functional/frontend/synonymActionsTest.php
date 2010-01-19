<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Checks')->

  get('/synonym/checks?table=taxonomy&id=4&type=synonym')->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/1/')->
  end()->

  get('/synonym/checks?table=taxonomy&id=5&type=isonym')->
  with('response')->begin()->
    isStatusCode(200)->
    matches('/0/')->
  end()->

  info('Add')->
  get('/synonym/add?table=taxonomy&id=4')->

  with('request')->begin()->
    isParameter('module', 'synonym')->
    isParameter('action', 'add')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#syn_screen form.edition')->
    checkElement('#syn_screen form.edition')->
    checkElement('.merge_question')->
    checkElement('.search_box')->
    checkElement('#classification_synonymies_group_name option',3)->
  end()->

  click('Save', array('classification_synonymies' => array(
    'group_name' => 'synonym',
    'order_by' => '0',
    'merge' => '0',
    'referenced_relation' => 'taxonomy'
    ))
  )->

  with('form')->begin()->
    hasErrors(2)->
    isError('merge', 'invalid')->
    isError('record_id', 'required')->
  end()->

  click('Save', array('classification_synonymies' => array(
    'group_name' => 'synonym',
    'order_by' => '0',
    'record_id' => '2',
    'merge' => '1',
    'referenced_relation' => 'taxonomy'
    ))
  )->
  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    matches('/ok/')->
  end();

$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(3);
$browser->test()->is(count($syn), 4, 'There are 4 synonyms');


$browser->
  info('Edit')->
  get('/synonym/edit?table=taxonomy&id=4&group_id=3')->

  with('request')->begin()->
    isParameter('module', 'synonym')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.edit_synon tbody tr', 4)->
    checkElement('.edit_synon tbody tr:first td:contains(\'Duchesnus\')')->
    checkElement('.edit_synon tbody tr:last td:contains(\'eliticus\')')->
    checkElement('.edit_synon tbody tr:first .widget_row_delete img')->
    checkElement('.edit_synon tbody tr:last .widget_row_delete img',false)->
  end()->
  
  
  click('Save', array('synonym_edit' => array(
    'orders' => ',3,6,2,7',
    'basionym_id' => '',
    ))
  )->

  with('form')->begin()->
    hasErrors(false)->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    matches('/ok/')->
  end()->

  get('/synonym/edit?table=taxonomy&id=4&group_id=3')->

  with('request')->begin()->
    isParameter('module', 'synonym')->
    isParameter('action', 'edit')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.edit_synon tbody tr', 4)->
    checkElement('.edit_synon tbody tr:last td:contains(\'eliticus\')')->
    checkElement('.edit_synon tbody tr:first td:contains(\'recombinus\')')->
    checkElement('.edit_synon tbody tr.syn_id_2 .widget_row_delete img')->
    checkElement('.edit_synon tbody tr:first .widget_row_delete img',false)->
  end()->

  info('Delete')->
  get('/synonym/delete?table=taxonomy&id=3')->

  with('response')->begin()->
    isStatusCode(200)->
    matches('/ok/')->
  end();

$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(3);
$browser->test()->is(count($syn), 3, 'One synonym is deleted');