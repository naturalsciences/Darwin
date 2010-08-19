<?php

/**
 * methods_and_tools actions.
 *
 * @package    darwin
 * @subpackage methods_and_tools
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class methods_and_toolsActions extends sfActions
{
  // Function trying to add a new method in collecting methods table and returning id if successfull and error message if not
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

  // Function trying to add a new method in collecting methods table and returning id if successfull and error message if not
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
}
 
