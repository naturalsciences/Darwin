<?php 
class ImportDnaXml implements IImportModels
{
  private $simpleArrayField = array(
    'category' => 'category',
    'level' => 'level', 
    'sex' => 'individual_sex', 
    'type' => 'individual_type',
    'stage' => 'individual_stage',
    'specimen_part' => 'part',
    'expedition_name' => 'expedition_name',
    'expedition_from_date' => 'expedition_from_date',
    'expedition_from_date_mask' => 'expedition_from_date_mask',
    'expedition_to_date' => 'expedition_to_date',
    'expedition_to_date_mask' => 'expedition_to_date_mask',
    'gtu_code' => 'gtu_code',
    'gtu_from_date' => 'gtu_from_date',
    'gtu_from_date_mask' => 'gtu_from_date_mask',
    'latitude' => 'gtu_latitude',
    'longitude' => 'gtu_longitude',     
    'lat_long_accuracy' => 'gtu_lat_long_accuracy',
    'elevation_value' => 'gtu_elevation',
    'elevation_accuracy' => 'gtu_elevation_accuracy',
    'shelf' => 'shelf',
    'container_type' => 'container_type',
    'container_storage' => 'container_storage',
    'container' => 'container',
    'sub_container_type' => 'sub_container_type',
    'sub_container_storage' => 'sub_container_storage',
    'sub_container' => 'sub_container', 
    'group_name' => 'group_name',
    'sub_group_name' => 'sub_group_name',
    'tag_value' => 'tag_value',
  ) ; 
  
  public function importFile($file,$id)
  {  
    $xml = new DOMDocument();
    $xml->load($file) ;     
    $msgs = $xml->getElementsByTagName("collection_object");     
    foreach ($msgs as $msg)
    {      
      $object = new Staging() ;
      $object['import_ref'] = $id ;        
      $this->parseAndAdd($msg,$object) ;
      $object->save() ;
    }       
  }
  
  protected function parseAndAdd($nodes,$object)
  {         
    foreach ($nodes->childNodes as $childNode) 
    {  
      // text node doesn't interest us
      if($childNode->nodeName == "#text") continue;
      // if a node have not more than one child, it's a node without childen
      if($childNode->childNodes->length == 1) $this->getSimpleField($childNode,$object);
      else
      {
        if($childNode->nodeName == 'collection_objects')
        {
          $msgs = $childNode->childNodes->item(1);
          foreach($msgs as $childs)
          {
            $newObject = new Staging() ;
            $newObject['import_ref'] = $object->getImportRef() ;
            $object->Staging[] = $newObject ;                       
            $this->parseAndAdd($childs,$newObject) ;                    
            //$newObject->save();
            
            // ici créer un array qui va traiter toutes les balises que je ne peut traiter qu'une fois l'object sauvé
          }
        }
        elseif($childNode->nodeName == 'gtu') $this->processWithGtuNode($childNode,$object); 
        elseif($childNode->nodeName == 'expedition') $this->processWithExpeditionNode($childNode,$object);
        elseif($childNode->nodeName == 'taxon') $this->processWithTaxonNode($childNode,$object);
        elseif($childNode->nodeName == 'codes') $this->processWithCodesNode($childNode,$object);
        elseif($childNode->nodeName == 'identifications') $this->processWithIdentificationsNode($childNode,$object);
        elseif($childNode->nodeName == 'donators') $this->processWithDonatorsNode($childNode,$object);
        elseif($childNode->nodeName == 'institution') $this->processWithInstitutionNode($childNode,$object);
        elseif($childNode->nodeName == 'comments') $this->processWithCommentsNode($childNode,$object);
        elseif($childNode->nodeName == 'properties') $this->processWithPropertiesNode($childNode,$object);    
        elseif($childNode->nodeName == 'collectors') $this->processWithCollectorsNode($childNode,$object);                      
        else die('Unknown node '.$childNode->nodeName.' in collection_objects') ;
      }          
    }    
  }

  // function used to get an XML tag and return the associated field in the Staging table
  protected function getSimpleField($xml_node, $object)
  {
    if(array_key_exists($xml_node->nodeName,$this->simpleArrayField)) $object[$this->simpleArrayField[$xml_node->nodeName]] = $xml_node->nodeValue ;  
    return $object ;
  }
  
  public function processWithGtuNode($xml_node,$object)
  {
    foreach ($xml_node->childNodes as $childNode) 
    {        
      // text node doesn't interest us
      if($childNode->nodeName == "#text") continue;
      // if a node have not more than one child, it's a node without childen
      if($childNode->childNodes->length == 1) $this->getSimpleField($childNode,$object);
      else
      {
        if($childNode->nodeName == 'tag_groups')
        {      
          foreach($childNode->childNodes as $childs)
          {          
          /*  $stagingTag = new StagingTagGroups() ;
            $stagingTag['staging_ref'] = $object ;
            $this->parseAndAdd($childs,$stagingTag) ;
            $stagingTag->save() ;    */        
          }
        }
        elseif($childNode->nodeName == 'comments')
        {
          $msgs = $childNode->childNodes->item(1);
          foreach($msgs as $childs)
          {
            $comment = new Comments() ;
          }
        }        
        elseif($childNode->nodeName == 'lat_long' || $childNode->nodeName == 'elevation') $this->parseAndAdd($childNode,$object) ;
        else die('Unknown node '.$childNode->nodeName.' in gtu') ;
      }
    }  
  } 
   
  public function processWithCollectorsNode($xml_node,$object)
  {
  
  }  
  
  public function processWithExpeditionNode($xml_node,$object)
  {
  
  }   
  public function processWithTaxonNode($xml_node,$object)
  {
  
  }   
  public function processWithCodesNode($xml_node,$object)
  {
  
  }   
  public function processWithIdentificationsNode($xml_node,$object)
  {
  
  }   
  public function processWithDonatorsNode($xml_node,$object)
  {
  
  }   
  public function processWithInstitutionNode($xml_node,$object)
  {
  
  }   
  public function processWithCommentsNode($xml_node,$object)
  {
  
  }   
  public function processWithPropertiesNode($xml_node,$object)
  {
  
  }    
}
    
