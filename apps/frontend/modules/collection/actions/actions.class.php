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
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

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
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

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
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

    $this->collections = Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($request->getParameter('institution'));
    $this->setLayout(false);
  }
  
  /* function that modify the institution when we change the parent_ref */
  public function executeSetInstitution(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();
     
    $collection = new Collections() ;
    $collection->setInstitutionRef(Doctrine::getTable('Collections')->getInstitutionNameByCollection($request->getParameter('parent_ref'))->getId()) ;
    $this->form = new CollectionsForm($collection);
    $this->setLayout(false);
  }
    
  public function executeChoose(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::ENCODER) ) $this->forwardToSecureAction();

    $this->institutions = Doctrine::getTable('Collections')->fetchByInstitutionList($this->getUser());
    $this->setLayout(false);
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->institutions = Doctrine::getTable('Collections')->fetchByInstitutionList($this->getUser());
  }

  public function executeNew(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

    $duplic = $request->getParameter('duplicate_id','0') ;
    $collection = $this->getRecordIfDuplicate($duplic, new Collections());
    $this->form = new CollectionsForm($collection, array('duplicate'=> true));    
    if ($duplic)
    {
      $User = Doctrine::getTable('CollectionsRights')->getAllUserRef($collection->getId()) ;
      foreach ($User as $key=>$val)
      {
         $this->form->addValue($key, $val->getUserRef(),'encoder');
      }
    }  
  }

  public function executeCreate(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

    $this->forward404Unless($request->isMethod('post'));
    $options = $request->getParameter('collections');
    $this->form = new CollectionsForm(null,array('new_with_error' => true, 'institution' => $options['institution_ref']));
    
    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();
    $collection = Doctrine::getTable('Collections')->findExcept($request->getParameter('id'));
    $this->forward404Unless($collection, 'collections does not exist');
    $this->level = $this->getUser()->getDbUserType() ;      
    $this->form = new CollectionsForm($collection);
    $this->loadWidgets();
  }

  public function executeAddValue(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

    $number = intval($request->getParameter('num'));
    $user_ref = intval($request->getParameter('user_ref'));

    if($request->hasParameter('id'))
    {
      $this->ref_id = $request->getParameter('id') ;
	    $collection = Doctrine::getTable('Collections')->findExcept($this->ref_id) ;
      $form = new CollectionsForm($collection);
    }
    else $form = new CollectionsForm();
    $form->addValue($number,$user_ref,$right);

    return $this->renderPartial('coll_rights',array('form' => $form['newVal'][$number],'ref_id' => ''));
  }

  public function executeUpdate(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();
    $this->forward404Unless($request->isMethod('post') || $request->isMethod('put'));
    $this->level = $this->getUser()->getDbUserType() ;    
    $collection = Doctrine::getTable('Collections')->findExcept($request->getParameter('id'));

    $this->forward404Unless($collection, 'collections does not exist');
    $this->form = new CollectionsForm($collection);

    $this->processForm($request, $this->form);
    $this->loadWidgets();

    $this->setTemplate('edit');
  }

   /**
   * @TODO: PREVENT error when has child!
   */
  public function executeDelete(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

    $request->checkCSRFProtection();
    $collection = Doctrine::getTable('Collections')->findExcept($request->getParameter('id'));

    $this->forward404Unless($collection, 'collections does not exist');

    try
    {
      $collection->delete();
    }
    catch(Doctrine_Connection_Pgsql_Exception $e)
    {
      $this->form = new CollectionsForm($collection);
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
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();

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

  public function executeView(sfWebRequest $request)
  {
    $this->forward404Unless($this->collection = Doctrine::getTable('Collections')->findExcept($request->getParameter('id')), "Collection does not Exist");
    if (!Doctrine::getTable('collectionsRights')->findOneByCollectionRefAndUserRef($request->getParameter('id'),$this->getUser()->getId()))
      $this->forwardToSecureAction();
    $this->form = new CollectionsForm($this->collection) ;    
  }

  public function executeWidgetsRight(sfWebRequest $request)
  {
    if(! $this->getUser()->isAtLeast(Users::MANAGER) ) $this->forwardToSecureAction();
    if (!Doctrine::getTable('collectionsRights')->findOneByCollectionRefAndUserRef($request->getParameter('collection_ref'),$this->getUser()->getId()))
      $this->forwardToSecureAction();
    $id = $request->getParameter('user_ref');
    $this->form = new WidgetRightsForm(null,array('user_ref' => $id,'collection_ref' => $request->getParameter('collection_ref'))) ;
    $this->user = Doctrine::getTable("Users")->findUser($id) ;    
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('widget_rights')) ;
      if($this->form->isValid())
      {
        $this->form->save();
        return $this->renderText('ok') ;
      }
    }    
  }
}
