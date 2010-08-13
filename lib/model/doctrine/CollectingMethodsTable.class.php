<?php


class CollectingMethodsTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('CollectingMethods');
    }

    public function fetchMethods()
    {
      $response = array();
      $q = Doctrine_Query::create()
          ->select('id, method')
          ->from('CollectingMethods')
          ->orderBy('method_indexed');
      $result = $q->fetchArray();
      foreach ($result as $value)
      {
        $response[$value['id']] = $value['method'];
      }
      return $response;
    }

    public function addMethod($method)
    {
      $q = Doctrine_Query::create()
           ->select('id')
           ->from('CollectingMethods')
           ->where('method_indexed = fullToIndex(?)', $method);
      if ($method!='' && !$q->count())
      {
        $newMethod = new CollectingMethods;
        $newMethod->setMethod($method);
        try
        {
          $newMethod->save();
        }
        catch (Doctrine_Exception $ne)
        {
          $e = new DarwinPgErrorParser($ne);
          return $e->getMessage();
        }
      }
      return 'ok';
    }
}