<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');
$individual = Doctrine::getTable('SpecimenIndividuals')->findOneBySpecimenRef(4);
$indivId = $individual->getId();
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

$browser->addCustomPart($indivId);
	
$browser->
  get('parts/overview/id/'.$indivId)-> 
  with('response')->begin()->
     isStatusCode(200)->
     checkElement('title', 'Parts overview')->
     checkElement('table.catalogue_table tr.parts',1)->
     checkElement('table.catalogue_table tr.parts td:nth-child(3)','specimen')->
     checkElement('table.catalogue_table tr.parts td:nth-child(7)','Test for parts')->
  end()->
  
  info('Add a new part')->
  click('#tab_4')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Add Part')->
  end()->

  click('Save',array(
    'specimen_parts' => array(
      'building' => 'Vestel',
      'floor' => '12a',
      'room' => '14',
      'row' => '50',
      'shelf' => 'xx15',
      'specimen_status' => 'good state',
      'complete' => '1',
    )
  ))->
  followRedirect()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title', 'Parts overview')->
    checkElement('table.catalogue_table tr.parts',2)->
    checkElement('table.catalogue_table tr.parts:last td:nth-child(3)','specimen')->
    checkElement('table.catalogue_table tr.parts:last td:nth-child(7)','')->
    checkElement('table.catalogue_table tr.parts:last td:nth-child(4)','14')->
  end()->

  click('table.catalogue_table tr.parts:last td:nth-child(9) a')->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('title','Edit part')->
    checkElement('#specimen_parts_building option',2)->
    checkElement('#specimen_parts_building option[selected]','Vestel')->
  end()->

  click('#spec_part_delete')->

  with('response')->begin()->
     isStatusCode(200)->
  end();

$browser->test()->like($browser->getResponse()->getContent(),'/ok/','Content is ok');

$browser->
  get('parts/overview/id/'.$indivId)-> 
  with('response')->begin()->
     isStatusCode(200)->
     checkElement('title', 'Parts overview')->
     checkElement('table.catalogue_table tr.parts',1)->
     checkElement('table.catalogue_table tr.parts td:nth-child(3)','specimen')->
     checkElement('table.catalogue_table tr.parts td:nth-child(7)','Test for parts')->
  end()

;
  
  
