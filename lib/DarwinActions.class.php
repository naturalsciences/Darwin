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

  protected function loadWidgets($id = null)
  {
    $this->__set('widgetCategory',$this->widgetCategory);
    if($id == null) $id = $this->getUser()->getId();
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets($this->widgetCategory);
    if(! $this->widgets) $this->widgets=array();
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
}
