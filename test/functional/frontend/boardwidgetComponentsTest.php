<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData()->login('root','evil');

$browser->
    info('1 - SavedSearch')->
    get('/widgets/reloadContent?widget=savedSearch&category=board')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('ul.saved_search_widget li',2)->
        checkElement('ul.saved_search_widget li img[src$="favorite_off.png"]',1)->
        checkElement('ul.saved_search_widget li img[src$="favorite_on.png"]',1)->
        checkElement('ul.saved_search_widget li:first img[src$="favorite_on.png"]',1)->
    end()->

    info('1 - SavedSpecimens')->
    get('/widgets/reloadContent?widget=savedSpecimens&category=board')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('ul.saved_search_widget li',2)->
        checkElement('ul.saved_search_widget li img[src$="favorite_off.png"]',1)->
        checkElement('ul.saved_search_widget li img[src$="favorite_on.png"]',1)->
        checkElement('ul.saved_search_widget li:first img[src$="favorite_on.png"]',1)->
    end()
;