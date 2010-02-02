<?php

/**
 * igs actions.
 *
 * @package    darwin
 * @subpackage igs
 * @category   actions
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class igsActions extends sfActions
{
  /**
    * Action executed when calling the expeditions from an other screen
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeChoose(sfWebRequest $request)
  {
    // Initialization of the Search expedition form
    $this->form = new IgsFormFilter();
    // Remove surrounding layout
    $this->setLayout(false);
  }

  /**
    * Action executed when calling the expeditions directly
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeIndex(sfWebRequest $request)
  {
    //  Initialization of the Search expedition form
    $this->form = new IgsFormFilter();    
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new igsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new igsForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($igs = Doctrine::getTable('igs')->find(array($request->getParameter('id'))), sprintf('Object igs does not exist (%s).', $request->getParameter('id')));
    $this->form = new igsForm($igs);
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets('catalogue_igs_widget');
    if(! $this->widgets) $this->widgets=array();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($igs = Doctrine::getTable('igs')->find(array($request->getParameter('id'))), sprintf('Object igs does not exist (%s).', $request->getParameter('id')));
    $this->form = new igsForm($igs);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($igs = Doctrine::getTable('igs')->find(array($request->getParameter('id'))), sprintf('Object igs does not exist (%s).', $request->getParameter('id')));
    $igs->delete();

    $this->redirect('igs/index');
  }
  public function executeSearch(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('post'));
    // Instantiate a new expedition form
    $this->form = new IgsFormFilter();
    // Triggers the search result function
    $this->searchResults($this->form,$request);    
  }

  public function executeSearchFor(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('get'));
    // Triggers the search ID function
    if($request->getParameter('searchedCrit', '') !== '')
    {
      $igId = Doctrine::getTable('Igs')->findOneByIgNum($request->getParameter('searchedCrit'));
      if ($igId) 
        return $this->renderText($igId->getId());
      else
        return $this->renderText('not found');
    }  
    return $this->renderText('');
  }

  public function executeSearchForLimited(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('get'));
    // Triggers the search ID function
    if($request->getParameter('q', '') !== '' && $request->getParameter('limit', '') !== '')
    {
      $igIds = Doctrine::getTable('Igs')->findByIgNumLimited($request->getParameter('q'), $request->getParameter('limit'));
      if ($igIds) 
      {
        $values=array();
        foreach($igIds as $key=>$value)
        {
          $values[$key]=$value->getIgNum();
        }
        return $this->renderText(implode("\n",$values));
      }
      else
        return $this->renderText(array(''));
    }  
    return $this->renderText(array(''));
  }

  /**
    * Method executed when searching an ig - trigger by the click on the search button
    * @param SearchIgForm         $form    The search ig form instantiated that will be binded with the data contained in request
    * @param sfWebRequest         $request Request coming from browser
    * @var   int                  $pagerSlidingSize: Get the config value to define the range size of pager to be displayed in numbers (i.e.: with a value of 5, it will give this: << < 1 2 3 4 5 > >>)
    */
  protected function searchResults(IgsFormFilter $form, sfWebRequest $request)
  {
    if($request->getParameter('searchIg','') !== '')
    {
      // Bind form with data contained in searchIg array
      $form->bind($request->getParameter('searchIg'));
      // Test that the form binded is still valid (no errors)
      if ($form->isValid())
      {
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        // Define all properties that will be either used by the data query or by the pager
        // They take their values from the request. If not present, a default value is defined
        $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));
        $this->orderBy = ($request->getParameter('orderby', '') == '')?'ig_num':$request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '')?'asc':$request->getParameter('orderdir');
        $query = $form->getQuery()->orderby($this->orderBy . ' ' . $this->orderDir);
        $this->currentPage = ($request->getParameter('page', '') == '')?1:intval($request->getParameter('page'));
        // Define in one line a pager Layout based on a PagerLayoutWithArrows object
        // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getIgLike method of IgTable class)
        $this->igPagerLayout = new PagerLayoutWithArrows(new Doctrine_Pager($query,
                                                                            $this->currentPage,
                                                                            $form->getValue('rec_per_page')
                                                                           ),
                                                            new Doctrine_Pager_Range_Sliding(array('chunk' => $pagerSlidingSize)),
                                                            $this->getController()->genUrl('igs/search?orderby='.$this->orderBy.
                                                                                           '&orderdir='.$this->orderDir.
                                                                                           '&is_choose='.$this->is_choose.
                                                                                           '&page='
                                                                                          ).'{%page_number}'
                                                          );
        // Sets the Pager Layout templates
        $this->igPagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
        $this->igPagerLayout->setSelectedTemplate('<li>{%page}</li>');
        $this->igPagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->igPagerLayout->getPager()->getExecuted()) $this->igss = $this->igPagerLayout->execute();
      }
    }
  }

  /**
    * Method called to process the encoding form (called by executeCreate or executeUpdate actions)
    * @param sfWebRequest $request Request coming from browser
    * @param sfForm       $form    The encoding form passed to be bound with data brought by the request and to be checked
    * @var   sfForm       $igs: Form saved
    */ 
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
      $igs = $form->save();

      $this->redirect('igs/edit?id='.$igs->getId());
    }
  }
}
