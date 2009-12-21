<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/expedition/index')->
  with('response')->
  begin()->
    isStatusCode()->
    checkElement('h1', 'Expedition List')->
    checkElement('#searchExpedition_name', '')->
    checkElement('#searchExpedition_from_date_day > option:first_element', 'dd')->
    checkElement('#searchExpedition_from_date_month > option:first_element', 'mm')->
    checkElement('#searchExpedition_from_date_year > option:first_element', 'yyyy')->
    checkElement('#searchExpedition_to_date_day > option:first_element', 'dd')->
    checkElement('#searchExpedition_to_date_month > option:first_element', 'mm')->
    checkElement('#searchExpedition_to_date_year > option:first_element', 'yyyy')->
    checkElement('form input[type="submit"]', 1)->
  end()->
  click('#expedition_search', 
        array('search_expedition' => array('name'=>'',
                                           'from_date'=>array('day'=>'',
                                                              'month'=>'10',
                                                              'year'=>''
                                                             )
                                          )
             )
       )->
  with('form')->
  begin()->
    hasErrors(1)->
    isError('from_date', 'Year missing.')->
  end();

