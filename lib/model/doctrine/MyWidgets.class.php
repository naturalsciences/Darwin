<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MyWidgets extends BaseMyWidgets
{
  public static $help_widget = array(
"cataloguewidget"=> array(
        "cataloguePeople" => "This widget allows you to add people that can have different roles. e.g. an author, a reviewer, an expert in a certain domain, etc.",    
        "comment" => "This widget is used to add commentary.If you wish, You can add more than one commentary notion per record. You cannot add the same notion twice in one record, however",    
        "extLinks" => "This widget allows you to add a URL address to the record (ex : http://www.naturalsciences.be)",    
        "properties" => "This widget allows you to add some more structured commentary, but be sure that your information is entered consistently. You could e.g. add the following: type= measurement, sub type= lenght, qualifier= wing, method= manual, tool= ruler, value units= cm, Accuracy units= mm , value= 3, accuracy=0.2",
        "keywords" => "This widget allows you to split the entire record name into smaller fields. Most of the keyword will follow the ABCDEFG standard",
        "vernacularNames" => "This widget can be used to add the equivalent vernacular name of the record in different languages. You can even add more than one vernacular name in the same language ",
        "relationRename" => "In this widget you can add the current name of the catalogue item",
        "synonym" => "This widget allows you to add synonyms, isonyms or homonyms of the catalogue item",
        "relationRecombination" => "In this widget you can add the original combination(s) that constitutes the present catalogue item",        
        "collectionsCodes" => "This widget allows you to preset the default prefix and/or separator and/or code and/or separator and/or suffix of the main code that will be used when choosing this collection in the specimen screen",
        "insurances" => "In this widget you can specify one or more values of the whole collection belonging to the same IG number as estimated by a particular insurance institution and /or on a particular date.",        
        ),
    "individualswidget"=>array(
        "refIdentifications" => "This widget allows you to add some information regarding the determination of either the type, sex, stage, social status and/or rock formation. You can potentially add the date on which the determination was made, what value was given as well as the determinators' names.",
        "socialStatus" => "This widget is mainly used for entering the social class of an animal living in a social group. e.g. to indicate that an individual is a workerbee",
        "rockForm" => "This widget allows you to add a sterioscopic form that you can find when analysing a mineral composition.",
        "specimenIndividualCount" => "In this widget you can specify the exact number of individuals belonging to the same specimen or you can specify a lower and upperbound estimate of the number of individuals",
        "stage" => "This widget lets you describe the stage of the present individual e.g. a larva",
        "specimenIndividualComments" =>  "his widget is used to add commentary. If you wish, You can add more than one commentary notion per record. You cannot add the same notion twice in one record, however",    
        "refProperties" => "This widget allows you to add some more structured commentary, but be sure that your information is entered consistently. You could e.g. add the following: type= measurement, sub type= lenght, qualifier= wing, method= manual, tool= ruler, value units= cm, Accuracy units= mm , value= 3, accuracy=0.2",
        "type" => "This widget allows you to add the information regarding the status of the individual e.g. holotype. You can, however, also add non conformistic nomenclatura types",
        "sex" => "This widget allows you to enter the sex of a particular individual. You can also add a specific attribute e.g. pregnant with eggs",
        "extLinks" => "This widget allows you to add a URL address to the record (ex : http://www.naturalsciences.be)", 
        ),
    "partwidget"=> array(
        "complete" => "This widget allows you to add information with regards to the physical state of the object. E.g. damaged. You can also indicate whether you have a complete object or a partial object by using the tickbox.",
        "container" => "This widget allows you to add information with regards to the storage conditions in the depositaries of the institute. You can specify a container and/or sub container as well as the condition in which the object is conserved. The supernumerary option allows you to indicate that the objects should potentially be moved to a more spacious container.",
        "partCount" => "In this widget you can specify the exact number of objects belonging to the same individual or you can specify a lower and upperbound estimate of the number of objects",
        "comments" => "This widget is used to add commentary.If you wish, You can add more than one commentary notion per record. You cannot add the same notion twice in one record, however",
        "refInsurances" => "In this widget you can specify one or more values with regards to the object(s) as estimated by a particular insurance institution and /or on a particular date.",
        "extLinks" => "This widget allows you to add a URL address to the record (ex : http://www.naturalsciences.be)", 
        "parent" => "This widget allows you to link the current object to a parent object. E.g. link a DNA sample to a Tissue sample.",
        "specPart" => "This mandatory widget specifies the type of object in more detail.",
        "localisation" => "This widget allows you to add information with regards to the placement in the depositories of the institute. You can specify the building, floor, room, row and/or shelf where the object(s) are stored.",
        "refCodes" => "This widget allows you to link one or more codes to an object. You can either associate a prefix, a separator, a code, a separator and/or a suffix to each type of code (main, temporary,etc.)",
        "maintenance" => "This widget shows you types of actions or observations with regards to the object. This can e.g. show you the date on which a person added alcohol to the container that houses the object or it could be the date on which a person observed that an object seemed to be broken. Information in this widget can, however, only be inserted by using the mass update functionality!",
        "refProperties" => "This widget allows you to add some more structured commentary, but be sure that your information is entered consistently. You could e.g. add the following: type= measurement, sub type= weight, qualifier= shell, method= electronic, tool= scale, value units= g, value=2",
        ),      
    "peoplewidget"=>array(
        "comment" => "This widget is used to add commentary.If you wish, You can add more than one commentary notion per record. You cannot add the same notion twice in one record, however",
        "comm" => "This widget regroups all communication information of a user, such as phone/fax number, email. You can use our tags to set the type of entry (work or home phone for instance)",
        "lang" => "This widget shows all known languages for this person. A language can be set as mothertongue and /or as preferred language.",
        "properties" => "This widget allows you to add some more structured commentary, but be sure that your information is entered consistently. You could e.g. add the following: type= domicile, Date from=01/02/1960, Date to= 05/06/1962 , value= Solomon Islands",
        "address" => "This widget allows you to add one or more addresses, it can be home address, work or more. The locality and the country are required fields", 
        "relation" => "This widget will allow you to add e.g. the institution/department/section the person works/worked for",
        "extLinks" => "This widget allows you to add a URL address to the record (ex : http://www.naturalsciences.be)",
        ),
    "specimensearchwidget"=>array(
        "localisation" => "Via this widget you can search a specimen/individual/part by looking up a particular position in a particular depositary.",
        "stage" => "Via this widget you can look up all records belonging to one or more life stages, e.g. adults or larvae.",
        "whatSearched" => "Via this widget you can predetermin whether you are looking for specimens as a whole, individuals having the same characteristics or objects with the same features.",
        "refLithology" => "Via this widget you can search all records that refer to a particular lithological unit.",
        "specIds" => "Via this widget you can look up a record via the internal database number.",
        "refTaxon" => "Via this widget you can search all records that refer to a particular taxonomic unit.",
        "refGtu" => "Via this widget you can look up records that refer to a particular sampling location. You can look up via code, date and/or related tags.",
        "refChrono" => "Via this widget you can search all records that refer to a particular chronostratigraphic unit.",
        "tools" => "Via this widget you can find all records where the selected tool(s) where used for collecting the specimens.",
        "type" => "Via this widget you can look up those records where the nomenclatura types and non nomenclatura types selected can be found.",
        "methods" => "Via this widget you can find all records where the selected method(s)where used for collecting the specimens.",
        "codes" => "Via this widget you can look up records via either the specimen code(s) or the part code(s).",
        "refCollection" => "Via this widget you can look up the records belonging to one or more specific collections.",
        "sex" => "Via this widget you can look up those records where the individuals belong to the selected sex.",
        "status" => "Via this widget you can look up all individuals that have a particular sexual state. You could e.g. look up all individuals that have the characteristic of carrying eggs.",
        "expedition" => "Via this widget you can look up all records belonging to the same expedition.",
        "refLitho" => "Via this widget you can search all records that refer to a particular lithostratigraphic unit.",
        "container" => "Via this widget you can look up all objects that are held in the container and/or subcontainer selected.",
        "refMineral" => "Via this widget you can search all records that refer to a particular mineralogic unit.",
        "latlong" => "Via this widget you can look up specimens found in a range of latitudes/longitudes by entering them manually. Beware of the formatting used: (-)000.000000. You can also decide to look for a range on the map by ticking the box above. The area shown on the map will be the range in which the search will be performed. Beware that existing sampling locations will be indicated with a flag on the map.",
        "social" => "Via this widget you can look up those records where the individuals belong to the selected social status. E.g. look up all worker ants.",
        "rockform" => "Via this widget you can look up one or more sterioscopic rock forms.",
        "refIgs" => "Via this widget you can either look up by General inventory number (I.G.) or by looking up in a range of I.G. creation dates.",
        ),
    "specimenwidget"=> array(
        "refChrono" => "This widget allows you to add the reference to a chonostratigraphic unit.",
        "extLinks" => "This widget allows you to add a URL address to the record (ex : http://www.naturalsciences.be)", 
        "refCollection" => "This mandatory widget specifies to which collection your specimen should be attached. You should also specify whether the specimen contains physical objects or whether it refers to an observation made.",
        "refIgs" => "This widget allows you to link this specimen to a particular I.G number.",
        "refExpedition" => "This widget allows you to link this specimen to a particular expedition.",
        "refHosts" => "This widget allows you to specify either a Host Taxon (e.g. your specimen is a parasite and you specify its natural host's taxonomic name). You can also add a Host Specimen, indicating that the specimen is also kept in one of the existing collections.",
        "method" => "This widget allows you to add one or more methods used to collect the specimen.",
        "refComment" => "This widget is used to add commentary.If you wish, You can add more than one commentary notion per record. You cannot add the same notion twice in one record, however",
        "refDonators" => "This widget allows you to add the names of the donators or sellers (people or institutions) that gave or sold this specimen to your institution.",
        "refProperties" => "This widget allows you to add some more structured commentary, but be sure that your information is entered consistently. You could e.g. add the following: type= watertemperature, sub type= C°,Date from=01/07/1974 10:05:00, value= 15",
        "refCollectors" => "This widget allows you to add the names of the collectors that where involved in collecting this specimen.",
        "refMineral" => "This widget allows you to add the reference to a mineralogic unit.",
        "refLithology" => "This widget allows you to add the reference to a lithological unit.",
        "refCodes" => "This widget allows you to link one or more codes to a specimen. You can either associate a prefix, a separator, a aphanumeric code, a separator and/or a suffix to each type of code (main, temporary,etc.)",
        "specimensAccompanying" => "This widget allows you to add two types of accompanying elements. In the first case, you could add a biological type of element. E.g Imagine that you have a shell that is covered by a colony of moss animals. You could add the Taxonomic name Membranipora tenuis Desor, 1848 in the widget and you could also specify the amount() of the area covered by the colony. In a second case, you could describe the different elements that constitute the whole mineral that you are encoding. E.g. your rock is made up by two mineralogic elements, one being 95% quartz and the second 5% calcite.",
        "refGtu" => "This widget allows you to add the reference to a Sampling location unit (Gtu).",
        "refLitho" => "This widget allows you to add the reference to a lithostratigraphic unit.",
        "refTaxon" => "This widget allows you to add a reference to a Taxon name.",
        "tool" => "This widget allows you to add one or more tools used to collect the specimen.",
        "refIdentifications" => "This widget allows you to add some information regarding the determination of either the taxonomic, lithostratigraphic, chronostratigraphic, mineralogic unit or rock formation. You can potentially add the date on which the determination was made, what value was given as well as the determinators' names.",
        "acquisitionCategory" => "This widget specifies the manner in which the specimen was obtained. E.g. it was part of a donation on the 01/01/2010.",
        ),
    "userswidget"=>array(
        "address" => "This widget allows you to add one or more addresses, it can be home address, work or more. The locality and the country are required fields", 
        "lang" => "This widget shows all known languages for this user. A language can be set as mothertongue. A language can also be set as preferred that will be used by DaRWIN. If our application cannot translate the preferred language the default language will be used (English)",
        "info" => "This widget is used to specify or change a login and password. if desired, a user can have more than one couple of Login/password.",
        "comm" => "This widget regroups all communication information of a user, such as phone/fax number, email. You can use our tags to set the type of entry (work or home phone for instance)",
        )
  ) ;
  /**
  * Get Widget list file for a given role
  * @param int $role The Role of the user
  * @see Users
  */
  public static function getFileByRight($role)
  {
    $file=sfConfig::get('sf_data_dir').'/widgets/' ;
    switch ($role) {
      case Users::ENCODER : $file .='encoderWidgetListPerScreen.yml' ; break ;
      case Users::MANAGER : $file .='collManagerWidgetListPerScreen.yml' ; break ;
      case Users::REGISTERED_USER : $file .='regUserWidgetListPerScreen.yml' ; break ;
      default : return(0);
    }
    return($file) ;
  }

  public function getWidgetField()
  {
    if ($this->getMandatory()) return('opened') ;
    if (!$this->getIsAvailable()) return ('unused') ;
    if ($this->getOpened()) return('opened') ;
    if ($this->getVisible()) return('visible') ;
    if ($this->getIsAvailable()) return('is_available') ;
  }

  public function getWidgetChoice()
  {
    return $this->getWidgetField() ;
  }

  public function setWidgetChoice($value)
  {
    $values_array = array('opened' => false, 'visible' => false, 'is_available' => false);

    if ($this->getMandatory()) $values_array = array_fill_keys(array_keys($values_array), true);
    if ($value == 'opened') $values_array = array_fill_keys(array_keys($values_array), true);
    if ($value == 'is_available') $values_array['is_available'] = true;
    if ($value == 'visible')
    {
      $values_array['visible'] = true;
      $values_array['is_available'] = true;
    }

    foreach($values_array as $key => $new_value)
    {
      if($this[$key] != $new_value)
        $this[$key] = $new_value;
    }
  }

  public function getComponentFromCategory()
  {
    $cat_array = explode('_',$this->_get('category'));
    return $cat_array[0].'widget';
  }

  public function getTableFromCategory($table_param)
  {
    if($table_param != null)
    {
      return $table_param;
    }
    $cat_array = explode('_', $this->_get('category'));
    if(count($cat_array) >= 2)
      return $cat_array[1];
    return null;
  }
  
  public static function getHelpIcon($category,$groupname)
  {
    $help_widget = MyWidgets::$help_widget[$category][$groupname] ;
    try{
        $i18n_object = sfContext::getInstance()->getI18n();
    }
    catch( Exception $e )
    {
        return $help_widget;
    }
    return $i18n_object->__($help_widget);    
  }   
}
