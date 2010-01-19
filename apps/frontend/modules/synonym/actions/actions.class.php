<?php
/**
 * synonym actions.
 *
 * @package    darwin
 * @subpackage synonym
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class synonymActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeAdd(sfWebRequest $request)
  {

    $this->classification = new ClassificationSynonymies();
    $this->classification->setReferencedRelation($request->getParameter('table'));

    $this->form = new ClassificationSynonymiesForm($this->classification, array('table' => $request->getParameter('table')));
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('classification_synonymies'));
	if($this->form->isValid())
	{
	    try
	    {
	      $conn = Doctrine_Manager::connection();
	      $conn->beginTransaction();

	      Doctrine::getTable('ClassificationSynonymies')
		->mergeSynonyms(
		  $request->getParameter('table'),
		  $this->form->getValue('record_id'),
		  $request->getParameter('id'),
		  $this->form->getValue('group_name')
		);
	      $conn->commit();
	      return $this->renderText('ok');
	    }
	    catch(Doctrine_Exception $e)
	    {
	      $conn->rollback();
	      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	      $this->form->getErrorSchema()->addError($error); 
	    }	  
	 }
    }
    $this->searchForm = new SearchCatalogueForm(array('table'=> $request->getParameter('table') ));
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $conn = Doctrine_Manager::connection();
    $conn->beginTransaction();

    $synonym = Doctrine::getTable('ClassificationSynonymies')->find($request->getParameter('id'));
    
    if( count(Doctrine::getTable('ClassificationSynonymies')->findByGroupId($synonym->getGroupId())) > 2)
    {
      $synonym->delete();
    }
    else     //Delete the entire group if there is only two record
    {
      Doctrine::getTable('ClassificationSynonymies')->deleteAllItemInGroup( $synonym->getGroupId() );
    }
    $conn->commit();
    return $this->renderText('ok');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->groups = Doctrine::getTable('ClassificationSynonymies')
       ->findAllForRecord($request->getParameter('table'), $request->getParameter('id'), $request->getParameter('group_id') );
    $this->form = new SynonymEditForm();
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('synonym_edit'));
	if($this->form->isValid())
	{
	  try
	  {
	    $conn = Doctrine_Manager::connection();
	    $conn->beginTransaction();
	    Doctrine::getTable('ClassificationSynonymies')->saveOrderAndResetBasio( substr($this->form->getValue('orders'),1) );
	    if($this->form->getValue('basionym_id')!= '')
	      $synonym = Doctrine::getTable('ClassificationSynonymies')->find($this->form->getValue('basionym_id'));
	    else
	      $synonym = null;
	    if($synonym)
	    {
		$synonym->setIsBasionym(true);
		$synonym->save();
	    }
	      
	    $conn->commit();
	    return $this->renderText('ok');
	  }
	  catch(Doctrine_Exception $e)
	  {
	    $conn->rollback();
	    return $this->renderText($e->getMessage());
	  }
	}
    }    
  }

  public function executeChecks(sfWebRequest $request)
  {
    return $this->renderText(
      Doctrine::getTable('ClassificationSynonymies')->findGroupIdFor(
	$request->getParameter('table'),
	$request->getParameter('id'),
	$request->getParameter('type'))
      );
  }
}
