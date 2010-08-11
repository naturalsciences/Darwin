<?php


class CollectingToolsTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CollectingTools');
    }
    public function fetchTools()
    {
      $response = array();
      $q = Doctrine_Query::create()
          ->select('id, tool')
          ->from('CollectingTools')
          ->orderBy('tool_indexed');
      $result = $q->fetchArray();
      foreach ($result as $value)
      {
        $response[$value['id']] = $value['tool'];
      }
      return $response;
    }
}