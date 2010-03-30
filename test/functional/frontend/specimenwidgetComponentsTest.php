<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
    info('1 - AcquisitionCategories')->
    get('/widgets/reloadContent?widget=acquisitionCategory&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('select',4)->
        checkElement('select:first option',12)->
        checkElement('select:first option:first','Undefined')->
    end()->
    info('2 - CollectionRef')->
    get('/widgets/reloadContent?widget=refCollection&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('input[type="hidden"]',1)->
    end()->
    get('/widgets/reloadContent?widget=tool&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('#specimen_collecting_tool option',3)->
        checkElement('#specimen_collecting_tool option:first','')->
        checkElement('.add_item_button')->
    end()->

    get('/widgets/reloadContent?widget=method&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('#specimen_collecting_method option',3)->
        checkElement('#specimen_collecting_method option:first','')->
        checkElement('.add_item_button')->
    end();
