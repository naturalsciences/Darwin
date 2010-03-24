<?php

/**
 * widgets actions.
 *
 * @package    darwin
 * @subpackage widgets
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class widgetsActions extends DarwinActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
 */
  public function executeAddWidget(sfWebRequest $request)
  {
    $this->forward404unless($request->getParameter('widget',false));
    //mark widget as visible
    Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->changeWidgetStatus($request->getParameter('category')."_widget", $request->getParameter('widget'), 'visible');

    $title = Doctrine::getTable('MyPreferences')->getWidgetTitle($this->getUser()->getAttribute('db_user_id'),
                                                                 $request->getParameter('widget'),
                                                                 $request->getParameter('category')."_widget");
    if($title)
    {
      $title = $title[0]['title'];
    }

    return $this->renderPartial('widgets/wlayout',array(
            'widget' => $request->getParameter('widget'),
            'is_opened' => true,
            'category' => $this->getComponentFromCategory($request->getParameter('category')),
            'title' => $title,
	    'options' => array(
		'eid' =>  $request->getParameter('eid',null),
		'table' => $this->getTableFromCategory($request->getParameter('category')),
	      ),
        ));
  }

  public function executeChangeStatus(sfWebRequest $request)
  {
    Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->changeWidgetStatus($request->getParameter('category')."_widget", $request->getParameter('widget'), $request->getParameter('status'));
    return $this->renderText("ok");
  }

  public function executeChangeOrder(sfWebRequest $request)
  {
    $col1 = explode(',', $request->getParameter('col1'));
    $col2 = explode(',', $request->getParameter('col2'));
    Doctrine::getTable('MyPreferences')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->changeOrder($request->getParameter('category')."_widget", $col1, $col2);
    return $this->renderText(var_export($col1,true).var_export($col2,true));
  }

  static protected function getComponentFromCategory($category)
  {
    $cat_array = explode('_',$category);
    return $cat_array[0].'widget';
  }

  static protected function getTableFromCategory($category)
  {
    $cat_array = explode('_',$category);
    if(count($cat_array) == 2)
      return $cat_array[1];
    return null;
  }

  public function executeReloadContent(sfWebRequest $request)
  {
    return $this->renderComponent(
	$this->getComponentFromCategory($request->getParameter('category')),
	$request->getParameter('widget'),
	array(
	    'eid' =>  $request->getParameter('eid',null),
	    'table' => $this->getTableFromCategory($request->getParameter('category')),
	)
    );
  }

}