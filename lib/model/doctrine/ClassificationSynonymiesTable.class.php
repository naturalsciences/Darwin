<?php
/**
 */
class ClassificationSynonymiesTable extends DarwinTable
{

  /**
  * Find all Synonyms for a table name and a recordId
  * @param string $table_name the table to look for
  * @param int record_id the record to be linked.
  * @return Doctrine_Collection Collection of Doctrine records
  */
  public function findForTable($table_name, $record_id)
  {
     $q = Doctrine_Query::create()
	 ->from('ClassificationSynonymies s')
	 ->andWhere('s.referenced_relation = ?',$table_name)
         ->andWhere('s.record_id = ?',$record_id)
	 ->andWhere('s.record_id != 0');
    return $q->execute();
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
    $q = Doctrine_Query::create()
	 ->select('MAX(s.group_id) as gid')
	 ->from('ClassificationSynonymies s');
    $result = $q->fetchOne();
    return $result->getGid()+1;
  }
  
  public  function findSynonymsFor($table_name, $record_id, $type)
  {
    $q = Doctrine_Query::create()
	 ->from('ClassificationSynonymies s')
	 ->andWhere('s.referenced_relation = ?',$table_name)
         ->andWhere('s.record_id = ?',$record_id)
	 ->andWhere('s.record_id != ?',0)
	 ->andWhere('s.group_name = ?',$type);
    $result = $q->fetchOne();
    if($result)
      return $result->getGroupId();
    else
      return 0;
  }
}