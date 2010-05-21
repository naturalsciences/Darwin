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
  protected $widgetCategory = 'specimen_parts_widget';
  protected $table = 'specimen_parts';

  public function executeEdit(sfWebRequest $request)
  {
	$this->individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id'));
	$this->forward404Unless($this->individual);
	$this->form = new PartsGroupedForm(null,array('individual' =>$this->individual));

	if($request->isMethod('post'))
	{
	  $this->form->bind( $request->getParameter('parts_grouped') );
	  if( $this->form->isValid() )
	  {
		$this->form->save();
		$this->redirect('parts/edit?id='.$this->individual->getId());
	  }
	}
	$this->specimen = Doctrine::getTable('Specimens')->findExcept($this->individual->getSpecimenRef());
  }

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

  public function executeGetStorage(sfWebRequest $request)
  {
	if($request->getParameter('item')=="container")
	  $items = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages($request->getParameter('type'));
	else
	  $items = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages($request->getParameter('type'));
	return $this->renderPartial('options', array('items'=> $items ));
  }
}
