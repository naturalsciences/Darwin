<?php

/**
 * CodeToCorrectTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CodeToCorrectTable extends DarwinTable
{
  public static function getInstance()
  {
      return Doctrine_Core::getTable('CodeToCorrect');
  }

  public function move($uid, $from, $to) {
    $from_fix = Doctrine_Core::getTable('CodeToCorrect')->find($from);
    $from = Doctrine_Core::getTable('Codes')->find($from);
    if( ! $from){
      if($from_fix )
        $from_fix->delete();
      return false;
    }
    $to_array = explode(',',$to);
    $infos = $from->toArray();
    unset($infos['id']);
    if($to != '' && !empty($to_array)) {
      foreach($to_array as $part_id) {
        $infos['referenced_relation'] = 'specimen_parts';
        $infos['record_id'] = $part_id;
        $code = new Codes();
        $code->fromArray($infos);
        $code->save();
      }
    }
    $from->delete();
    $from_fix->delete();
    return true;
  }

  public function getForUserCount($uid) {
    $cols = BaseFormFilterDoctrine::getCollectionWithRights($uid, true);
    $q = Doctrine_Query::create()->
      select('COUNT(*) AS cnt')->
      from('CodeToCorrect c')->
      andWhereIn('c.collection_ref', $cols);
    return $q->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
  }

  public function getForUser($uid, $offset=0, $limit= 50) {
    $cols = BaseFormFilterDoctrine::getCollectionWithRights($uid, true);

    $conn_MGR = Doctrine_Manager::connection();
    $conn = $conn_MGR->getDbh();
    $sql = "select 
c.referenced_relation as s_referenced_relation,
c.record_id as s_record_id,
c.id as s_id,
c.code_category as s_code_category,
c.code_prefix as s_code_prefix,
c.code_prefix_separator as s_code_prefix_separator,
c.code as s_code,
c.code_suffix as s_code_suffix,
c.code_suffix_separator as s_code_suffix_separator,
c.collection_ref,

c2.referenced_relation as p_referenced_relation,
c2.record_id as p_record_id,
c2.id as p_id,
c2.code_category as p_code_category,
c2.code_prefix as p_code_prefix,
c2.code_prefix_separator as p_code_prefix_separator,
c2.code as p_code,
c2.code_suffix as p_code_suffix,
c2.code_suffix_separator as p_code_suffix_separator,

p.id as part_id

      from code_to_correct c
      inner join specimen_individuals i on c.record_id = i.specimen_ref
      inner join specimen_parts p on i.id = p.specimen_individual_ref
      LEFT join codes c2 on c2.referenced_relation ='specimen_parts'  and c2.record_id = p.id
      where collection_ref in ( ".implode(',',$cols). ") 
      
      order by s_record_id, s_id , part_id
      
      limit ".$limit." offset ".$offset ;
  
    $statement = $conn->prepare($sql);
    $statement->execute(array());
    $db_res = $statement->fetchAll(PDO::FETCH_ASSOC);
    $result = array();
    $number_of_result = count($db_res);
    
    foreach($db_res as $row){

      if( !isset( $result[ $row['s_record_id'] ] ) )
        $result[ $row['s_record_id'] ] = array('codes'=> array(), 'parts'=> array());

      if( !isset($result[$row['s_record_id']]['codes'][$row['s_id']]) )
        $result[$row['s_record_id']]['codes'][$row['s_id']] = $row;
        
      if( !isset($result[$row['s_record_id']]['parts'][$row['part_id']]) )
        $result[$row['s_record_id']]['parts'][$row['part_id']] = array();

      if( $row['p_id'] != '' && !isset($result[$row['s_record_id']]['parts'][$row['part_id']][$row['p_id']]) )
        $result[$row['s_record_id']]['parts'][$row['part_id']][$row['p_id']] = $row;
    }
    
    if($number_of_result == 100) {
      unset($result[ count($result) -1 ] );
    }
    return $result;
  }
}