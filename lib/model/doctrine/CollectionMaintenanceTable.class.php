<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CollectionMaintenanceTable extends DarwinTable
{
  public function getDistinctActions()
  {
    return $this->createDistinct('CollectionMaintenance', 'action_observation', 'action')->execute();
  }
  
  public function getCountRelated($table, $ids)
  {
    $q = Doctrine_Query::create()->
 		select('COUNT(m.id) AS cnt, m.record_id')->
		from('CollectionMaintenance m')->
		where('m.referenced_relation = ?', $table)->
		andWhereIn('m.record_id', $ids)->
		groupBy('m.record_id');
    return $q->execute(array(), Doctrine_Core::HYDRATE_NONE);
  }
}