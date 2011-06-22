<?php 
class DisplayImportDna implements IDisplayModels
{
  public function getName()
  {
    return "DNA XML";
  }

  public function getLevels()
  {
    $this->levels = array(
      'specimen' => 'specimen',
      'individual' => '  individuals',
      'specimen part' => '    parts',
      'tissue part' => '      tissues',
      'DNA part' => '         dna samples',
    );
    foreach($this->levels as $k=>$l)
      $this->levels[$k] = str_replace(' ', '&nbsp;',$l);
    return $this->levels ;
  }

  public function getColumnsForLevel($level)
  {
    switch($level)
    {
      case 'specimen':   return $this->getColumnsForSpecimens();
      case 'individual': return $this->getColumnsForIndividuals();
      case 'specimen part': return $this->getColumnsForParts();
      case 'tissue part': return $this->getColumnsForTissues();
      case 'DNA part':     return $this->getColumnsForSamples();
      default: throw new Exception ('Unable to get columns, Unknown Level');
    }
  }

  protected function getColumnsForSpecimens()
  {
     return array(
        'category',
        'expedition_name',
        'station_visible',
        'gtu',
        'taxon',
        'chrono',
        'litho',
        'lithology',
        'mineral',
        'ig',
        'acquisition',
      );
  }

  protected function getColumnsForIndividuals()
  {
    return array(
      'individual_type',
      'individual_sex',
      'individual_state',
      'individual_stage',
      'individual_social_status',
      'individual_rock_form',
      'individual_count_min',
      'individual_count_max',
    );
  }

  protected function getColumnsForParts()
  {
    return array(
      'part',
      'specimen_status',
      'building',
      'floor',
      'room',
      'row',
      'shelf',
      'container_type',
      'container_storage',
      'container',
      'sub_container_type',
      'sub_container_storage',
      'sub_container',
      'part_count_min',
      'part_count_max',
    );
  }

  protected function getColumnsForTissues()
  {
    return $this->getColumnsForParts();
  }

  protected function getColumnsForSamples()
  {
    return $this->getColumnsForParts();
  }
}

