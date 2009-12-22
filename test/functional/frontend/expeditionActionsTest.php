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
    setField('#searchExpedition_from_date_month', '10')->
  end()->
/*

  @TODO: Correct this part to test what should be displayed by the search - called or not with the is_choose option 
  @TODO: Also testing the sort and the pager

  info('Post waiting for a "Year missing" error')->
  post('/expedition/search?searchExpedition[from_date][month]=10')->
  with('response')->
  begin()->
    isStatusCode()->
    checkElement('#error_list li', 1)->
  end();  
*/
  info('Get new record')->
  click('New')->
  with('response')->
  begin()->
    isStatusCode()->
    checkElement('h1', 'New Expedition')->
    checkElement('h1[class="edit_mode"]', true)->
    checkElement('form[class="edition"]', true)->
    checkElement('#expeditions_expedition_from_date_day > option:first_element', 'dd')->
    checkElement('#expeditions_expedition_from_date_month > option:first_element', 'mm')->
    checkElement('#expeditions_expedition_from_date_year > option:first_element', 'yyyy')->
    checkElement('#expeditions_expedition_to_date_day > option:first_element', 'dd')->
    checkElement('#expeditions_expedition_to_date_month > option:first_element', 'mm')->
    checkElement('#expeditions_expedition_to_date_year > option:first_element', 'yyyy')->
    checkElement('form a', 'Cancel')->
    checkElement('form input[type="submit"][value="Save"]', 1)->
  end()->
  info('Try to save without data')->
  click('Save', 
        array('expeditions' => array('name'  => '',
                                     'expedition_from_date' => '',
                                     'expedition_to_date' => ''
                                    )
             )
       )->
  with('form')->
  begin()->
    hasErrors(1)->
    isError('name', 'required')->
  end()->
  info('Try to save with only "Year missing" error')->
  click('Save', 
        array('expeditions' => array('name'  => 'Antarctica 2000',
                                     'expedition_from_date' => array('day'=>'','month'=>'05','year'=>''),
                                     'expedition_to_date' => array('day'=>'','month'=>'','year'=>'')
                                    )
             )
       )->
  with('form')->
  begin()->
    hasErrors(1)->
    isError('expedition_from_date', 'year_missing')->
  end()->
  info('Try to save with only "Month missing" error')->
  click('Save', 
        array('expeditions' => array('name'  => 'Antarctica 2000',
                                     'expedition_from_date' => array('day'=>'05','month'=>'','year'=>'2000'),
                                     'expedition_to_date' => array('day'=>'','month'=>'','year'=>'')
                                    )
             )
       )->
  with('form')->
  begin()->
    hasErrors(1)->
    isError('expedition_from_date', 'month_missing')->
  end()->
  info('Try to save with date to bellow date from global error')->
  click('Save', 
        array('expeditions' => array('name'  => 'Antarctica 2000',
                                     'expedition_from_date' => array('day'=>'05','month'=>'12','year'=>'2000'),
                                     'expedition_to_date' => array('day'=>'05','month'=>'12','year'=>'1999')
                                    )
             )
       )->
  with('form')->
  begin()->
    hasGlobalError("invalid")->
  end()->
  info('Save with correct data and check everything was saved correctly')->
  click('Save', 
        array('expeditions' => array('name'  => 'Antarctica 2000',
                                     'expedition_from_date' => array('day'=>'05','month'=>'12','year'=>'1999'),
                                     'expedition_to_date' => array('day'=>'05','month'=>'12','year'=>'2000')
                                    )
             )
       )->
  with('response')->
  begin()->
    isRedirected()->
  end()->
  followRedirect()->
  with('request')->begin()->
    isParameter('module', 'expedition')->
    isParameter('action', 'edit')->
  end()->
  with('response')->
  begin()->
    checkElement('form input[name="expeditions[name]"][value="Antarctica 2000"]', 1)->
  end();

  $items = Doctrine::getTable('Expeditions')->findByName('Antarctica 2000');

  $browser->
  info('Check new record has been saved in DB')->
  test()->is($items[0]->getName(),'Antarctica 2000', 'We have the new encoded taxa');
  $browser->
  test()->is($items[0]->getExpeditionFromDate(),array('year'=>1999, 'month'=>12, 'day'=>5, 'hour'=>'', 'minute'=>'', 'second'=>''), 'We have the new encoded taxa');
  $browser->
  test()->is($items[0]->getExpeditionToDate(),array('year'=>2000, 'month'=>12, 'day'=>5, 'hour'=>'', 'minute'=>'', 'second'=>''), 'We have the new encoded taxa');

  $browser->
  info('Test the delete action...')->
  get('/expedition/edit/id/'. $items[0]->getId())->
  click('Delete')->
  with('response')->begin()->
    isRedirected()->
  end()->

  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'expedition')->
    isParameter('action', 'index')->
  end();

  $items = Doctrine::getTable('Expeditions')->findByName('Antarctica 2000');

  $browser->
  test()->is($items->count(),0, 'Expedition well deleted');
