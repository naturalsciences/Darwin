<?php

/**
 * Parts actions.
 *
 * @package    darwin
 * @subpackage parts
 * @author     DB team <collections@naturalsciences.be>
 */
class partsActions extends DarwinActions
{
  protected $widgetCategory = 'part_widget';
  protected $table = 'specimen_parts';

  public function executeEdit(sfWebRequest $request)
  {
	$this->part = Doctrine::getTable('SpecimenParts')->findExcept($request->getParameter('id'));
	if($this->part)
	{
	  $this->individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($this->part->getSpecimenIndividualRef());
	}
	else
	{
	  $this->part= new SpecimenParts();
	  $this->individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('indid'));
	}

	$this->specimen = Doctrine::getTable('Specimens')->findExcept($this->individual->getSpecimenRef());
	$this->form = new SpecimenPartsForm($this->part);

	if($request->isMethod('post'))
	{
	  $this->form->bind( $request->getParameter('specimen_parts') );
	  if( $this->form->isValid() )
	  {
		$this->form->save();
		$this->redirect('parts/overview?id='.$this->individual->getId());
	  }
	}
    $this->loadWidgets();
  }
  public function executeOverview(sfWebRequest $request)
  {
	$this->individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id'));
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($this->individual->getSpecimenRef());
	$this->forward404Unless($this->individual);
	$this->parts = Doctrine::getTable('SpecimenParts')->findForIndividual($this->individual->getId());
	$parts_ids = array();
	foreach($this->parts as $part)
	  $parts_ids[] = $part->getId();
	$codes_collection = Doctrine::getTable('Codes')->getCodesRelatedArray($this->table, $parts_ids);
	$this->codes = array();
	foreach($codes_collection as $code)
	{
	  if(! isset($this->codes[$code->getRelatedRecord()]))
		$this->codes[$code->getRelatedRecord()] = array();
	  $this->codes[$code->getRelatedRecord()][] = $code;
	}	

  }

  public function executeGetStorage(sfWebRequest $request)
  {
	if($request->getParameter('item')=="container")
	  $items = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages($request->getParameter('type'));
	else
	  $items = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages($request->getParameter('type'));
	return $this->renderPartial('options', array('items'=> $items ));
  }

/*
  public function executeAddNew(sfWebRequest $request)
  {
	$number = intval($request->getParameter('num'));
	$individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id'));
	$form = new PartsGroupedForm(null,array('individual' =>$individual));
	$form->addValue($number);
	return $this->renderPartial('partform',array('form' => $form['newVal'][$number]));
  }

  public function executeDetails(sfWebRequest $request)
  {
	$this->parts = Doctrine::getTable('SpecimenParts')->findExcept($request->getParameter('id'));
	$this->individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($this->parts->getSpecimenIndividualRef());
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($this->individual->getSpecimenRef());

    $this->loadWidgets();
  }
*/
}
