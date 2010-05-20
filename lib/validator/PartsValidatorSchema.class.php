<?php
class PartsValidatorSchema extends sfValidatorSchema
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('category', 'The value is required.');
  }
 
  protected function doClean($value)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    $errorSchemaLocal = new sfValidatorErrorSchema($this);

    if (!$value['category'])
    {
      return array();
    }

    if (count($errorSchemaLocal))
    {
      $errorSchema->addError($errorSchemaLocal, 'category');
    }

    if (count($errorSchema))
    {
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }
    return $value;
  }
}