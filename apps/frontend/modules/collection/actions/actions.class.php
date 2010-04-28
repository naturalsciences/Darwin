<?php

/**
 * collection actions.
 *
 * @package    darwin
 * @subpackage collection
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class collectionActions extends DarwinActions
{
  protected $widgetCategory = 'catalogue_collections_widget';

  public function executeCompleteOptions(sfWebRequest $request)
  {
    $this->collections = Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($request->getParameter('institution'));
    $this->setLayout(false);
  }
  
  public function executeChoose(sfWebRequest $request)
  {
    $this->institutions = Doctrine::getTable('Collections')->fetchByInstitutionList();
    $this->setLayout(false);
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->institutions = Doctrine::getTable('Collections')->fetchByInstitutionList();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless(Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getDbUserType() > 2 , sprintf('You are not allowed to access to this page'));
    $this->form = new CollectionsForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post'));
    $options = $request->getParameter('collections');
    $this->form = new CollectionsForm(null,array('new_with_error' => true, 'institution' => $options['institution_ref']));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($collections = Doctrine::getTable('Collections')->findExcept($request->getParameter('id')), sprintf('Object collections does not exist (%s).', array($request->getParameter('id'))));
//    $this->forward404Unless(Doctrine::getTable('Collections')->find( $this->getUser()->getAttribute('db_user_id')), sprintf('You are not allowed to edit this collection'));    
    $this->form = new CollectionsForm($collections);
    $this->loadWidgets();
  }

  public function executeAddValue(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $user_ref = intval($request->getParameter('user_ref'));
    $collection = Doctrine::getTable('Collections')->findExcept($request->getParameter('id')) ;
    $form = new CollectionsForm($collection);
    $form->addValue($number,$user_ref);
    return $this->renderPartial('coll_rights',array('form' => $form['newVal'][$number]));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->forward404Unless($collections = Doctrine::getTable('Collections')->findExcept($request->getParameter('id')), sprintf('Object collections does not exist (%s).', array($request->getParameter('id'))));
    $this->form = new CollectionsForm($collections);

    $this->processForm($request, $this->form);
    $this->loadWidgets();

    $this->setTemplate('edit');
  }

   /**
   * @TODO: PREVENT error when has child!
   */
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($collections = Doctrine::getTable('Collections')->findExcept($request->getParameter('id')), sprintf('Object collections does not exist (%s).', array($request->getParameter('id'))));
    try
    {
      $collections->delete();
    }
    catch(Doctrine_Connection_Pgsql_Exception $e)
    {
      $this->form = new CollectionsForm($collections);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
      return ;
    }
    $this->redirect('collection/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
        try{
            $collections = $form->save();
            //$this->redirect('collection/index');
        }
        catch(Exception $e)
        {
            $error = new sfValidatorError(new savedValidator(),$e->getMessage());
            $form->getErrorSchema()->addError($error); 
        }
    }
  }
}
