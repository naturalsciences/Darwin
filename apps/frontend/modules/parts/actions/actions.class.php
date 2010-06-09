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
	  $this->forward404Unless($this->individual);
	  $this->part->Individual = $this->individual;
	}

	$this->specimen = Doctrine::getTable('Specimens')->findExcept($this->individual->getSpecimenRef());
	$this->form = new SpecimenPartsForm($this->part, array( 'collection'=>$this->specimen->getCollectionRef() ));

	if($request->isMethod('post'))
	{
	  $this->form->bind( $request->getParameter('specimen_parts') );
	  if( $this->form->isValid() )
	  {
	    try
	    {
		$this->form->save();
		$this->redirect('parts/overview?id='.$this->individual->getId());
	    }
	    catch(Doctrine_Exception $ne)
	    {
		$e = new DarwinPgErrorParser($ne);
		$error = new sfValidatorError(new savedValidator(),$e->getMessage());
		$this->form->getErrorSchema()->addError($error);
	    }
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
	  if(! isset($this->codes[$code->getRecordId()]))
		$this->codes[$code->getRecordId()] = array();
	  $this->codes[$code->getRecordId()][] = $code;
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

  protected function getSpecimenPartForm(sfWebRequest $request, $fwd404=false, $parameter='id')
  {
    $part = null;

    $collectionId = $request->getParameter('collection_id', null);

    if ($fwd404)
      $this->forward404Unless($part = Doctrine::getTable('SpecimenParts')->findExcept($request->getParameter($parameter,0)), $this->getI18N('Specimen not found'));
    elseif($request->hasParameter($parameter) && $request->getParameter($parameter))
      $part = Doctrine::getTable('SpecimenParts')->findExcept($request->getParameter($parameter));
    
    $form = new SpecimenPartsForm($part, array('collection'=>$collectionId));
    return $form;
  }

  public function executeAddCode(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $collectionId = $request->getParameter('collection_id', null);
    $form = $this->getSpecimenPartForm($request);
    $form->addCodes($number, $collectionId);
    return $this->renderPartial('specimen/spec_codes',array('form' => $form['newCode'][$number]));
  }

  public function executeAddInsurance(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $collectionId = $request->getParameter('collection_id', null);
    $form = $this->getSpecimenPartForm($request);
    $form->addInsurances($number);
    return $this->renderPartial('parts/insurances',array('form' => $form['newInsurance'][$number]));
  }
}
