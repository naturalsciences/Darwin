<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData()->login('root','evil');

$browser->
    info('1 - AcquisitionCategories')->
    get('/widgets/reloadContent?widget=acquisitionCategory&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('select',1)->
        checkElement('select option',12)->
        checkElement('select option:first','Undefined')->
    end()->
    info('2 - CollectionRef')->
    get('/widgets/reloadContent?widget=refCollection&category=specimen')->
    with('response')->begin()->
        isStatusCode(200)->
        checkElement('input[type="hidden"]',1)->
    end();;