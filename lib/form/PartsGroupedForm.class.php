<?php

/**
 * Collections form.
 *
 * @package    form
 * @subpackage Collections
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PartsGroupedForm extends sfForm
{
  public function configure()
  {
	$this->individual = isset($this->options['individual']) ? $this->options['individual'] : new SpecimenIndividuals() ;
	$subForm = new sfForm();
	foreach($this->individual['SpecimenParts'] as $index => $part)
	{
	  //$val = new SpecimenParts($part);
	  $form = new SpecimenPartsForm($part);
	  $subForm->embedForm($index, $form);
	}
    $this->embedForm('SpecimenParts',$subForm);

	// New vals
    $newsubForm = new sfForm();
    $this->embedForm('newVal',$newsubForm);

    $this->widgetSchema->setNameFormat('parts_grouped[%s]');
  }
  
  public function addValue($num)
  {
	$val = new SpecimenParts();
	$val->Individual = $this->individual; //Not sure....
	$form = new SpecimenPartsForm($val);

	$this->embeddedForms['newVal']->embedForm($num, $form);
	//Re-embedding the container
	$this->embedForm('newVal', $this->embeddedForms['newVal']);
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
	if(isset($taintedValues['newVal']))
	{
	  foreach($taintedValues['newVal'] as $key=>$newVal)
	  {
		if (!isset($this['newVal'][$key]))
		{
		  $this->addValue($key);
		}
	  }
    }
    parent::bind($taintedValues, $taintedFiles);
  }


  public function saveEmbeddedForms()
  {
	$value = $this->getValue('newVal');
	foreach($this->embeddedForms['newVal']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['category']))
	  {
	    unset($this->embeddedForms['newVal'][$name]);
	  }
	  else
	  {
		$form->updateObject($value[$name]);
		$form->getObject()->save();
	  }

	}

	$value = $this->getValue('SpecimenParts');
	foreach($this->embeddedForms['SpecimenParts']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['category']))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['SpecimenParts'][$name]);
	  }
	  else
	  {
		$form->updateObject($value[$name]);
		$form->getObject()->save();
	  }
	}
  }

  public function save($con = null)
  {
	if (null === $con)
	{
	  $con = Doctrine_Manager::getInstance()->getConnectionForComponent('SpecimenParts');
	}

	try
	{
	  $con->beginTransaction();	
	  $this->saveEmbeddedForms($con);
	  $con->commit();
    }
	catch (Exception $e)
	{
	  $con->rollBack();
	  throw $e;
	}

  }
}
