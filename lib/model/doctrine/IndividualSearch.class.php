<?php

/**
 * IndividualSearch
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class IndividualSearch extends BaseIndividualSearch
{
    public function getCountryTags()
    {
      $tags = explode(';',$this->getGtuCountryTagValue(''));
      $nbr = count($tags);
      if(! $nbr) return "-";
      $str = '<ul class="name_tags_view">';
      foreach($tags as $value)
        if (strlen(trim($value)))
          $str .=  '<li>' . trim($value).'</li>';
      $str .= '</ul>';
      
      return $str;
    }

    public function getOtherGtuTags()
    {
      $tags = explode(';',$this->getGtuCountryTagValue(''));
      $nbr = count($tags);
      if(! $nbr) return "-";
      $str = '<ul class="name_tags_view">';
      foreach($tags as $value)
        if (strlen($value))
          $str .=  '<li>' . trim($value).'</li>';
      $str .= '</ul>';
      
      return $str;
    }

  public function getWithTypes()
  {
    if($this->getIndividualType() != 'specimen') return true;
    else return false;
  }

  public function getAggregatedName($sep = ' / ')
  {
    $items = array(
        $this->getCollectionName(),
        $this->getTaxonName(),
        $this->getIndividualTypeGroup(),
        $this->getIndividualSex(),
        $this->getIndividualState(),
        $this->getIndividualStage(),
    );
    $items = array_filter($items);
    return implode($sep, $items);
  }
  
  /* function witch check if there at least 1 common name for a specific catalogue */  
  public static function checkIfCommonName($id,$tab)
  {
    if(!$id) return false;
    if(!isset($tab[$id])) return false;
    if($tab[$id]['name'] == '') return false ;
    return true ;
  }  
}
