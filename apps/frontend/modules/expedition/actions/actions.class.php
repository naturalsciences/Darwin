<?php

/**
 * expedition actions.
 *
 * @package    darwin
 * @subpackage expedition
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class expeditionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));    
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ExpeditionsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));

    $this->form = new ExpeditionsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new ExpeditionsForm($expeditions);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new ExpeditionsForm($expeditions);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    $expeditions->delete();

    $this->redirect('expedition/index');
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));
    $this->searchResults($this->form,$request);    
  }

  protected function searchResults($form, $request)
  {
    if($request->getParameter('searchExpedition','') !== '')
    {
      $form->bind($request->getParameter('searchExpedition'));
      if ($form->isValid())
      {
        $resultsPerPage = intval(sfConfig::get('app_resultsPerPage'));
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        $this->orderBy = ($request->getParameter('orderby', '') == '')?'name':$request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '')?'asc':$request->getParameter('orderdir');
        $this->currentPage = ($request->getParameter('currentPage', '') == '')?1:intval($request->getParameter('currentPage'));
        $this->resultsPerPage = ($request->getParameter('resultsPerPage', '') == '')?$resultsPerPage:intval($request->getParameter('resultsPerPage'));;
        $this->expePagerLayout = new Doctrine_Pager_Layout(new Doctrine_Pager(Doctrine::getTable('Expeditions')
                                                                              ->getExpLike($form->getValue('name'), 
                                                                                           $form->getValue('from_date'),
                                                                                           $form->getValue('to_date'),
                                                                                           $this->orderBy,
                                                                                           $this->orderDir
                                                                                          ),
                                                                              $this->currentPage,
                                                                              $this->resultsPerPage
                                                                             ),
                                                           new Doctrine_Pager_Range_Sliding(array('chunk' => $pagerSlidingSize)),
                                                           $this->getController()->genUrl('expedition/search?orderby='.$this->orderBy.'&page=').'{%page_number}'
                                                         );
        $this->expePagerLayout->setTemplate('<li>:<a href="{%url}">{%page}</a></li>');
        $this->expePagerLayout->setSelectedTemplate('<li>:[{%page}]</li>');
        if (! $this->expePagerLayout->getPager()->getExecuted()) $this->expeditions = $this->expePagerLayout->execute();
      }
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $expeditions = $form->save();

      $this->redirect('expedition/edit?id='.$expeditions->getId());
    }
  }

}
