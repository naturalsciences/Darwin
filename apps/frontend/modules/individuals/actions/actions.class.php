<?php

/**
 * Individuals actions.
 *
 * @package    darwin
 * @subpackage individuals
 * @author     DB team <collections@naturalsciences.be>
 */
class individualsActions extends DarwinActions
{
  protected $widgetCategory = 'individuals_widget';
  protected $table = 'specimen_individuals';

  protected function getSpecimenIndividualsForm(sfWebRequest $request)
  {
    $this->spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id'));
    if($this->spec_individual)
    {
      $this->specimen = Doctrine::getTable('Specimens')->findExcept($this->spec_individual->getSpecimenRef());
    }
    else
    {
      $this->spec_individual = new SpecimenIndividuals();
      $this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id'));
      $this->forward404Unless($this->specimen);
      $this->spec_individual->Specimens = $this->specimen;
    }

    $individual = new SpecimenIndividualsForm($this->spec_individual);

    return $individual;
  }

  public function executeEdit(sfWebRequest $request)
  {
	$this->individual = $this->getSpecimenIndividualsForm($request);

	if($this->individual->getObject()->isNew())
	{
	  $this->individual->addIdentifications(0,0);
	  $this->individual->addComments(0);
	}
	if($request->isMethod('post'))
	{
	  $this->individual->bind( $request->getParameter('specimen_individuals') );
	  if( $this->individual->isValid())
	  {
	    try
	    {
		$this->individual->save();
		$this->redirect('individuals/overview?spec_id='.$this->individual->getObject()->getSpecimenRef());
	    }
	    catch(Doctrine_Exception $ne)
	    {
	      $e = new DarwinPgErrorParser($ne);
	      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	      $this->individual->getErrorSchema()->addError($error); 
	    }
	  }
	}
	$this->loadWidgets();
  }

  public function executeOverview(sfWebRequest $request)
  {
	$this->forward404Unless($this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0)), sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
	$this->individuals = Doctrine::getTable('SpecimenIndividuals')->findBySpecimenRef($this->specimen->getId());
  }

  public function executeAddIdentification(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $order_by = intval($request->getParameter('order_by',0));
    $individual_form = $this->getSpecimenIndividualsForm($request);
    $individual_form->addIdentifications($number, $order_by);
    return $this->renderPartial('specimen/spec_identifications',array('form' => $individual_form['newIdentification'][$number], 'row_num' => $number, 'module'=>'individuals', 'spec_id'=>$individual_form->getObject()->getSpecimenRef(), 'id'=>$request->getParameter('id',0), 'individual_id'=>0));
  }

  public function executeAddIdentifier(sfWebRequest $request)
  {
    $individual_form = $this->getSpecimenIndividualsForm($request);
    $number = intval($request->getParameter('num'));
    $people_ref = intval($request->getParameter('people_ref')) ; 
    $identifier_number = intval($request->getParameter('identifier_num'));
    $identifier_order_by = intval($request->getParameter('iorder_by',0));
    $ident = null;

    if($request->hasParameter('identification_id') && $request->getParameter('identification_id'))
    {
      $ident = $individual_form->getEmbeddedForm('Identifications')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number,$people_ref, $identifier_order_by);
      $individual_form->reembedIdentifications($ident, $number);
      return $this->renderPartial('specimen/spec_identification_identifiers',array('form' => $individual_form['Identifications'][$number]['newIdentifier'][$identifier_number], 'rownum'=>$identifier_number, 'identnum' => $number));
    }
    else
    {
      $individual_form->addIdentifications($number, 0);
      $ident = $individual_form->getEmbeddedForm('newIdentification')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number,$people_ref, $identifier_order_by);
      $individual_form->reembedNewIdentification($ident, $number);
      return $this->renderPartial('specimen/spec_identification_identifiers',array('form' => $individual_form['newIdentification'][$number]['newIdentifier'][$identifier_number], 'rownum'=>$identifier_number, 'identnum' => $number));
    }
  }

  public function executeAddComments(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $spec = null;

    if($request->hasParameter('id') && $request->getParameter('id'))
      $spec = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id') );
    $form = new SpecimenIndividualsForm($spec);
    $form->addComments($number);
    return $this->renderPartial('specimen/spec_comments',array('form' => $form['newComments'][$number], 'rownum'=>$number));
  }

}
