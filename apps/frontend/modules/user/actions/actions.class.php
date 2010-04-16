<?php

/**
 * user actions.
 *
 * @package    darwin
 * @subpackage user
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class userActions extends DarwinActions
{
  protected $widgetCategory = 'users_widget';
  public function executeNew(sfWebRequest $request)
  {
	$this->forward404Unless(Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getDbUserType() > 2 , sprintf('You are not allowed to access to this page'));
    $this->form = new UsersForm(null, array("db_user_type" => $this->getUser()->getAttribute('db_user_type')));
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Doctrine::getTable('Users')->find($request->getparameter('id')), sprintf('User does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless(Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getDbUserType() > 2 , sprintf('You are not allowed to access to this page'));
    $is_physical = Doctrine::getTable('Users')->find($request->getparameter('id'))->getIsPhysical() ;
    $this->form = new UsersForm($this->user,array("db_user_type" => $this->getUser()->getAttribute('db_user_type'), "is_physical" => $is_physical));
    $old_db_user_type = $this->user->getDbUserType() ;
    $this->loadWidgets();
    if($request->isMethod('post'))
    {
	 $array = $request->getParameter('users');
      $this->form->bind($array);
	 $new_db_user_type = $array['db_user_type'] ;
	 $user_id = $request->getParameter('id') ;
      if($this->form->isValid())
      {
	  $this->form->updateObject();

	  // Let's save the object
	  $this->form->getObject()->save();
	  if ($old_db_user_type != $new_db_user_type)
	  {
	  	if ($old_db_user_type > $new_db_user_type)
	  	{
	  	  // widget to delete
		  switch ($old_db_user_type)
		  {
		  	case 8: if ($new_db_user_type > 2) break ; // for now an admin and a CM have the same widget
		  	        if ($new_db_user_type > 1) Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Collection manager',false) ; 
		  	        if ($new_db_user_type == 1) Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Encoder',false) ; 
		  	        break ;
		  	case 4: if ($new_db_user_type > 1) Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Collection manager',false) ; 
		  	        if ($new_db_user_type == 1) Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Encoder',false) ; 
		  	        break ;
		  	case 2: Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Encoder',false) ; 
		  	        break ;
		  	default: break ;
		  }
		}
		else
		{
		  $widget = new Users() ;
		   // widget to add
		  switch ($old_db_user_type)
		  {
		  	case 1: Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Encoder',true) ; 
		  	        if ($new_db_user_type > 2) Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Collection manager',true) ; 
		  	        break ;
		  	case 2: Doctrine::getTable('MyPreferences')->setUserRef($user_id)->setWidgets('Collection manager',true) ; 
		  	        break ;
		  	default: break ;
		  }
		
		}
	  }
	  if($this->form->getValue('password') != '')
	  {
	    $login_infos = $this->user->UsersLoginInfos;
	    if( count($login_infos) != 1)
	    {
	      print 'Ouups'; exit; // @TODO: replace this by a proper way
	    }
            $login_infos[0]->setPassword(sha1(sfConfig::get('app_salt').$this->form->getValue('password')));
	    $login_infos[0]->save();
	  }
	  return $this->redirect('user/edit?id='.$user_id."&save=1");
      }
    }
  }
  /**
    * Action executed when calling the expeditions from an other screen
    * @param sfWebRequest $request Request coming from browser
    */ 
  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new UsersFormFilter(null, array("db_user_type" => $this->getUser()->getAttribute('db_user_type'), "screen" => 1));
    $this->setLayout(false);
  }

  public function executeIndex(sfWebRequest $request)
  {
	$this->forward404Unless(Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getDbUserType() > 2 , sprintf('You are not allowed to access to this page'));
    $this->form = new UsersFormFilter(null, array("db_user_type" => $this->getUser()->getAttribute('db_user_type'), "screen" => 2));
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod('post')) ;
    $screen = $request->getAttribute('users_filters[screen]') ;
    $this->setCommonValues('user', 'family_name', $request);
    $this->form = new UsersFormFilter(null, array("db_user_type" => $this->getUser()->getAttribute('db_user_type'), "screen" => $screen));
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));

    if($request->getParameter('users_filters','') !== '')
    {
      $this->form->bind($request->getParameter('users_filters'));

      if ($this->form->isValid())
      {
        $query = $this->form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        // if this is not an admin, make sure no admin and collection manager are visible in the search form
        $this->pagerLayout = new PagerLayoutWithArrows(
	  new Doctrine_Pager(
	    $query,
	    $this->currentPage,
	    $this->form->getValue('rec_per_page')
	  ),
	  new Doctrine_Pager_Range_Sliding(
	    array('chunk' => $this->pagerSlidingSize)
	    ),
	  $this->getController()->genUrl($this->s_url.$this->o_url).'/page/{%page_number}'
	);
        // Sets the Pager Layout templates
        $this->setDefaultPaggingLayout($this->pagerLayout);
        // If pager not yet executed, this means the query has to be executed for data loading
        if (! $this->pagerLayout->getPager()->getExecuted())
           $this->items = $this->pagerLayout->execute();
      }
    }

  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($user = Doctrine::getTable('users')->findUser($request->getParameter('id')), sprintf('User does not exist (%s).', $request->getParameter('id')));
    try{
        $user->delete();
	$this->redirect('user/index');
    }
    catch(Doctrine_Exception $ne)
    {
	$e = new DarwinPgErrorParser($ne);
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	$this->form = new UserForm($user);
	$this->form->getErrorSchema()->addError($error); 
	$this->loadWidgets();
	$this->setTemplate('edit');
    }
  }
  
  public function executeWidget(sfWebRequest $request)
  {
   $id = $request->getparameter('id') ;
   if (!$id) $id = Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getId() ;
   else { 
	$this->forward404Unless(Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id'))->getDbUserType() > 2 , sprintf('You are not allowed to access to this page'));
 	$this->forward404Unless(Doctrine::getTable('Users')->find($id), sprintf('User does not exist (%s).', $id));
   }
   $widget = Doctrine::getTable('MyPreferences')->setUserRef($id)->getWidgetsList($this->getUser()->getAttribute('db_user_type')) ;
   $this->form = new UserWidgetForm(null,array('collection' => $widget, 'level' =>$this->getUser()->getAttribute('db_user_type')));
   $this->level = $this->getUser()->getAttribute('db_user_type') ;
   $this->loadWidgets();  
   if($request->isMethod('post'))
   {
     $this->form->bind($request->getParameter('user_widget')) ;
     if($this->form->isValid())
     {
     	$this->form->save();
     	return $this->redirect('@homepage');
     }
   }
   $this->form_pref = array();
   foreach($this->form['MyPreferences'] as $keyword)
   {	
      $type = $keyword['category']->getValue();
      if(!isset($this->form_pref[$type]))
        $this->form_pref[$type] = array();
      $this->form_pref[$type][] = $keyword;
   }
   $this->user = Doctrine::getTable("Users")->findUser($id) ;
  }
  
  public function executeProfile(sfWebRequest $request)
  {
    $this->user =  Doctrine::getTable('Users')->find( $this->getUser()->getAttribute('db_user_id') );
    $this->login = Doctrine::getTable('UsersLoginInfos')->findOneByUserRef( $this->getUser()->getAttribute('db_user_id'));
    $this->forward404Unless($this->user);


    $this->loadWidgets();
    $old_people = $this->user->getPeopleId();

    $this->form = new ProfileForm($this->user,array('is_physical' => $this->user->getIsPhysical()));
    if($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('users'));
      if($this->form->isValid())
      {
	  $this->form->updateObject();
	    
	  // Let's save the object
	  $this->form->getObject()->save();

	  if($this->form->getValue('password') != '')
	  {
	    $login_infos = $this->user->UsersLoginInfos;
	    if( count($login_infos) != 1)
	    {
	      print 'Ouups'; exit; // @TODO: replace this by a proper way
	    }
            $login_infos[0]->setPassword(sha1(sfConfig::get('app_salt').$this->form->getValue('password')));
	    $login_infos[0]->save();
	  }
	  return $this->redirect('user/profile?save=1');
      }
    }
  }

  public function executeAddress(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->address =  Doctrine::getTable('UsersAddresses')->find($request->getParameter('id'));
    else
    {
     $this->address = new UsersAddresses();
     $this->address->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersAddressesForm($this->address);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_addresses'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	  }
	  catch(Exception $e)
	  {
	    return $this->renderText($e->getMessage());
	  }
	  return $this->renderText('ok');
	}
    }
  }


  public function executeGetTags(sfWebRequest $request)
  {
    $this->array_possible = Doctrine::getTable('UsersComm')->getTags($request->getParameter('type'));
  }

  public function executeComm(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->comm =  Doctrine::getTable('UsersComm')->find($request->getParameter('id'));
    else
    {
     $this->comm = new UsersComm();
     $this->comm->setPersonUserRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersCommForm($this->comm);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_comm'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	  }
	  catch(Exception $e)
	  {
	    return $this->renderText($e->getMessage());
	  }
	  return $this->renderText('ok');
	}
    }
  }
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new UsersForm(null, array("db_user_type" => $this->getUser()->getAttribute('db_user_type')));

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try{
	$user = $form->save();
	$widget = new Users() ;
	$widget->addUserWidgets($user->getId());
	Doctrine::getTable('MyPreferences')->setUserRef($user->getId())->setWidgets('Registered user',true) ; 
	if ($user->getDbUserType() > 1) Doctrine::getTable('MyPreferences')->setUserRef($user->getId())->setWidgets('Encoder',true) ; 
	if ($user->getDbUserType() > 2) Doctrine::getTable('MyPreferences')->setUserRef($user->getId())->setWidgets('Collection manager',true) ; 
	if ($user->getDbUserType() > 4) Doctrine::getTable('MyPreferences')->setUserRef($user->getId())->setWidgets('Administrator',true) ; 
	$this->redirect('user/edit?id='.$user->getId());
      }
      catch(Doctrine_Exception $ne)
      {
	$e = new DarwinPgErrorParser($ne);
	$error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error);
      }
    }
  }

  public function executeLang(sfWebRequest $request)
  {

    if($request->hasParameter('id'))
      $this->lang =  Doctrine::getTable('UsersLanguages')->find($request->getParameter('id'));
    else
    {
     $this->lang = new UsersLanguages();
     $this->lang->setUsersRef($request->getParameter('ref_id'));
    }
     
    $this->form = new UsersLanguagesForm($this->lang);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('users_languages'));
	if($this->form->isValid())
	{
	  try {
	    if($this->form->getValue('preferred_language') && ! $this->lang->getPreferredLanguage() )
	    {
	      Doctrine::getTable('UsersLanguages')->removeOldPreferredLang($this->getUser()->getAttribute('db_user_id'));
	    }
	    
	    $this->form->save();
	    if($this->form->getValue('preferred_language'))
	    {
	      $this->getUser()->setCulture($this->form->getValue('language_country'));
	    }
	    
	    return $this->renderText('ok');
	  }
	  catch(Doctrine_Exception $e)
	  {
	    $error = new sfValidatorError(new savedValidator(),$e->getMessage());
	    $this->form->getErrorSchema()->addError($error); 
	  }
	}
    }
  }
}
