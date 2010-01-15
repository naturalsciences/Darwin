<?php
/**
 */
class ClassificationSynonymiesTable extends DarwinTable
{
  public function DeleteAllItemInGroup($id)
  {
    $q = Doctrine_Query::create()
      ->delete('ClassificationSynonymies s')
      ->where('s.group_id = ?',$id)
      ->execute();
  }

  public function findGroupsIdsForRecord($table_name, $record_id)
  {
    $q = Doctrine_Query::create()
	 ->select('DISTINCT(group_id) as group')
	 ->from('ClassificationSynonymies s INDEXBY group');
    $q = $this->addCatalogueReferences($q,$table_name, $record_id,'s');
    $results = $q->fetchArray();
    if(!count($results))
      return array();
    $groups = array();
    foreach($results as $result)
    {
      $groups[] = $result['group'];
    }
    return $groups;
  }

  public function findAllForRecord($table_name, $record_id, $groups = null)
  {
    if($groups === null)
      $groups = $this->findGroupsIdsForRecord($table_name, $record_id);

    if(empty($groups))
      return array();
    $q = Doctrine_Query::create()
	 ->select('s.group_name, s.id, s.record_id, s.group_id, s.is_basionym, s.order_by, t.name, t.id')
	 ->from('ClassificationSynonymies s, '.Catalogue::getModelForTable($table_name). ' t')
	 ->whereIn('s.group_id', $groups)
	 ->andWhere('t.id=s.record_id')
	 ->andWhere('s.referenced_relation = ?',$table_name) //Not really necessay but....
	 ->orderBy('s.group_name ASC, s.order_by')
	 ->setHydrationMode(Doctrine::HYDRATE_NONE);
    $items = $q->execute();
    $results = array();
    foreach($items as $item)
    {
	//group_name 
	if(! isset($results[$item[0]]) )
	  $results[$item[0]]=array();
	$results[$item[0]][] = array(
	  'id' => $item[1],
	  'record_id' => $item[2],
	  'group_id' => $item[3],
	  'is_basionym' => $item[4],
	  'order_by' => $item[5],
	  'name' => $item[6],
	  'item_id' => $item[7],
	);
    }
    return $results;
  }
  
  public function findGroupnames()
  {
    return array(
      'synonym' => $this->getI18N()->__('Synonyms'),
      'isonym' => $this->getI18N()->__('Isonyms'),
      'homonym' => $this->getI18N()->__('Homonyms')
    );
  }

  public function findNextGroupId()
  {
    $conn = Doctrine_Manager::connection()->getDbh();
    $statement = $conn->prepare("SELECT nextval('classification_synonymies_group_id_seq')");
    $statement->execute();
    $resultset = $statement->fetchAll(PDO::FETCH_NUM);
    return $resultset[0][0];
  }
  
  public function findGroupIdFor($table_name, $record_id, $type)
  {
    $q = Doctrine_Query::create()
	 ->from('ClassificationSynonymies s')
	 ->andWhere('s.group_name = ?',$type);

    $q = $this->addCatalogueReferences($q,$table_name, $record_id,'s');

    $result = $q->fetchOne();
    if($result)
      return $result->getGroupId();
    else
      return 0;
  }

  public function saveOrderAndResetBasio($ids)
  {
    $id_list = explode(',',$ids);
     $q = Doctrine_Query::create()
      ->update('ClassificationSynonymies')
      ->set('is_basionym', '?', false)
      ->set('order_by',"fct_array_find(?, id::text) ",implode(",",$id_list))
      ->whereIn('id', $id_list);

    $updated = $q->execute();
  }
  
  public function mergeSynonyms($table, $record_id_1, $record_id_2, $group_name)
  {
    //Get id For the element to be linked
    $ref_group_id_1 = $this->findGroupIdFor($table, $record_id_1, $group_name);

    //Get id For this element 
    $ref_group_id_2 = $this->findGroupIdFor($table, $record_id_2, $group_name);

    if($ref_group_id_1 == 0 || $ref_group_id_2 == 0)
    {
      $c1 = new ClassificationSynonymies();
      $c1->setReferencedRelation($table);
      $c1->setGroupName($group_name);
      $c1->setRecordId($record_id_2);

      if($ref_group_id_1 == 0 && $ref_group_id_2 == 0) //If there is no group
      {
	$c1->setGroupId( $this->findNextGroupId());

	$c2 = clone $c1;
	$c2->setRecordId($record_id_1);
	$c2->save();
      }
      elseif($ref_group_id_1 == 0)
      {
	$c1->setRecordId($record_id_1);
	$c1->setGroupId($ref_group_id_2);
      }
      else
      {
	$c1->setGroupId( $ref_group_id_1 );
      }
      $c1->save();
    }
    else // There is 2 existing groups... let's merge them
    {
      $this->mergeGroup($ref_group_id_1, $ref_group_id_2);
    }
  }

  protected function mergeGroup($group1, $group2)
  {
    $q = Doctrine_Query::create()
      ->update('ClassificationSynonymies s')
      ->set('s.group_id', '?', $group1)
      ->where('s.group_id = ?', $group2);
    $updated = $q->execute();
    //Check if 2 basionym
    $q = Doctrine_Query::create()
      ->select("COUNT(s.id) num_ids")
      ->from('ClassificationSynonymies s')
      ->andWhere('s.group_id = ?', $group1)
      ->andWhere('s.is_basionym = ?',true);

    $res = $q->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
    // Set No Basionym If more than 1
    if($res > 1)
    {
      Doctrine_Query::create()
	->update('ClassificationSynonymies s')
	->set('s.is_basionym','?',false)
	->where('s.group_id = ?', $group1)
	->execute();
    }
  }
}