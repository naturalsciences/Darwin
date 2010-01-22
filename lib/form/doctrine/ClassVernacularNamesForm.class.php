<?php

/**
 * ClassVernacularNames form.
 *
 * @package    form
 * @subpackage ClassVernacularNames
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ClassVernacularNamesForm extends BaseClassVernacularNamesForm
{
public function configure()
  {
    unset(
      $this['community_indexed'],
      $this['id']
    );

    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();    
    $this->widgetSchema['community'] = new widgetFormSelectComplete(array(
        'model' => 'ClassVernacularNames',
        'table_method' => 'getDistinctCommunities',
        'method' => 'getCommunities',
        'key_method' => 'getCommunities',
        'add_empty' => true,
	'change_label' => 'Pick a community in the list',
	'add_label' => 'Add another community',
    ));
    
    $this->embedRelation('VernacularNames');
    
    $subForm = new sfForm();
    $this->embedForm('newVal',$subForm);
  }
  
  public function addValue($num)
  {
      $val = new VernacularNames();
      $val->ClassVernacularNames = $this->getObject();
      $form = new VernacularNamesForm($val);
  
      //Embedding the new picture in the container
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

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $forms)
    {
	$value = $this->getValue('newVal');
	foreach($this->embeddedForms['newVal']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['name']))
	  {
	    unset($this->embeddedForms['newVal'][$name]);
	  }
	}

	$value = $this->getValue('VernacularNames');
	foreach($this->embeddedForms['VernacularNames']->getEmbeddedForms() as $name => $form)
	{
	  
	  if (!isset($value[$name]['name']))
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['VernacularNames'][$name]);
	  }
	}
    }
    return parent::saveEmbeddedForms($con, $forms);
  }
}