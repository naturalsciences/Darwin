<?php

/**
 * mineralogy actions.
 *
 * @package    darwin
 * @subpackage mineralogy
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class mineralogyActions extends DarwinActions
{
  protected $widgetCategory = 'catalogue_mineralogy_widget';

  public function executeChoose(sfWebRequest $request)
  {
    $this->searchForm = new MineralogyFormFilter(array('table'=> 'mineralogy'));
    $this->setLayout(false);
  }

  public function executeDelete(sfWebRequest $request)
  {
    $this->forward404Unless(
      $unit = Doctrine::getTable('Mineralogy')->findExcept($request->getParameter('id')),
      sprintf('Object mineralogy does not exist (%s).', array($request->getParameter('id')))
    );

    try
    {
      $unit->delete();
    }
    catch(Doctrine_Connection_Pgsql_Exception $e)
    {
      $this->form = new MineralogyForm($unit);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form->getErrorSchema()->addError($error); 
      $this->setTemplate('edit');
      return ;
    }
    $this->redirect('mineralogy/index');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new MineralogyForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new MineralogyForm();
    $this->processForm($request,$this->form);
    $this->setTemplate('new');
  }
    
  public function executeEdit(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Mineralogy')->findExcept($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->form = new MineralogyForm($unit);
    $this->loadWidgets();

    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('mineralogy',$unit->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $unit = Doctrine::getTable('Mineralogy')->find($request->getParameter('id'));
    $this->forward404Unless($unit,'Unit not Found');
    $this->form = new MineralogyForm($unit);
    
    $relations = Doctrine::getTable('CatalogueRelationships')->getRelationsForTable('mineralogy',$unit->getId());
    $this->processForm($request,$this->form);

    $this->loadWidgets();

    $this->setTemplate('edit');
  }


  public function executeIndex(sfWebRequest $request)
  {
    $this->searchForm = new MineralogyFormFilter(array('table'=> 'mineralogy'));
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind( $request->getParameter($form->getName()) );
    if ($form->isValid())
    {
      try{
	$form->save();
	$this->redirect('mineralogy/edit?id='.$form->getObject()->getId());
      }
      catch(sfStopException $e)
      { throw $e; }
      catch(Exception $e)
      {
	$error = new sfValidatorError(new savedValidator(),$e->getMessage());
	$form->getErrorSchema()->addError($error); 
      }
    }
  }

  public function executeSearchFor(sfWebRequest $request)
  {
    // Forward to a 404 page if the method used is not a post
    $this->forward404Unless($request->isMethod('get'));
    // Triggers the search ID function
    if($request->getParameter('searchedCrit', '') !== '')
    {
      $unitId = Doctrine::getTable('Mineralogy')->findOneByCode($request->getParameter('searchedCrit'));
      if ($unitId) 
        return $this->renderText($unitId->getCode());
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
      $codes = Doctrine::getTable('Mineralogy')->findByCodeLimited($request->getParameter('q'), $request->getParameter('limit'));
      if ($codes) 
      {
        $values=array();
        foreach($codes as $key=>$value)
        {
          $values[$key]=$value->getCode();
        }
        return $this->renderText(implode("\n",$values));
      }
      else
        return $this->renderText(array(''));
    }  
    return $this->renderText(array(''));
  }

}