<?php

/**
 * MultimediaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MultimediaTable extends DarwinTable
{
  /**
   * Returns an instance of this class.
   *
   * @return object MultimediaTable
   */
  public static function getInstance()
  {
      return Doctrine_Core::getTable('Multimedia');
  }
  
  public function findForTable($table_name, $record_id, $onlyVisible=false)
  {
    $q = Doctrine_Query::create()
        ->from('Multimedia m')
        ->orderBy('m.creation_date DESC');
    $q = $this->addCatalogueReferences($q, $table_name, $record_id, 'm', true);
    if($onlyVisible)
      $q->andWhere('visible = true');
    return $q->execute();
  }    

  public function findForPublic(array $parameters)
  {
    $q = Doctrine_Query::create()
         ->from('Multimedia m')
         ->where('m.visible = true')
         ->andWhere("(m.referenced_relation = 'specimens' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'specimen_individuals' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'specimen_parts' AND record_id IN (SELECT part_ref FROM darwin_flat WHERE individual_ref = ?))
                      OR
                     (m.referenced_relation = 'taxonomy' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'chronostratigraphy' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'lithostratigraphy' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'lithology' AND record_id = ?)
                      OR
                     (m.referenced_relation = 'mineralogy' AND record_id = ?)",
                    $parameters
                  )
         ->orderBy("CASE WHEN m.referenced_relation = 'specimens' THEN 1
                         WHEN m.referenced_relation  = 'specimen_individuals' THEN 2
                         WHEN m.referenced_relation  = 'specimen_parts' THEN 3
                         WHEN m.referenced_relation  = 'taxonomy' THEN 4
                         WHEN m.referenced_relation = 'chronostratigraphy' THEN 5
                         WHEN m.referenced_relation = 'lithostratigraphy' THEN 6
                         WHEN m.referenced_relation = 'lithology' THEN 7
                         WHEN m.referenced_relation = 'mineralogy' THEN 8
                     END,
                     m.creation_date DESC");
    return $q->execute();
  }

  /* return an array of URIs in order to delete related files */
  public function getMultimediaRelated($table,$record_id)
  {
    $files = array() ;
    foreach(self::findForTable($table, $record_id) as $media)
      $files[] = sfConfig::get('sf_upload_dir')."/multimedia/".$media->getUri();
    return $files ;
  }
}
