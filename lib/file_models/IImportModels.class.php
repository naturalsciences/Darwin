<?php
interface IImportModels
{
  public function getName();
  public function getLevels();
  public function getColumnsForLevel($level);
  public function importFile($file,$id);
}
