<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');
$individual = Doctrine::getTable('SpecimenIndividuals')->findAll();
$indivId = $individual[0]->getId();
$browser->
  get('/parts/edit/indid/'.$indivId)->

  with('request')->begin()->
    isParameter('module', 'parts')->
    isParameter('action', 'edit')->
  end()->
  info('1.1 - is everything ok on screen')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Add Part')->
    checkElement('.board_col',2)->
    checkElement('.board_col:first .widget',4)->
    checkElement('.board_col:last .widget',6)->
    checkElement('.board_col:first .widget:first .widget_top_bar span','Part')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_top_bar span','Count')->
    checkElement('.board_col:first .widget:nth-child(3) .widget_top_bar span','Container')->    
    checkElement('.board_col:last .widget:first .widget_top_bar span','Complete')->
    checkElement('.board_col:last .widget:nth-child(2) .widget_top_bar span','Localisation')->
    checkElement('.board_col:last .widget:nth-child(3) .widget_top_bar span','Properties')->
    checkElement('.board_col:last .widget:nth-child(4) .widget_top_bar span','Insurances')->
    checkElement('.board_col:last .widget:nth-child(5) .widget_top_bar span','Maintenance')->    
    checkElement('.board_col:last .widget:nth-child(6) .widget_top_bar span','Comments')->        
  end()
;
