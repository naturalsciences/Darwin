<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');
$Specimen = Doctrine::getTable('Specimens')->findAll();
$specId = $Specimen[0]->getId();
$browser->
  get('/individuals/edit/spec_id/'.$specId)->

  with('request')->begin()->
    isParameter('module', 'individuals')->
    isParameter('action', 'edit')->
  end()->
  info('1.1 - is everything ok on screen')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Add specimen individual')->
    checkElement('.board_col',2)->
    checkElement('.board_col:first .widget',3)->
    checkElement('.board_col:last .widget',4)->
    checkElement('.board_col:first .widget:first .widget_top_bar span','Type')->
    checkElement('.board_col:first .widget:nth-child(2) .widget_top_bar span','Sex')->
    checkElement('.board_col:first .widget:nth-child(3) .widget_top_bar span','Stage')->    
    checkElement('.board_col:last .widget:first .widget_top_bar span','Count')->
    checkElement('.board_col:last .widget:nth-child(2) .widget_top_bar span','Properties')->
    checkElement('.board_col:last .widget:nth-child(3) .widget_top_bar span','Identifications')->   
    checkElement('.board_col:last .widget:nth-child(4) .widget_top_bar span','Comments')->        
  end()
;
