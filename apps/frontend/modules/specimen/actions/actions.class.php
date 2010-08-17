<?php

/**
 * Specimens actions.
 *
 * @package    darwin
 * @subpackage specimen
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class specimenActions extends DarwinActions
{
  protected $widgetCategory = 'specimen_widget';

  /*
  */
  protected function getSpecimenForm(sfWebRequest $request, $fwd404=false, $parameter='id')
  {
    $spec = null;

    if ($fwd404)
      $this->forward404Unless($spec = Doctrine::getTable('Specimens')->findExcept($request->getParameter($parameter,0)), $this->getI18N('Specimen not found'));
    elseif($request->hasParameter($parameter) && $request->getParameter($parameter))
      $spec = Doctrine::getTable('Specimens')->findExcept($request->getParameter($parameter) );
    
    $form = new SpecimensForm($spec);
    return $form;
  }

  public function executeAddCode(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $form = $this->getSpecimenForm($request);
    $collectionId = $request->getParameter('collection_id', null);
    $form->addCodes($number, $collectionId);
    return $this->renderPartial('spec_codes',array('form' => $form['newCode'][$number], 'rownum'=>$number));
  }

  public function executeAddCollector(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $people_ref = intval($request->getParameter('people_ref')) ;
    $form = $this->getSpecimenForm($request);
    $form->addCollectors($number,$people_ref,$request->getParameter('iorder_by',0));
    return $this->renderPartial('spec_people_associations',array('form' => $form['newCollectors'][$number], 'row_num'=>$number));
  }

  public function executeAddComments(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $form = $this->getSpecimenForm($request);
    $form->addComments($number);
    return $this->renderPartial('spec_comments',array('form' => $form['newComments'][$number], 'rownum'=>$number));
  }

  public function executeAddSpecimensAccompanying(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $form = $this->getSpecimenForm($request);
    $form->addSpecimensAccompanying($number);
    return $this->renderPartial('specimens_accompanying',array('form' => $form['newSpecimensAccompanying'][$number], 'rownum'=>$number));
  }

  public function executeAddIdentification(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $order_by = intval($request->getParameter('order_by',0));
    $spec_form = $this->getSpecimenForm($request, false, 'spec_id');
    $spec_form->addIdentifications($number, $order_by);
    return $this->renderPartial('spec_identifications',array('form' => $spec_form['newIdentification'][$number], 'row_num' => $number, 'module'=>'specimen', 'spec_id'=>$request->getParameter('spec_id',0), 'individual_id'=>0));
  }

  public function executeAddIdentifier(sfWebRequest $request)
  {
    $spec_form = $this->getSpecimenForm($request, false, 'spec_id');
    $number = intval($request->getParameter('num'));
    $people_ref = intval($request->getParameter('people_ref')) ; 
    $identifier_number = intval($request->getParameter('identifier_num'));
    $identifier_order_by = intval($request->getParameter('iorder_by',0));
    $ident = null;

    if($request->hasParameter('identification_id') && $request->getParameter('identification_id'))
    {
      $ident = $spec_form->getEmbeddedForm('Identifications')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number,$people_ref, $identifier_order_by);
      $spec_form->reembedIdentifications($ident, $number);
      return $this->renderPartial('spec_identification_identifiers',array('form' => $spec_form['Identifications'][$number]['newIdentifier'][$identifier_number], 'rownum'=>$identifier_number, 'identnum' => $number));
    }
    else
    {
      $spec_form->addIdentifications($number, 0);
      $ident = $spec_form->getEmbeddedForm('newIdentification')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number,$people_ref, $identifier_order_by);
      $spec_form->reembedNewIdentification($ident, $number);
      return $this->renderPartial('spec_identification_identifiers',array('form' => $spec_form['newIdentification'][$number]['newIdentifier'][$identifier_number], 'rownum'=>$identifier_number, 'identnum' => $number));
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->loadWidgets();
    $this->form = new SpecimensForm();
    $this->form->addIdentifications(0,0); 
    $this->form->addComments(0); 
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'),'You must submit your data with Post Method');
    $this->form = new SpecimensForm();
    $this->processForm($request, $this->form);

    $this->loadWidgets();

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->loadWidgets();
    $this->form = $this->getSpecimenForm($request, true);
    $this->setTemplate('new');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->loadWidgets();

    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->form = $this->getSpecimenForm($request,true);

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      try
      {
        $specimen = $form->save();
        $this->redirect('specimen/edit?id='.$specimen->getId());
      }
      catch(Doctrine_Exception $ne)
      {
        $e = new DarwinPgErrorParser($ne);
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }

  /**
    * Action executed when calling the expeditions from an other screen
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeChoose(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    // Initialization of the Search expedition form
    $this->form = new SpecimensFormFilter(array('caller_id'=>$this->caller_id));
    // Remove surrounding layout
    $this->setLayout(false);
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->setLevelAndCaller($request);
    // Initialization of the Search expedition form
    $this->form = new SpecimensFormFilter(array('caller_id'=> $this->caller_id));
  }

  /**
    * Action executed when searching an expedition - trigger by the click on the search button
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeSearch(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('post'));
    $this->setCommonValues('specimen', 't.name', $request);
    $item = $request->getParameter('searchSpecimen',array(''));
    // Instantiate a new expedition form
    $this->form = new SpecimensFormFilter(array('caller_id'=>$item['caller_id']));
    // Triggers the search result function
    $this->searchResults($this->form, $request);    
  }

  /**
    * Method executed when searching an expedition - trigger by the click on the search button
    * @param SearchExpeditionForm $form    The search expedition form instantiated that will be binded with the data contained in request
    * @param sfWebRequest         $request Request coming from browser
    * @var   int                  $pagerSlidingSize: Get the config value to define the range size of pager to be displayed in numbers (i.e.: with a value of 5, it will give this: << < 1 2 3 4 5 > >>)
    */
  protected function searchResults(SpecimensFormFilter $form, sfWebRequest $request)
  {
    if($request->getParameter('searchSpecimen','') !== '')
    {
      // Bind form with data contained in searchExpedition array
      $form->bind($request->getParameter('searchSpecimen'));
      // Test that the form binded is still valid (no errors)
      if ($form->isValid())
      {
        // Define all properties that will be either used by the data query or by the pager
        // They take their values from the request. If not present, a default value is defined
        $query = $form->getQuery()->orderby($this->orderBy . ' ' . $this->orderDir);
        // Define in one line a pager Layout based on a pagerLayoutWithArrows object
        // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
        $this->pagerLayout = new PagerLayoutWithArrows(new Doctrine_Pager($query,
                                                                          $this->currentPage,
                                                                          $form->getValue('rec_per_page')
                                                                         ),
                                                       new Doctrine_Pager_Range_Sliding(array('chunk' => $this->pagerSlidingSize)),
                                                       $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
                                                      );
        // Sets the Pager Layout templates
        $this->setDefaultPaggingLayout($this->pagerLayout);
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->specimens = $this->pagerLayout->execute();

        $specs = array();
        foreach($this->specimens as $specimen)
        {
          $specs[$specimen->getId()] = $specimen->getId();
        }
        $specCodes = Doctrine::getTable('Codes')->getCodesRelatedArray('specimens', $specs);
        $this->codes = array();
        foreach($specCodes as $code)
        {
          if(! isset($this->codes[$code->getRecordId()]) ) 
            $this->codes[$code->getRecordId()] = array();
          $this->codes[$code->getRecordId()][] = $code;
        }
      }
    }
  }

  public function executeSameTaxon(sfWebRequest $request)
  {
    if($request->getParameter('specId') && $request->getParameter('taxonId'))
    {
      $result = Doctrine::getTable('Specimens')->findOneById($request->getParameter('specId'));
      if($result)
      {
        return ($result->getTaxonRef() == $request->getParameter('taxonId'))?$this->renderText("ok"):$this->renderText("not ok");
      }
    }
    return $this->renderText("ok");
  }

  public function executeGetTaxon(sfWebRequest $request)
  {
    $this->forward404Unless($request->getParameter('specId') && $request->getParameter('targetField'));
    $targetField = $request->getParameter('targetField');
    $specimen = Doctrine::getTable('Specimens')->findOneById($request->getParameter('specId'));
    $this->forward404Unless($specimen);
    return $this->renderText('{'.
                             '"'.$targetField.'":"'.$specimen->Taxonomy->getId().'",'.
                             '"'.$targetField.'_name":"'.$specimen->Taxonomy->getNameWithFormat().'"'.
                             '}'
                            );
  }

}
