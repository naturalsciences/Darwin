<?php
class DarwinActions extends sfActions
{
  protected function setCommonValues($moduleName, $defaultOrderByField, sfWebRequest $request)
  {
    // Define all properties that will be either used by the data query or by the pager
    // They take their values from the request. If not present, a default value is defined
    $this->pagerSlidingSize = intval(sfConfig::get('app_pagerSlidingSize'));
    $this->currentPage = ($request->getParameter('page', '') == '')? 1: $request->getParameter('page');
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));
    $this->orderBy = ($request->getParameter('orderby', '') == '')?$defaultOrderByField:$request->getParameter('orderby');
    $this->orderDir = ($request->getParameter('orderdir', '') == '' || $request->getParameter('orderdir') == 'asc') ? 'asc' : 'desc';

    $this->s_url = $moduleName.'/search'.'?is_choose='.$this->is_choose;
    $this->o_url = '&orderby='.$this->orderBy.'&orderdir='.$this->orderDir;
  }

  protected function setDefaultPaggingLayout(PagerLayoutWithArrows $pagerLayout)
  {
    $pagerLayout->setTemplate('<li><a href="{%url}">{%page}</a></li>');
    $pagerLayout->setSelectedTemplate('<li>{%page}</li>');
    $pagerLayout->setSeparatorTemplate('<span class="pager_separator">::</span>');
  }
  
  protected function setLevelAndCaller(sfWebRequest $request)
  {
    $this->level = (!$request->hasParameter('level'))?'':$request->getParameter('level');
    $this->caller_id = (!$request->hasParameter('caller_id'))?'':$request->getParameter('caller_id');
  }

  protected function setPeopleRole(sfWebRequest $request)
  {
    $this->only_role = (!$request->hasParameter('only_role'))?'0':$request->getParameter('only_role');
  }

  protected function loadWidgets($id = null,$collection = null)
  {
    $this->__set('widgetCategory',$this->widgetCategory);
    if($id == null) $id = $this->getUser()->getId();
    $this->widgets = Doctrine::getTable('MyWidgets')
      ->setUserRef($this->getUser()->getId())
      ->setDbUserType($this->getUser()->getDbUserType())
      ->getWidgets($this->widgetCategory, $collection);
    if(! $this->widgets) $this->widgets=array();   
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  /**
   * Forwards the current request to the secure action.
   *
   * Copied from sfBasicSecurityFilter
   *
   * @see lib/vendor/symfony/lib/filter/sfBasicSecurityFilter.class.php
   * @throws sfStopException
   */
  public function forwardToSecureAction()
  {
    sfContext::getInstance()->getController()->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
    $this->getResponse()->setStatusCode(403);
    throw new sfStopException();
  }
  
  protected function getRecordIfDuplicate($id , $obj, $is_spec = false)
  {
    if ($id)
    {
      $check = $obj->getTable()->findExcept($id);
      if(!$check) return $obj ;
      if($is_spec)
      {
        $check->SpecimensMethods->count() ;
        $check->SpecimensTools->count() ;
      }
      $record = $check->toArray(true);
      unset($record['id']) ;
      $obj->fromArray($record,true) ;
      switch(get_class($obj))
      {
       case 'Expeditions' : 
        $obj->setExpeditionFromDate(new FuzzyDateTime($check->getExpeditionFromDate(),$check->getExpeditionFromDateMask()) );
        $obj->setExpeditionToDate(new FuzzyDateTime($check->getExpeditionToDate(),$check->getExpeditionToDateMask()) );
        break ; 
       case 'People' :            
        $obj->setBirthDate(new FuzzyDateTime($check->getBirthDate(),$check->getBirthDateMask()) );
        $obj->setEndDate(new FuzzyDateTime($check->getEndDate(),$check->getEndDateMask()) );
        $obj->setActivityDateFrom(new FuzzyDateTime($check->getActivityDateFrom(),$check->getActivityDateFromMask()) );
        $obj->setActivityDateTo(new FuzzyDateTime($check->getActivityDateTo(),$check->getActivityDateToMask()) );
        break ;
       case 'Gtu' :
        $obj->setGtuFromDate(new FuzzyDateTime($check->getGtuFromDate(),$check->getGtuFromDateMask()) );
        $obj->setGtuToDate(new FuzzyDateTime($check->getGtuToDate(),$check->getGtuToDateMask()) );
        break ;
       case 'Igs' :
        $obj->setIgDate(new FuzzyDateTime($check->getIgDate(),$check->getIgDateMask()) );
        break ;
       case 'Specimens' :
        $obj->setAcquisitionDate(new FuzzyDateTime($check->getAcquisitionDate(),$check->getAcquisitionDateMask()) );
        break ;
       default: break ;
      }
    }
    return $obj ;
  }

  /*Function sending an email to the specified user to confirm he's been well registered*/
  protected function sendConfirmationMail($userParams)
  {
    $message = $this->getMailer()->compose();
    $message->setFrom(array(sfConfig::get('app_mailer_sender') => 'DaRWIN 2 team'));
    if(is_array($userParams))
    {
      if (isset($userParams['mail']) && isset($userParams['name']) && isset($userParams['physical']))
      {
        if(!empty($userParams['mail']))
        {
          $message->setTo($userParams['mail']);
          $message->setSubject($this->getI18N()->__('DaRWIN 2  registration'));
          $invitation = $this->getI18N()->__('Dear').' ';
          if($userParams['physical'])
          {
            if (empty($userParams['title']))
            {
              $invitation .= $userParams['name'];
            }
            else
            {
              $invitation .= $userParams['title'].' '.$userParams['name'];
            }
          }
          else
          {
            $invitation .= $this->getI18N()->__('member of').' '.$userParams['name'];
          }
          $invitation .= ',';
          $line_2 = $this->getI18N()->__('Thank you for having registered on DaRWIN 2.');
          $line_3 = $this->getI18N()->__('You can now log you in and enjoy enhanced services to our collections.');
          $line_4 = '';
          $line_5 = '';
          $line_6 = '';
          if(!empty($userParams['username']) && !empty($userParams['password']))
          {
            $line_4 = $this->getI18N()->__('For your recall, here are your user name and password:');
            $line_5 = $this->getI18N()->__('User name: ').$userParams['username'];
            $line_6 = $this->getI18N()->__('Password: ').$userParams['password'];
          }
          $line_7 = $this->getI18N()->__('To log you in, you can visit us on http://').$_SERVER['SERVER_NAME'].' .';
          $line_8 = $this->getI18N()->__('DaRWIN 2 team');
          $body = sprintf(<<<EOF
%1\$s

%2\$s
%3\$s

%4\$s
%5\$s
%6\$s

%7\$s

%8\$s
EOF
          ,$invitation,$line_2,$line_3,$line_4,$line_5,$line_6,$line_7,$line_8);
          $message->setBody($body,'text/plain');
          $this->getMailer()->send($message);
        }
      }
    }
  }
}