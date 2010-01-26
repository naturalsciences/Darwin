<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  get('/vernacularnames/add?id=4&table=taxonomy')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#class_vernacular_names_community option',1)->
  end()->
  
  click('Save', array('catalogue_properties' => array(
    'referenced_relation' => 'taxonomy',
    'record_id'           => '4',
    'community'       => '',
  )))->

  with('form')->begin()->
    hasErrors(1)->
    isError('community', 'required')->
  end()->

  click('Save', array('catalogue_properties' => array(
    'referenced_relation' => 'taxonomy',
    'record_id'           => '4',
    'community'       => 'Français',
    'newVal' => array('0' => array('name' => 'Faux con',
	                           'id' => '',
                                  )
                     ),
  )))->

  with('response')->begin()->
    isStatusCode(200)->
    matches('/ok/')->
  end()->  

  get('/vernacularnames/addValue?num=12')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('tr',1)->
    checkElement('tr td',2)->
  end();

  $r = Doctrine::getTable('ClassVernacularNames')->findForTable('taxonomy',4);

  $browser->
  get('/vernacularnames/addValue?num=13&id='.$r[0]->getId() )->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('tr',1)->
    checkElement('tr td',2)->
  end()
;
