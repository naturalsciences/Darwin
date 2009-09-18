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
        checkElement('select option',3)->
        checkElement('select option:first','/^$/')->
        checkElement('select option:nth-child(2)','expedition')->
        checkElement('select option:nth-child(3)','thievery')->
    end();