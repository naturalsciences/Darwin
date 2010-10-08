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

  public function executeAddSpecCodes(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'));
    $this->collCodes = Doctrine::getTable('Collections')->findExcept($request->getParameter('id'));
    $this->form = new CollectionsCodesForm($this->collCodes);
    
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('collections'));
      if($this->form->isValid())
      {
        try
        {
          $this->form->save();
          return $this->renderText('ok');
        }
        catch(Exception $e)
        {
          $error = new sfValidatorError(new savedValidator(),$e->getMessage());
          $this->form->getErrorSchema()->addError($error); 
        }
      }
    }
  }

  public function executeDeleteSpecCodes(sfWebRequest $request)
  {
    $this->forward404Unless($request->hasParameter('id'),'No id given');
    $item = Doctrine::getTable('Collections')->findExcept($request->getParameter('id'));
    $this->forward404Unless($item,'No such item');
    try
    {
      $item->setCodePrefix(Doctrine::getTable('Collections')->getDefaultValueOf('code_prefix'));
      $item->setCodePrefixSeparator(Doctrine::getTable('Collections')->getDefaultValueOf('code_prefix_separator'));
      $item->setCodeSuffix(Doctrine::getTable('Collections')->getDefaultValueOf('code_suffix'));
      $item->setCodeSuffixSeparator(Doctrine::getTable('Collections')->getDefaultValueOf('code_suffix_separator'));
      $item->setCodeAutoIncrement(Doctrine::getTable('Collections')->getDefaultValueOf('code_auto_increment'));
      $item->setCodePartCodeAutoCopy(Doctrine::getTable('Collections')->getDefaultValueOf('code_part_code_auto_copy'));
      $item->save();
      return $this->renderText('ok');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      return $this->renderText($e->getMessage());
    }
  }

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
    $this->user_allowed = ($this->getUser()->getDbUserType() < Users::MANAGER?false:true) ;
  }

  public function executeNew(sfWebRequest $request)
  {
    $db_user_type = Doctrine::getTable('Users')->find( $this->getUser()->getId() )->getDbUserType();
    if($db_user_type < Users::MANAGER) $this->forwardToSecureAction();
    $collection = new Collections();
    $duplic = $request->getParameter('duplicate_id','0') ;
    $collection = $this->getRecordIfDuplicate($duplic, $collection);
    $this->form = new CollectionsForm($collection,array('duplicate'=> true));    
    if ($duplic)
    {
      $User = Doctrine::getTable('CollectionsRights')->getAllUserRef($duplic) ;      
      foreach ($User as $key=>$val)
      {
         $this->form->addValue($key, $val->getUserRef());
      }
    }  
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
//	$db_user_type = Doctrine::getTable('Users')->find( $this->getUser()->getId() )->getDbUserType();
//    if($db_user_type < Users::MANAGER) $this->forwardToSecureAction();
    $this->form = new CollectionsForm($collections);
    $this->loadWidgets();
  }

  public function executeAddValue(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $user_ref = intval($request->getParameter('user_ref'));
    $right = $request->getParameter('right') ; // used to determine if we add an encoder right or a secondary admin right
    if($request->hasParameter('id'))
    {
      $this->ref_id = $request->getParameter('id') ;
	    $collection = Doctrine::getTable('Collections')->findExcept($this->ref_id) ;
      $form = new CollectionsForm($collection);
    }
    else $form = new CollectionsForm();
    $form->addValue($number,$user_ref,$right);
    if($right == 'encoder')
      return $this->renderPartial('coll_rights',array('form' => $form['newVal'][$number],'ref_id' => $this->ref_id));
    else
      return $this->renderPartial('coll_rights',array('form' => $form['newAdmin'][$number],'ref_id' => ''));
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

  public function executeRights(sfWebRequest $request)
  {
    $id = $request->getParameter('collection_ref') ;
    $user = $request->getParameter('user_ref') ;
    $this->forward404Unless(Doctrine::getTable('Collections')->fetchByCollectionParent($id), sprintf('Object collections does not exist (%s).', $id));
    $this->user_formated_name = Doctrine::getTable('Users')->findUser($user)->getFormatedName() ;
    $old_rights = Doctrine::getTable('CollectionsRights')->findCollectionsByUser($user)->toArray() ;
    $old_right = array() ;
    foreach($old_rights as $key=>$right)
      $old_right[] = $right['collection_ref'] ;
    $this->form = new SubCollectionsForm(null,array('collection_ref' => $id ,'user_ref' => $user,'old_right' => $old_right));
    $this->form->user = $user ;
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('sub_collection')) ;
      if($this->form->isValid())
      {
        $this->form->save();
        return $this->renderText('ok') ;
      }
    }
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()));
    if ($form->isValid())
    {
        try{
            $collections = $form->save();
            $this->redirect('collection/edit?id='.$collections->getId());
        }
        catch(Exception $e)
        {
            $error = new sfValidatorError(new savedValidator(),$e->getMessage());
            $form->getErrorSchema()->addError($error); 
        }
    }
  }    
}
