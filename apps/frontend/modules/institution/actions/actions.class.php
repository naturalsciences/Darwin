<?php

/**
 * institution actions.
 *
 * @package    darwin
 * @subpackage institution
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class institutionActions extends sfActions
{
  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new InstitutionsFormFilter();
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new InstitutionsFormFilter();
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $this->form = new InstitutionsFormFilter();
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));



    if($request->getParameter('institutions_filters','') !== '')
    {
      $this->form->bind($request->getParameter('institutions_filters'));

      if ($this->form->isValid())
      {
        $this->orderBy = ($request->getParameter('orderby', '') == '') ? 'family_name' : $request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '') ? 'asc' : $request->getParameter('orderdir');
        $query = $this->form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        $this->currentPage = ($request->getParameter('page', '') == '')? 1: $request->getParameter('page');
	$this->s_url = 'institution/search?&page='.$this->currentPage.'&is_choose='.$this->is_choose;
        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager(
	    $query,
	    $this->currentPage,
	    $this->form->getValue('rec_per_page')
	  ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $pagerSlidingSize)
	    ),
	  $this->getController()->genUrl($this->s_url.'&orderby='.$this->orderBy.'&orderdir='.$this->orderDir).'/page/{%page_number}'
	);

        // Sets the Pager Layout templates
        $this->pagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
        $this->pagerLayout->setSelectedTemplate('<li>{%page}</li>');
        $this->pagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
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

  protected function initiateWidgets()
  {
      $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('people_institution_widget');
    if(! $this->widgets) $this->widgets=array();
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    $this->form = new InstitutionsForm($institution);
    $this->initiateWidgets();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    $this->form = new InstitutionsForm($institution);

    $this->processForm($request, $this->form);
    $this->initiateWidgets();
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($institution = Doctrine::getTable('Institutions')->findInstitution($request->getParameter('id')), sprintf('Object institution does not exist (%s).', $request->getParameter('id')));
    try{
        $institution->delete();
	$this->redirect('institution/edit?id='.$institution->getId());
    }
    catch(Doctrine_Exception $e)
    {
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
    }
    $this->form = new InstitutionsForm($institution);
    $this->form->getErrorSchema()->addError($error); 
    $this->initiateWidgets();
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
