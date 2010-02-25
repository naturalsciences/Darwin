<?php

/**
 * institution actions.
 *
 * @package    darwin
 * @subpackage institution
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class institutionActions extends DarwinActions
{
  protected $widgetCategegory = 'people_institution_widget';

  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new InstitutionsFormFilter();
    if( $request->getParameter('only_role','0') !=0)
    {
      $this->form->setDefault('only_role',$request->getParameter('only_role'));
    }
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new InstitutionsFormFilter();
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->setCommonValues('institution', 'family_name', $request);
    $this->form = new InstitutionsFormFilter();
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));

    if($request->getParameter('institutions_filters','') !== '')
    {
      $this->form->bind($request->getParameter('institutions_filters'));

      if ($this->form->isValid())
      {
        $query = $this->form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager(
	    $query,
	    $this->currentPage,
	    $this->form->getValue('rec_per_page')
	  ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $this->pagerSlidingSize)
	    ),
	  $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
	);

        // Sets the Pager Layout templates
        $this->setDefaultPaggingLayout($this->pagerLayout);
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->items = $this->pagerLayout->execute();
      }
    }    
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new InstitutionsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new InstitutionsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    $this->form = new InstitutionsForm($institution);
    $this->loadWidgets();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    $this->form = new InstitutionsForm($institution);

    $this->processForm($request, $this->form);
    $this->loadWidgets();
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    try{
        $institution->delete();
	$this->redirect('institution/index');
    }
    catch(Doctrine_Exception $e)
    {
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
    }
    $this->form = new InstitutionsForm($institution);
    $this->form->getErrorSchema()->addError($error); 
    $this->loadWidgets();
    $this->setTemplate('edit');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try{
        $institution = $form->save();
	$this->redirect('institution/edit?id='.$institution->getId());
      }
      catch(Doctrine_Exception $e)
      {
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }
}
