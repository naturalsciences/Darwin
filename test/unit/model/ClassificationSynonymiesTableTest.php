<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(18, new lime_output_color());

$t->info('findGroupsIdsForRecord');
$syns = Doctrine::getTable('ClassificationSynonymies')->findGroupsIdsForRecord('taxonomy',4);
$t->is_deeply($syns, array(1,2), 'We have ids for the group of a record');
$syns = Doctrine::getTable('ClassificationSynonymies')->findGroupsIdsForRecord('taxonomy',5);
$t->is_deeply($syns, array(1), 'We have ids for the only group of another record');
$syns = Doctrine::getTable('ClassificationSynonymies')->findGroupsIdsForRecord('taxonomy',-1);
$t->is_deeply($syns, array(), 'We don\'t have id for not existing group');

$t->info('findAllForRecord');
$records = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord('taxonomy', 4);

$t->is_deeply($records,
  array(
    'homonym' => array (
      0 => array(
	'id' => 5,
	'record_id' => 3,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus Tunstall, 1771',
	'item_id' => 3,
      ),
      1 => array (
	'id' => 4,
	'record_id' => 4,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 2,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
    ),
    'synonym' => array (
      0 => array (
	'id' => 2,
	'record_id' => 4,
	'group_id' => 1,
	'is_basionym' => true,
	'order_by' => 0,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
      1 => array (
	'id' => 3,
	'record_id' => 5,
	'group_id' => 1,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus recombinus',
	'item_id' => 5,
	),
      ),
    ),'GetAll records of all group for this item');

$records = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord('taxonomy', 3);
$t->is_deeply($records,
  array(
    'homonym' => array (
      0 => array (
	'id' => 5,
	'record_id' => 3,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus Tunstall, 1771',
	'item_id' => 3,
      ),
      1 => array(
	'id' => 4,
	'record_id' => 4,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 2,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
    )
  ),'GetAll records for this item (only homonym)');

$records = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord('taxonomy', 4, array(2));

$t->is_deeply($records,
 array(
    'homonym' => array (
      0 => array(
	'id' => 5,
	'record_id' => 3,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus Tunstall, 1771',
	'item_id' => 3,
      ),
      1 => array (
	'id' => 4,
	'record_id' => 4,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 2,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
    )
  ),'Get only homonym for the first record');


$t->info('findGroupnames');
$groups = Doctrine::getTable('ClassificationSynonymies')->findGroupnames();

$t->is_deeply($groups, array('synonym' => 'Synonyms', 'isonym' => 'Isonyms', 'homonym' => 'Homonyms'),'Get all groups');

$t->info('findNextGroupId');
$id = Doctrine::getTable('ClassificationSynonymies')->findNextGroupId();
$id2 = Doctrine::getTable('ClassificationSynonymies')->findNextGroupId();

$t->is($id+1, $id2,'Get Next Id');


$t->info('findGroupIdFor');
$group = Doctrine::getTable('ClassificationSynonymies')->findGroupIdFor('taxonomy', 4, 'homonym');
$t->is(2, $group,'We get the group id for homonym');

$group = Doctrine::getTable('ClassificationSynonymies')->findGroupIdFor('taxonomy', 4, 'synonym');
$t->is(1, $group,'We get the group id for synonym');

$group = Doctrine::getTable('ClassificationSynonymies')->findGroupIdFor('taxonomy', 3, 'synonym');
$t->is(0 ,$group,'We get no group id for synonym');


$t->info('saveOrderAndResetBasio');
Doctrine::getTable('ClassificationSynonymies')->saveOrderAndResetBasio('4,5');

$records = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord('taxonomy', 3);
$t->is_deeply($records,
  array(
    'homonym' => array (
      0 => array(
	'id' => 4,
	'record_id' => 4,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
      1 => array (
	'id' => 5,
	'record_id' => 3,
	'group_id' => 2,
	'is_basionym' => false,
	'order_by' => 2,
	'name' => 'Falco Peregrinus Tunstall, 1771',
	'item_id' => 3,
      ),
    )
  ),'Everything is reset');

Doctrine::getTable('ClassificationSynonymies')->saveOrderAndResetBasio('2,3');

$records = Doctrine::getTable('ClassificationSynonymies')->findAllForRecord('taxonomy', 4, array(1));
$t->is_deeply($records,
array(
    'synonym' => array (
      0 => array (
	'id' => 2,
	'record_id' => 4,
	'group_id' => 1,
	'is_basionym' => false,
	'order_by' => 1,
	'name' => 'Falco Peregrinus (Duchesnus Brulus 1912)',
	'item_id' => 4,
      ),
      1 => array (
	'id' => 3,
	'record_id' => 5,
	'group_id' => 1,
	'is_basionym' => false,
	'order_by' => 2,
	'name' => 'Falco Peregrinus recombinus',
	'item_id' => 5,
	),
      )
  ),'Everything is reset also is_basio');

$t->info('mergeSynonyms');
Doctrine::getTable('ClassificationSynonymies')->mergeSynonyms('taxonomy', 4, 2, 'synonym');
$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(1);
$t->is(count($syn), 4, 'Group 1 and 2 merge to group 1');

Doctrine::getTable('ClassificationSynonymies')->mergeSynonyms('taxonomy', 3, 2, 'homonym');
$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(2);
$t->is(count($syn), 3, 'record added to to group 2');

Doctrine::getTable('ClassificationSynonymies')->mergeSynonyms('taxonomy', 2, 6, 'homonym');
$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(2);
$t->is(count($syn), 4, 'group 2 has new record');

Doctrine::getTable('ClassificationSynonymies')->mergeSynonyms('taxonomy', 2, 6, 'isonym');
$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId( Doctrine::getTable('ClassificationSynonymies')->findNextGroupId() - 1);
$t->is(count($syn), 2, 'two records create a new group');

$t->info('deleteAllItemInGroup');
Doctrine::getTable('ClassificationSynonymies')->deleteAllItemInGroup(2);
$syn = Doctrine::getTable('ClassificationSynonymies')->findByGroupId(2);
$t->is(count($syn), 0, 'All record in group was deleted');
