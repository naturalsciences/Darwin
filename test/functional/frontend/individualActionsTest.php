<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');
$browser->addCustomSpecimen('777','Collection test for individual','Taxon test for individual',1)->
	with('response')->isRedirected()->followRedirect()->
	with('request')->begin()->
	     click('#tab_2')->
     end()->
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
  end();
$collection_id = Doctrine::getTable('Collections')->getCollectionByName('Collection test for individual')->getId();
$taxon_id = Doctrine::getTable('Taxonomy')->getTaxonByName('Taxon test for individual',1,'/')->getId() ;
$specimen_id = Doctrine::getTable('Specimens')->GetSpecimenByRef($collection_id,$taxon_id)->getId();
$browser->addCustomIndividual($specimen_id);
