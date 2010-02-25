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
  
  protected function loadWidgets()
  {
    $this->widgets = Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->getWidgets($this->widgetCategegory);
    if(! $this->widgets) $this->widgets=array();
  }
}
