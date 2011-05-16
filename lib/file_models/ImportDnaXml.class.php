<?php 
class ImportDnaXml implements IImportModels
{
  public function getName()
  {
    return "DNA XML";
  }

  public function getLevels()
  {
    $this->levels = array(
      'specimens'   => 'specimen',
      'individuals' => '  individuals',
      'parts'       => '    parts',
      'tissues'      => '      tissues',
      'samples'     => '         dna samples',
    );
    foreach($this->levels as $k=>$l)
      $this->levels[$k] = str_replace(' ', '&nbsp;',$l);
    return $this->levels ;
  }

  public function getColumnsForLevel($level)
  {
    switch($level)
    {
      case 'specimens':   return $this->getColumnsForSpecimens();
      case 'individuals': return $this->getColumnsForIndividuals();
      case 'parts':       return $this->getColumnsForParts();
      case 'tissues':     return $this->getColumnsForTissues();
      case 'samples':     return $this->getColumnsForSamples();
      default: throw new Exception ('Unable to get columns, Unknown Level');
    }
  }

  protected function getColumnsForSpecimens()
  {
    array();
  }

  protected function getColumnsForIndividuals()
  {
    array();
  }

  protected function getColumnsForParts()
  {
    array();
  }

  protected function getColumnsForTissues()
  {
    array();
  }

  protected function getColumnsForSamples()
  {
    array();
  }
}