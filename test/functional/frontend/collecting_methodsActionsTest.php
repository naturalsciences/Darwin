<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');
$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
    info('1 - Add a collecting method with a post method but not as a XMLHTTP Request')->
    get('/collecting_methods/addMethod?value=Test')->
    with('response')->begin()->
        isStatusCode(404)->
    end()->
    info('2 - Add a collecting method with a post method as a XMLHTTP Request')->
    setHttpHeader("X-Requested-With", "XMLHttpRequest")->
    post('/collecting_methods/addMethod?value=Test')->
    with('response')->begin()->
        isStatusCode(200)->
        matches('/[0-9]+/')->
    end()->
    info('3 - Try to reAdd the same collecting method.. and check we get an error')->
    setHttpHeader("X-Requested-With", "XMLHttpRequest")->
    post('/collecting_methods/addMethod?value=Test')->
    with('response')->begin()->
        isStatusCode(200)->
        matches('/duplicate key value violates unique constraint/')->
    end()
;
