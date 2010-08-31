<?php

/**
 * methods_and_tools actions.
 *
 * @package    darwin
 * @subpackage methods_and_tools
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class methods_and_toolsActions extends DarwinActions
{
  
  protected $widgetCategory = 'catalogue_methods_and_tools_widget';

  /**
    * Action executed when trying to add a new method in collecting methods table 
    * Returns id if successfull and error message if not
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeAddMethod(sfWebRequest $request)
  {
    // Test well action is Ajaxly called and that value parameter exist
    $this->forward404Unless($request->isMethod(sfRequest::POST) 
                            && $request->isXmlHttpRequest() 
                            && $request->hasParameter('value')
                           );
    try
    {
      // Define a new object and try to add and save new value passed
      $newMethod = new CollectingMethods;
      $newMethod->setMethod($request->getParameter('value'));
      $newMethod->save();
      // Return id of new record saved
      $response = $newMethod->getId();
    }
    catch (Doctrine_Exception $ne)
    {
      // Return database error if occurs
      $e = new DarwinPgErrorParser($ne);
      $response = $e->getMessage();
    }
    return $this->renderText($response);
  }

  /**
    * Action executed when trying to add a new tool in collecting tools table 
    * Returns id if successfull and error message if not
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeAddTool(sfWebRequest $request)
  {
    // Test well action is Ajaxly called and that value parameter exist
    $this->forward404Unless($request->isMethod(sfRequest::POST) 
                            && $request->isXmlHttpRequest() 
                            && $request->hasParameter('value')
                           );
    try
    {
      // Define a new object and try to add and save new value passed
      $newTool = new CollectingTools;
      $newTool->setTool($request->getParameter('value'));
      $newTool->save();
      // Return id of new record saved
      $response = $newTool->getId();
    }
    catch (Doctrine_Exception $ne)
    {
      // Return database error if occurs
      $e = new DarwinPgErrorParser($ne);
      $response = $e->getMessage();
    }
    return $this->renderText($response);
  }

  /**
    * Action executed when calling the methods directly
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeMethodsIndex(sfWebRequest $request)
  {
    $this->setCommonValues('methods_and_tools', 'method', $request);
    $this->s_url = 'methods_and_tools/search?notion=method&is_choose='.$this->is_choose;
    //  Initialization of the Search methods form
    $this->form = new CollectingMethodsFormFilter();
    $this->searchResults($this->form, $request);
  }

  /**
    * Action executed when calling the tools directly
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeToolsIndex(sfWebRequest $request)
  {
    $this->setCommonValues('methods_and_tools', 'tool', $request);
    $this->s_url = 'methods_and_tools/search?notion=tool&is_choose='.$this->is_choose;
    //  Initialization of the Search methods form
    $this->form = new CollectingToolsFormFilter();
    $this->searchResults($this->form, $request);
  }

  /**
    * Method executed when searching an expedition - trigger by the click on the search button
    * @param SearchExpeditionForm $form    The search expedition form instantiated that will be binded with the data contained in request
    * @param sfWebRequest         $request Request coming from browser
    * @var   int                  $pagerSlidingSize: Get the config value to define the range size of pager to be displayed in numbers (i.e.: with a value of 5, it will give this: << < 1 2 3 4 5 > >>)
    */
  protected function searchResults($form, sfWebRequest $request)
  {
    if($request->getParameter('searchMethodsAndTools','') !== '')
    {
      // Bind form with data contained in searchExpedition array
      $form->bind($request->getParameter('searchMethodsAndTools'));
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
           $this->methods_and_tools = $this->pagerLayout->execute();
      }
    }
  }

  /**
    * Action executed when calling the expeditions from an other screen
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeChoose(sfWebRequest $request)
  {
    // Initialization of the Search expedition form
    $this->form = new ExpeditionsFormFilter();
    // Remove surrounding layout
    $this->setLayout(false);
  }

  /**
    * Action executed when calling expedition/new: will trigger the display of a new empty form for new expedition encoding
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeNew(sfWebRequest $request)
  {
    $expedition = new Expeditions() ;
    $duplic = $request->getParameter('duplicate_id','0') ;
    $expedition = $this->getRecordIfDuplicate($duplic, $expedition);
    // Initialization of a new encoding expedition form
    $this->form = new ExpeditionsForm($expedition);
    if ($duplic)
    {
      $User = Doctrine::getTable('CataloguePeople')->findForTableByType('expeditions',$duplic) ;
      if(count($User))
      {
        foreach ($User['member'] as $key=>$val)
        {
           $user = new CataloguePeople() ;
           $user = $this->getRecordIfDuplicate($val->getRecordId(), $user);
           $this->form->addMember($key, $val->getPeopleRef(), $val->getOrderBy(), $user);
        }
      }
    }
    
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
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->findExcept($request->getParameter('id')), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    // Otherwise initialize the expedition encoding form
    $this->form = new ExpeditionsForm($expeditions);
    $this->loadWidgets();
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
    $this->forward404Unless($expeditions = Doctrine::getTable('Expeditions')->findExcept($request->getParameter('id')), sprintf('Object expeditions does not exist (%s).', array($request->getParameter('id'))));
    // Instantiate a new expedition form
    $this->form = new ExpeditionsForm($expeditions);
    // Process the form for saving informations
    $this->processForm($request, $this->form);
    $this->loadWidgets();
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
    try
    {
      $expeditions->delete();
      $this->redirect('expedition/index');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form = new ExpeditionsForm($expeditions);
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
    }
  }

  public function executeAddMember(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $people_ref = intval($request->getParameter('people_ref'));
    $this->form = new ExpeditionsForm();
    $this->form->addMember($number,$people_ref,$request->getParameter('iorder_by',0));
    return $this->renderPartial('member_row',array('form' =>  $this->form['newMember'][$number], 'row_num'=>$number));
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
      try
      {
        $item = $form->save();
        $this->redirect('expedition/edit?id='.$item->getId());
      }
      catch(Doctrine_Exception $ne)
      {
        $e = new DarwinPgErrorParser($ne);
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }}
 
