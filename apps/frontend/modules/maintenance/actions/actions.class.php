<?php

/**
 * maintenance actions.
 *
 * @package    darwin
 * @subpackage maintenance
 * @author     DB team <collections@naturalsciences.be>
 */
class maintenanceActions extends DarwinActions
{

  public function executeIndex(sfWebRequest $request)
  {
	$this->search_form = new SpecimenPartsFormFilter();
	$this->form = new CollectionMaintenanceForm();
	if($request->isMethod('post'))
	{
	  $this->form->bind($request->getParameter('collection_maintenance'));
	  if($this->form->isValid())
	  {
		$parts_ids_str = $this->form->getValue('parts_ids');
		$parts_ids_arr = explode(',',$parts_ids_str);
		$this->form->updateObject();
		$part_maintenance = $this->form->getObject();

		try
		{
		  foreach($parts_ids_arr as $id)
		  {
			$maintenance = $part_maintenance->copy();
			$maintenance->setRecordId($id);
			$maintenance->setReferencedRelation("specimen_parts");
			$maintenance->save();
		  }
		  $this->parts_numbers = count($parts_ids_arr);
		  return $this->renderPartial('finished',array('parts_numbers'=> $this->parts_numbers ));
		}
		catch(Doctrine_Exception $ne)
		{
		  $e = new DarwinPgErrorParser($ne);
		  $error = new sfValidatorError(new savedValidator(),$e->getMessage());
		  $this->form->getErrorSchema()->addError($error); 
		}
	  }
	  return $this->renderPartial('maintenance',array('form'=> $this->form ));
	}
  }

  public function executeGetOptions(sfWebRequest $request)
  {
	$field = $request->getParameter('field');
	switch($field)
	{
	  case 'floor':
		$this->options = Doctrine::getTable('SpecimenParts')->getDistinctFloors( $request->getParameter('building') );
		$this->opt_method = 'getFloors';
		break;
	  case 'room':
		$this->options = Doctrine::getTable('SpecimenParts')->getDistinctRooms( $request->getParameter('building'),  $request->getParameter('floor'));
		$this->opt_method = 'getRooms';
		break;
	  case 'row':
		$this->options = Doctrine::getTable('SpecimenParts')->getDistinctRows( $request->getParameter('building'),  $request->getParameter('floor'), $request->getParameter('room'));
		$this->opt_method = 'getRows';
		break;
	  case 'shelf':
		$this->options = Doctrine::getTable('SpecimenParts')->getDistinctShelfs( $request->getParameter('building'),  $request->getParameter('floor'), $request->getParameter('room'), $request->getParameter('row'));
		$this->opt_method = 'getShelfs';
		break;
	  default:
		$this->forward404('Invalid Option');
	}
  }
  
  public function executeGetParts(sfWebRequest $request)
  {
	$this->form = new SpecimenPartsFormFilter();
	$this->form->bind($request->getParameter('specimen_parts_filters'));
	$this->parts = array();
	if($this->form->isValid())
	{
	  $this->parts = $this->form->getQuery()->execute();
	}
	else
	  return $this->renderText($this->form);
	$partsIds = array();
	foreach($this->parts as $part)
	{
	  $partsIds[] = $part->getId();
	}

	$this->maint = Doctrine::getTable('CollectionMaintenance')->getCountRelated("specimen_parts", $partsIds);
	$this->maintenances =array();
	foreach($this->maint as $key=>$val)
	  $this->maintenances[$val[0]] = $val[1];
  }
}
