<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData()->login('root','evil');

$browser->
  info('1 - Add hidden widget')->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.board_col:last .widget',false)->
  end()->
  
  info('1.1 - no widget when it is the bad category')->
  get('/widgets/changeStatus?category=specimen&widget=addTaxon&status=visible')->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.board_col:last .widget',false)->
  end()->


  info('1.2 - the other category is still not changed')->
  get('/specimen/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',1)->
    checkElement('.board_col:last .widget',false)->
  end()->
  
  info('1.3 - add widget when the category is ok')->
  get('/widgets/changeStatus?category=board&widget=addTaxon&status=visible')->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',3)->
    checkElement('.board_col:last .widget',false)->
  end()->

//-------------------- 
  info('2 - hide a visible widget')->
  get('/widgets/changeStatus?category=board&widget=addTaxon&status=hidden')->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.board_col:last .widget',false)->
  end()->
  info('2.1 - hide a hidden widget')->
  get('/widgets/changeStatus?category=board&widget=addTaxon&status=hidden')->
  with('response')->begin()->
    isStatusCode(200)->
  end()->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.board_col:last .widget',false)->
  end()->

//-------------------- 
  info('3 - expand a widget')->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.widget_content.hidden',1)->
  end()->

  get('/widgets/changeStatus?category=board&widget=savedSearch&status=close')->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.widget_content.hidden',2)->
  end()->

  get('/widgets/changeStatus?category=board&widget=savedSearch&status=open')->
  get('/board/index')->
  with('response')->begin()->
    checkElement('.board_col:first .widget',2)->
    checkElement('.widget_content.hidden',1)->
  end()
;