<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MineralogyTable extends DarwinTable
{
  /**
  * Get Distincts Cristalographic system
  * @return array an Array of currencies in keys
  */
  public function getDistinctSystems()
  {
    return $this->createDistinct('Mineralogy', 'cristal_system', 'c_system')->execute();
  }

  public function fetchByCodeLimited($code, $limit)
  {
    $q = Doctrine_Query::create()
         ->from('Mineralogy')
         ->where("upper(code) like concat (upper(?), '%') ", $code)
         ->limit($limit)
         ->orderBy("code ASC");
    return $q->execute();
  }

}