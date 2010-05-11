<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new DarwinTestFunctional(new sfBrowser());
$browser->loadData($configuration)->login('root','evil');

$browser->
  info('Index')->
  get('/collection/index')->

  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('h1', 'Collection List')->
    checkElement('h2:last','UGMM')->
    checkElement('.treelist:first > ul > li',1)->
    checkElement('.treelist:first > ul > li:first span','/Vertebrates/')->
    checkElement('.treelist:first > ul > li:first span a img')->
    checkElement('.treelist:first > ul > li:first > ul > li',2)->
    checkElement('.treelist:first > ul > li:first > ul > li:first span', '/Amphibia/')->
    checkElement('.treelist:last > ul > li',1)->
  end()->
  
  info('New')->
  get('/collection/new')->

  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'new')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('Save', array('collections' => array(
    'name'            => '',
    'institution_ref' => '',
    'collection_type' => '',
    'code'            => '',
    'main_manager_ref'=> '',
    'parent_ref'      => '',
  )))->

  with('form')->begin()->
    hasErrors(5)->
    isError('name', 'required')->
    isError('code', 'required')->
    isError('institution_ref', 'required')->
    isError('collection_type', 'required')->
    isError('main_manager_ref', 'required')->
  end()->

  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'create')->
  end()->
  
  click('Save', array('collections' => array(
    'name'            => 'Paléonotologie',
    'institution_ref' => Doctrine::getTable('People')->findOneByFamilyName('Institut Royal des Sciences Naturelles de Belgique')->getId(),
    'collection_type' => 'mix',
    'code'            => 'paleo',
    'main_manager_ref'=> Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId(),
    'parent_ref'      => '',
  )))->
  followRedirect()->
  
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.treelist:first > ul > li',2)->
  end()->
  
  info('Edit')->
  click('span > a', array(), array('position' => 5))->
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'edit')->
  end()->

   with('response')->begin()->
    isStatusCode(200)->
    checkElement('input[value="Paléonotologie"]')->
  end()->
  click('Save',array('collections' => 
    array(
    'name'            => 'Paléonotologie',
    'institution_ref' => Doctrine::getTable('People')->findOneByFamilyName('UGMM')->getId(),
    'collection_type' => 'mix',
    'code'            => 'paleo',
    'main_manager_ref'=> Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId(),
    'parent_ref'      => Doctrine::getTable('Collections')->findOneByName('Molusca')->getId(),
        )))->
  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'index')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.treelist:first > ul > li',1)->
    checkElement('.treelist:last > ul > li',1)->
    checkElement('.treelist:last > ul > li > ul > li',1)->
  end()->


 info('Delete')->
  click('span > a', array(), array('position' => 6))->
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'edit')->
  end()->
  click('Delete')->
  followRedirect()->

  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'index')->
  end()->
  
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('.treelist:first > ul > li',1)->
  end()
;
$user_id = Doctrine::getTable('users')->getUserByPassword('root','evil')->getId() ;
$collection_id = Doctrine::getTable('collections')->getCollectionByName('Vertebrates')->getId() ;

$browser->
  info('sub collection right')->
  get('collection/rights/user_ref/'.$user_id.'/collection_ref/'.$collection_id)->
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'rights')->
  end()->
with('response')->begin()->
    isStatusCode(200)->
    checkElement('.treelist:first > ul > li:first span','/Amphibia/')->
    end()->
 
  click('#submit',  array('sub_collection' => array(
    'SubCollectionsRights' => array(
    0 => array(
    'user_ref'       => $user_id,
    'collection_ref' => Doctrine::getTable('collections')->getCollectionByName('Amphibia')->getId(),
    'check_right' => true),
    1 => array(
    'user_ref'       => $user_id,
    'collection_ref' => Doctrine::getTable('collections')->getCollectionByName('Aves')->getId(),
    'check_right' => false)
    )
    )));
    
$browser->
  info('sub collection right')->
  get('collection/edit/id/'.Doctrine::getTable('collections')->getCollectionByName('Amphibia')->getId())->
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'edit')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#'.$user_id.' > td:first > label',Doctrine::getTable('users')->findUser($user_id)->getFormatedName())->
    end()->
    
  get('collection/edit/id/'.Doctrine::getTable('collections')->getCollectionByName('Aves')->getId())->
  with('request')->begin()->
    isParameter('module', 'collection')->
    isParameter('action', 'edit')->
  end()->
  with('response')->begin()->
    isStatusCode(200)->
    checkElement('#'.$user_id,null)->
    end();
