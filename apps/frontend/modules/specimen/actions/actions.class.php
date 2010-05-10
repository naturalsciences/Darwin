<?php

/**
 * board actions.
 *
 * @package    darwin
 * @subpackage board
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class specimenActions extends DarwinActions
{
  protected $widgetCategory = 'specimen_widget';

  public function executeAddCode(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $spec = null;

    if($request->hasParameter('id') && $request->getParameter('id'))
      $spec = Doctrine::getTable('Specimens')->findExcept($request->getParameter('id') );
    
    $collectionId = $request->getParameter('collection_id', null);

    $form = new SpecimensForm($spec);
    $form->addCodes($number, $collectionId);
    return $this->renderPartial('spec_codes',array('form' => $form['newCode'][$number]));
  }

  protected function getSpecimenForm(sfWebRequest $request)
  {
    $spec = null;

    if($request->hasParameter('spec_id') && $request->getParameter('spec_id'))
      $spec = Doctrine::getTable('Specimens')->findExcept($request->getParameter('spec_id') );
    
    $form = new SpecimensForm($spec);
    return $form;
  }

  public function executeAddIdentification(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $order_by = intval($request->getParameter('order_by',0));
    $spec_form = $this->getSpecimenForm($request);
    $spec_form->addIdentifications($number, $order_by);
    return $this->renderPartial('spec_identifications',array('form' => $spec_form['newIdentification'][$number], 'row_num' => $number, 'spec_id'=>$request->getParameter('spec_id',0)));
  }

  public function executeAddIdentifier(sfWebRequest $request)
  {
    $spec_form = $this->getSpecimenForm($request);
    $number = intval($request->getParameter('num'));
    $identifier_number = intval($request->getParameter('identifier_num'));
    $identifier_order_by = intval($request->getParameter('iorder_by',0));
    $ident = null;

    if($request->hasParameter('identification_id') && $request->getParameter('identification_id'))
    {
      $ident = $spec_form->getEmbeddedForm('Identifications')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number, $identifier_order_by);
      $spec_form->reembedIdentifications($ident, $number);
      return $this->renderPartial('spec_identification_identifiers',array('form' => $spec_form['Identifications'][$number]['newIdentifier'][$identifier_number]));
    }
    else
    {
      $spec_form->addIdentifications($number, 0);
      $ident = $spec_form->getEmbeddedForm('newIdentification')->getEmbeddedForm($number);
      $ident->addIdentifiers($identifier_number, $identifier_order_by);
      $spec_form->reembedNewIdentification($ident, $number);
      return $this->renderPartial('spec_identification_identifiers',array('form' => $spec_form['newIdentification'][$number]['newIdentifier'][$identifier_number]));
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->loadWidgets();
    $this->form = new SpecimensForm();
    $this->form->addCodes(0, null);
    $this->form->addIdentifications(0,0);
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
    $specimen = Doctrine::getTable('Specimens')->find($request->getParameter('id'));
    $this->forward404Unless($specimen,'Specimen not Found');
    $this->form = new SpecimensForm($specimen);
    $this->setTemplate('new');
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->loadWidgets();

    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $specimen = Doctrine::getTable('Specimens')->find($request->getParameter('id'));
    $this->forward404Unless($specimen,'Specimen not Found');
    $this->form = new SpecimensForm($specimen);

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
//     $this->searchForm = new TaxonomyFormFilter(array('table' => $this->table, 'level' => $this->level, 'caller_id' => $this->caller_id));
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
        foreach($this->specimens as $specimen)
        {
          $codes = array();
          foreach($specCodes as $code)
          {
            if($specimen->getId()==$code->getRecordId())
              $codes[] = $code->toArray();
          }
          $specimen->SpecimensCodes->fromArray($codes);
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
