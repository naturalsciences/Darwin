<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CodesTable extends DarwinTable
{
  public function getDistinctCodeCategories()
  {
    $q = Doctrine_Query::create()->
         select('DISTINCT code_category')->
         from('Codes');
    $res = $q->execute();
    $results = array('' =>'');
    foreach($res as $row)
    {
      $results[$row->getId()] = $row->__toString();
    }
    return $results;
  }
}