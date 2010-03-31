<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('1 - Specimen screen')->
  get('/specimen/new')->

  with('request')->begin()->
    isParameter('module', 'specimen')->
    isParameter('action', 'new')->
  end()->

  info('1.1 - is everything ok on screen')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Add Specimens')->
    checkElement('.board_col',2)->
    checkElement('.board_col:first .widget',2)->
    checkElement('.board_col:last .widget',4)->
    checkElement('.board_col:first .widget:first .widget_top_bar span','Collection')->
  end()
;
