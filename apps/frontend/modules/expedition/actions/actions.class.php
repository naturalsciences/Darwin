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
  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));    
    $this->setLayout(false);
  }
  
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
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        $this->is_choose = $request->getParameter('is_choose', false);
        $this->orderBy = ($request->getParameter('orderby', '') == '')?'name':$request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '')?'asc':$request->getParameter('orderdir');
        $this->currentPage = ($request->getParameter('page', '') == '')?1:intval($request->getParameter('page'));
        $this->expePagerLayout = new PagerLayoutWithArrows(new Doctrine_Pager(Doctrine::getTable('Expeditions')
                                                                               ->getExpLike($form->getValue('name'), 
                                                                                            $form->getValue('from_date'),
                                                                                            $form->getValue('to_date'),
                                                                                            $this->orderBy,
                                                                                            $this->orderDir
                                                                                           ),
                                                                               $this->currentPage,
                                                                               $form->getValue('rec_per_page')
                                                                              ),
                                                            new Doctrine_Pager_Range_Sliding(array('chunk' => $pagerSlidingSize)),
                                                            $this->getController()->genUrl('expedition/search?orderby='.$this->orderBy.
                                                                                           '&orderdir='.$this->orderDir.
                                                                                           '&is_choose='.$this->is_choose.
                                                                                           '&page='
                                                                                          ).'{%page_number}'
                                                          );
        $this->expePagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
        $this->expePagerLayout->setSelectedTemplate('<li>{%page}</li>');
        $this->expePagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
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
