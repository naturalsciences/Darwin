<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
    info('1 - Rename')->
     get('/widgets/reloadContent?widget=relationRename&category=catalogue_taxonomy&eid=4')->
     with('response')->begin()->
        isStatusCode(200)->
        checkElement('table tr',1)->
        checkElement('table tr td:first a.link_catalogue','/Falco Peregrinus Tunstall/')->
	checkElement('img',0)->
    end()->

    info('1 - Recombined')->
     get('/widgets/reloadContent?widget=relationRecombination&category=catalogue_taxonomy&eid=4')->
     with('response')->begin()->
        isStatusCode(200)->
        checkElement('table tr',1)->
        checkElement('table tr td:first a.link_catalogue','/recombinus/')->
	checkElement('img',1)->
    end();

 $items = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('taxonomy', 4, 'current_name');

$browser->
    get('/catalogue/deleteRelation?relid='.$items[0][0])->
    with('response')->begin()->
      isStatusCode(200)->
    end()->

     get('/widgets/reloadContent?widget=relationRename&category=catalogue_taxonomy&eid=4')->
     with('response')->begin()->
        isStatusCode(200)->
        checkElement('table tr',0)->
	checkElement('img',1)->
    end();