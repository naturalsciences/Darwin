<?php

/**
 * Expedition actions.
 *
 * @package    darwin
 * @subpackage expedition
 * @category   actions
 * @author     DB team <collections@naturalsciences.be>
 * @var        sfForm                $this->form: Encoding form
 * @var        SearchExpeditionForm  $this->form: Search expedition Form
 * @var        bool|int              $this->is_choose: Flag telling if the search form has been called from an other window or directly
 * @var        string                $this->orderBy: field by which the data are ordered
 * @var        string                $this->orderDir: Indicates the order of the order by: Ascending or Descending
 * @var        int                   $this->currentPage: Give the page of data the user is on (used with Doctrine_Pager)
 * @var        PagerLayoutWithArrows $this->expePagerLayout: Pager layout initialized
 * @var        Doctrine_Collection   $this->expeditions: Collection of data, resulting of the query triggered by an expedition search
 */
class expeditionActions extends sfActions
{

  /**
    * Action executed when calling the expeditions from an other screen
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeChoose(sfWebRequest $request)
  {
    // Initialization of the Search expedition form
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));
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
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));    
  }

  /**
    * Action executed when calling expedition/new: will trigger the display of a new empty form for new expedition encoding
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeNew(sfWebRequest $request)
  {
    // Initialization of a new encoding expedition form
    $this->form = new ExpeditionsForm();
  }

  /**
    * Action executed when creating a new expedition by clicking on the save after edition
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeCreate(sfWebRequest $request)
  {
    // Trigger the protection against the XSS attack
    $request->checkCSRFProtection();
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('post'));
    // Instantiate the expedition form
    $this->form = new ExpeditionsForm();
    // Process the form for saving informations
    $this->processForm($request, $this->form);
    // Set the template to new
    $this->setTemplate('new');
  }

  /**
    * Action executed when calling expedition/edit: will trigger the display of a form for expedition encoding
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeEdit(sfWebRequest $request)
  {
    // Forward to a 404 page if the requested expedition id is not found
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    // Otherwise initialize the expedition encoding form
    $this->form = new ExpeditionsForm($expeditions);
  }

  /**
    * Action executed when updating expedition data by clicking on the save after edition
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeUpdate(sfWebRequest $request)
  {
    // Trigger the protection against the XSS attack
    $request->checkCSRFProtection();
    // If method is <> from post or put and if the id edited and to be saved doesn't exist anymore... forward to a 404 page
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    // Instantiate a new expedition form
    $this->form = new ExpeditionsForm($expeditions);
    // Process the form for saving informations
    $this->processForm($request, $this->form);
    // Set the template to edit
    $this->setTemplate('edit');
  }

  /**
    * Action executed when deleting an expedition by clicking on the delete link
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeDelete(sfWebRequest $request)
  {
    // Trigger the protection against the XSS attack
    $request->checkCSRFProtection();
    // Forward to a 404 page if the expedition to be deleted has not been found
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->find(array($request->getParameter('id'))), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    // Effectively triggers the delete method of the expedition table
    $expeditions->delete();
    // Redirect to the expedition index page
    $this->redirect('expedition/index');
  }

  /**
    * Action executed when searching an expedition - trigger by the click on the search button
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeSearch(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('post'));
    // Instantiate a new expedition form
    $this->form = new SearchExpeditionForm(null, array('culture' => $this->getUser()->getCulture(), 'month_format' => 'short_name'));
    // Triggers the search result function
    $this->searchResults($this->form,$request);    
  }

  /**
    * Method executed when searching an expedition - trigger by the click on the search button
    * @param SearchExpeditionForm $form    The search expedition form instantiated that will be binded with the data contained in request
    * @param sfWebRequest         $request Request coming from browser
    * @var   int                  $pagerSlidingSize: Get the config value to define the range size of pager to be displayed in numbers (i.e.: with a value of 5, it will give this: << < 1 2 3 4 5 > >>)
    */
  protected function searchResults(SearchExpeditionForm $form, sfWebRequest $request)
  {
    if($request->getParameter('searchExpedition','') !== '')
    {
      // Bind form with data contained in searchExpedition array
      $form->bind($request->getParameter('searchExpedition'));
      // Test that the form binded is still valid (no errors)
      if ($form->isValid())
      {
        $pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
        // Define all properties that will be either used by the data query or by the pager
        // They take their values from the request. If not present, a default value is defined
        $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));
        $this->orderBy = ($request->getParameter('orderby', '') == '')?'name':$request->getParameter('orderby');
        $this->orderDir = ($request->getParameter('orderdir', '') == '')?'asc':$request->getParameter('orderdir');
        $this->currentPage = ($request->getParameter('page', '') == '')?1:intval($request->getParameter('page'));
        // Define in one line a pager Layout based on a PagerLayoutWithArrows object
        // This pager layout is based on a Doctrine_Pager, itself based on a customed Doctrine_Query object (call to the getExpLike method of ExpeditionTable class)
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
        // Sets the Pager Layout templates
        $this->expePagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
        $this->expePagerLayout->setSelectedTemplate('<li>{%page}</li>');
        $this->expePagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->expePagerLayout->getPager()->getExecuted()) $this->expeditions = $this->expePagerLayout->execute();
      }
    }
  }

  /**
    * Method called to process the encoding form (called by executeCreate or executeUpdate actions)
    * @param sfWebRequest $request Request coming from browser
    * @param sfForm       $form    The encoding form passed to be bound with data brought by the request and to be checked
    * @var   sfForm       $expedition: Form saved
    */ 
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
