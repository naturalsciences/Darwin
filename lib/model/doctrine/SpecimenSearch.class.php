<?php

/**
 * SpecimenSearch
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class SpecimenSearch extends BaseSpecimenSearch
{

    public function getCountryTags()
    {
      $tags = explode(';',$this->getGtuCountryTagValue(''));
      $nbr = count($tags);
      if(! $nbr) return "-";
      $str = '<ul class="name_tags">';
      foreach($tags as $value)
        if (strlen($value))
          $str .=  '<li>' . trim($value).'</li>';
      $str .= '</ul>';
      
      return $str;
    }

    public function getOtherGtuTags()
    {
      $tags = explode(';',$this->getGtuCountryTagValue(''));
      $nbr = count($tags);
      if(! $nbr) return "-";
      $str = '<ul class="name_tags">';
      foreach($tags as $value)
        if (strlen($value))
          $str .=  '<li>' . trim($value).'</li>';
      $str .= '</ul>';
      
      return $str;
    }

  /* Function returning a flag telling if for the current specimen there are types or not */
  public function getWithTypes()
  {
    if($this->_get('with_types') == '{specimen}' || $this->_get('with_types') == '{}') return false;
    return true;
  }

  public function getAggregatedName($sep = ' / ')
  {
    $items = array(
      $this->getTaxonName(),
      $this->getChronoName(),
      $this->getLithoName(),
      $this->getLithologyName(),
      $this->getMineralName()
    );
    $items = array_filter($items);
    return implode($sep, $items);
  }
  
  /* function witch check if there at least 1 common name for a specific catalogue */  
  public function checkIfCommonName($id,$tab)
  {
    if(!$id) return false;
    if(!isset($tab[$id])) return false;
    if($tab[$id]['name'] == '') return false ;
    return true ;
  }

  /* function witch check if there at least 1 common name for a specific specimen */
  public function checkCommonNameForSpecimen($common_name,$spec) 
  {
    $bool = false ;
    if($this->checkIfCommonName($spec->getTaxonRef(), $common_name['taxonomy'])) $bool = true ;
    if($this->checkIfCommonName($spec->getChronoRef(), $common_name['chronostratigraphy'])) $bool = true ;
    if($this->checkIfCommonName($spec->getLithoRef(), $common_name['lithostratigraphy'])) $bool = true ;
    if($this->checkIfCommonName($spec->getLithologyRef(), $common_name['lithology'])) $bool = true ;
    if($this->checkIfCommonName($spec->getMineralRef(), $common_name['mineralogy'])) $bool = true ;   
    return $bool ;             
  }   
}
