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

  public function getDistinctSepVals($option=true)
  {
    $field = ($option)?'code_prefix_separator':'code_suffix_separator';
    $vals = $this->createDistinct('SpecimensCodes', $field, $field)->execute();
    $response = array(''=>'');
    foreach($vals as $keys=>$value)
    {
      if ($option)
      {
        $response[$value->getCodePrefixSeparator()] = $value->getCodePrefixSeparator();
      }
      else
      {
        $response[$value->getCodeSuffixSeparator()] = $value->getCodeSuffixSeparator();
      }
    }
    return $response;
  }

}