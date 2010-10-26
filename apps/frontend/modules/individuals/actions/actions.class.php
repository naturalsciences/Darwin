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
    if(! $request->hasParameter('id'))
    {
      $this->spec_individual = new SpecimenIndividuals(); 
      $duplic = $request->getParameter('duplicate_id','0') ;          
      if ($duplic) // then it's a duplicate individual
      {
        $this->spec_individual = $this->getRecordIfDuplicate($duplic,$this->spec_individual);    
        // set all necessary widgets to visible 
        Doctrine::getTable('SpecimenIndividuals')->getRequiredWidget($this->spec_individual, $this->getUser()->getId(), 'individuals_widget',
                                                                      ($request->hasParameter('all_duplicate')?1:0));
      }    
    }
    else
    {
      $this->spec_individual = Doctrine::getTable('SpecimenIndividuals')->findExcept($request->getParameter('id'));
      $this->forward404Unless($this->spec_individual);
    }

    if($this->spec_individual->isNew())
    {
      $this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id'));

      if(! $this->specimen) // PROTECT
        $this->specimen = new Specimens();

      $this->spec_individual->Specimens = $this->specimen;
    }
    else
    {
      $this->specimen = Doctrine::getTable('Specimens')->findExcept($this->spec_individual->getSpecimenRef());
    }

    $individual = new SpecimenIndividualsForm($this->spec_individual);

    return $individual;
  }

  public function executeEdit(sfWebRequest $request)
  {
    if(!$this->getUser()->isAtLeast(Users::ENCODER)) $this->forwardToSecureAction();  
    if(in_array($request->getParameter('id'),Doctrine::getTable('Specimens')->testNoRightsCollections('individual_ref',
                                                                                                      $request->getParameter('id'), 
                                                                                                      $this->getUser()->getId())))
      $this->redirect("specimen/view?id=".$request->getParameter('id')) ;  

    $this->individual = $this->getSpecimenIndividualsForm($request);  

    if($this->individual->getObject()->isNew())
    {
      $duplic = $request->getParameter('duplicate_id') ;
      if($duplic)
      {
        // reembed duplicated comment
        $Comments = Doctrine::getTable('Comments')->findForTable('specimen_individuals',$duplic) ;
        if(!$Comments->count()) $this->individual->addComments(0);
        foreach ($Comments as $key=>$val)
        {
          $comment = new Comments() ;
          $comment = $this->getRecordIfDuplicate($val->getId(),$comment); 
          $this->individual->addComments($key, $comment) ;          
        }
        //reembed identification
        $Identifications = Doctrine::getTable('Identifications')->getIdentificationsRelated('specimen_individuals',$duplic) ;
        if(!$Identifications->count()) $this->individual->addIdentifications(0,0);
        foreach ($Identifications as $key=>$val)
        {
          $identification = new Identifications() ;
          $identification = $this->getRecordIfDuplicate($val->getId(),$identification); 
          $this->individual->addIdentifications($key, $val->getOrderBy(), $identification);
          $Identifier = Doctrine::getTable('CataloguePeople')->getPeopleRelated('identifications', 'identifier', $val->getId()) ;     
          foreach ($Identifier as $key2=>$val2)
          {
            $ident = $this->individual->getEmbeddedForm('newIdentification')->getEmbeddedForm($key);
            $ident->addIdentifiers($key2,$val2->getPeopleRef(),0);        
            $this->individual->reembedNewIdentification($ident, $key);    
          }         
        }                 
      }
      else
      {
        $this->individual->addIdentifications(0,0);
        $this->individual->addComments(0);      
      }
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
          $this->individual->getErrorSchema()->addError($error, 'Darwin2 :'); 
        }
      }
    }
    $this->loadWidgets();
  }

  public function executeOverview(sfWebRequest $request)
  {  
    $this->specimen = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id',0));
    $this->forward404Unless($this->specimen, sprintf('Specimen does not exist (%s).', $request->getParameter('spec_id',0)));
    $this->individuals = Doctrine::getTable('SpecimenIndividuals')->findBySpecimenRef($this->specimen->getId());
    $this->view = false ;    
    if($request->hasParameter('view') || $this->getUser()->isA(Users::REGISTERED_USER)) $this->view=true ;
    if(in_array($request->getParameter('spec_id'),Doctrine::getTable('Specimens')->testNoRightsCollections('individual_ref',$request->getParameter('spec_id'), $this->getUser()->getId())))  // if this user is not in collection Right, so the overview is displayed in readOnly
      $this->view = true;
  }

  public function executeAddIdentification(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $order_by = intval($request->getParameter('order_by',0));
    $individual_form = $this->getSpecimenIndividualsForm($request);
    $individual_form->addIdentifications($number, $order_by);
    return $this->renderPartial('specimen/spec_identifications',
      array(
        'form' => $individual_form['newIdentification'][$number],
        'row_num' => $number,
        'module' => 'individuals',
        'spec_id' => $individual_form->getObject()->getSpecimenRef(),
        'id' => $request->getParameter('id',0),
        'individual_id' => 0,
    ));
  }

  public function executeAddIdentifier(sfWebRequest $request)
  {
    $individual_form = $this->getSpecimenIndividualsForm($request);
    $number = intval($request->getParameter('num'));
    $people_ref = intval($request->getParameter('people_ref')) ; 
    $identifier_number = intval($request->getParameter('identifier_num'));
    $identifier_order_by = intval($request->getParameter('iorder_by',0));
    $ident = null;

    if($request->hasParameter('identification_id'))
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
  
  public function executeView(sfWebRequest $request)
  {
    $this->forward404Unless($this->specimen = Doctrine::getTable('SpecimenSearch')->findOneByIndividualRef($request->getParameter('id')),'Individual does not exist');  
    $this->loadWidgets(null,$this->specimen->getCollectionRef()); 
  } 
}
