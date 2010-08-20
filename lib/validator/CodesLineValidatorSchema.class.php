<?php
class CodesLineValidatorSchema extends sfValidatorSchema
{
  protected function configure($options = array(), $messages = array())
  {
  }
 
  protected function doClean($value)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    $errorSchemaLocal = new sfValidatorErrorSchema($this);

    if (($value['code_from'] && $value['code_to']===""))
    {
       $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'code_to');
    }

    if($value['code_from']==="" && $value['code_to'])
    {
      $errorSchemaLocal->addError(new sfValidatorError($this, 'required'), 'code_from');
    }

    if (!$value['code_from'] && !$value['code_to'] && !$value['code_part'])
    {
      return array();
    }

    if (count($errorSchemaLocal))
    {
      $errorSchema->addError($errorSchemaLocal, 'code_from');
    }

    if (count($errorSchema))
    {
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }
 
    return $value;
  }
}
