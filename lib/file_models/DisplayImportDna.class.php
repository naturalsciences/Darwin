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
        'category'=>'Category',
        'expedition_name' => 'Expedition',
//         'station_visible' => 'Station Visible',
        'gtu' => 'Sampling Location',
        'taxon' => 'Taxonomy',
        'chrono' => 'Chronostratigraphy',
        'litho' => 'Lithostratigraphy',
        'lithology' => 'Lithology',
        'mineral' => 'Mineralogy',
        'ig' => 'Inv. Gen.',
        'acquisition' => 'Acquisition',
      );
  }

  protected function getColumnsForIndividuals()
  {
    return array(
      'individual_type' => 'Type',
      'individual_sex' => 'Sex',
      'individual_state' => 'State',
      'individual_stage' => 'Stage',
      'individual_social_status' => 'Social Status',
      'individual_rock_form' => 'Rock Form',
      'individual_count' => 'Number',
    );
  }

  protected function getColumnsForParts()
  {
    return array(
      'part' => 'Part',
      'specimen_status' => 'Status',
      'institution' => 'Institution',
      'building' => 'Building',
      'floor' => 'Floor',
      'room' => 'Room',
      'row' => 'Row',
      'shelf' => 'Shelf',
      'container_type' => 'Container Type',
      'container_storage' => 'Container storage',
      'container' => 'Container',
      'sub_container_type' => 'Sub container type',
      'sub_container_storage' => 'Sub container storage',
      'sub_container' => 'Sub container',
      'part_count' => 'Number',
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

