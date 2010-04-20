<?php


class SpecimensCodesTable extends DarwinTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('SpecimensCodes');
    }

  /**
  * Get Distincts prefix separators
  * @return array an Array of types in keys
  */
  public function getDistinctPrefixSep()
  {
    return $this->createDistinct('SpecimensCodes', 'code_prefix_separator', 'code_prefix_separator')->execute();
  }

  /**
  * Get Distincts suffix separators
  * @return array an Array of types in keys
  */
  public function getDistinctSuffixSep()
  {
    return $this->createDistinct('SpecimensCodes', 'code_suffix_separator', 'code_suffix_separator')->execute();
  }

}