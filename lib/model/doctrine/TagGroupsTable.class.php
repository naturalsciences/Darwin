<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TagGroupsTable extends DarwinTable
{
  public function getDistinctSubGroups($group)
  {
    $q = $this->createDistinct('TagGroups  INDEXBY sgn', 'sub_group_name', 'sgn','');
    $q->andWhere('group_name = ?', $group);
    $results = $q->fetchArray();
    if(count($results))
      $results = array_combine(array_keys($results),array_keys($results));

    return $results;
//    return array_merge(array(''=>''), $results);
  }
}