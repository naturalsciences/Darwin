<?php
class InsurancesValidatorSchema extends sfValidatorSchema
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('insurance_value', 'At least a value is required.');
  }
 
  protected function doClean($value)
  {
    $errorSchema = new sfValidatorErrorSchema($this);
    $errorSchemaLocal = new sfValidatorErrorSchema($this);

    if (!$value['referenced_relation'])
    {
      return array();
    }
    elseif ($value['insurance_currency'] && !$value['insurance_value'] )
    {
      $errorSchemaLocal->addError(new sfValidatorError($this, 'insurance_value'));
    }
    elseif (!$value['insurance_value'] && !$value['insurance_currency'])
    {
      return array();
    }
    elseif (!$value['insurance_year'])
    {
      $errorSchemaLocal->addError(new sfValidatorError($this, 'required'),'insurance_year');
    }

    if (count($errorSchemaLocal))
    {
      $errorSchema->addError($errorSchemaLocal, 'insurance_value');
    }

    if (count($errorSchema))
    {
      throw new sfValidatorErrorSchema($this, $errorSchema);
    }
 
    return $value;
  }
}