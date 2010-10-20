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
  public function executeGetWidgetPosition(sfWebRequest $request)
  {
    $this->forward404unless($request->getParameter('widget',false));
    //get widget position and order by in json parseable structure
    $defaultResponse = array('col_num'=>'1', 'order_by'=>'0');
    $position = Doctrine::getTable('MyWidgets')->getWidgetPosition($this->getUser()->getAttribute('db_user_id'),
                                                                       $request->getParameter('widget'),
                                                                       $request->getParameter('category')."_widget");
    if($position)
    {
      return $this->renderText(json_encode($position[0]->toArray()));
    }
    return $this->renderText(json_encode($defaultResponse));
  }

  public function executeAddWidget(sfWebRequest $request)
  {
    $this->forward404unless($request->getParameter('widget',false));
    //mark widget as visible
    Doctrine::getTable('MyWidgets')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->changeWidgetStatus($request->getParameter('category')."_widget", $request->getParameter('widget'), 'visible');

    $title = Doctrine::getTable('MyWidgets')->getWidgetTitle($this->getUser()->getAttribute('db_user_id'),
                                                             $request->getParameter('widget'),
                                                             $request->getParameter('category')."_widget");
    if($title)
    {
      $title = $title[0]['title'];
    }
    
    $mandatory = Doctrine::getTable('MyWidgets')->getWidgetTitle($this->getUser()->getAttribute('db_user_id'),
                                                                 $request->getParameter('widget'),
                                                                 $request->getParameter('category')."_widget");
    if($mandatory)
    {
      $mandatory = $mandatory[0]['mandatory'];
    }
    
    return $this->renderPartial('widgets/wlayout',array(
            'widget' => $request->getParameter('widget'),
            'is_opened' => true,
            'is_mandatory' => $mandatory,
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
    Doctrine::getTable('MyWidgets')
      ->setUserRef($this->getUser()->getAttribute('db_user_id'))
      ->changeWidgetStatus($request->getParameter('category')."_widget", $request->getParameter('widget'), $request->getParameter('status'));
    return $this->renderText("ok");
  }

  public function executeChangeOrder(sfWebRequest $request)
  {
    $col1 = explode(',', $request->getParameter('col1'));
    $col2 = explode(',', $request->getParameter('col2'));
    Doctrine::getTable('MyWidgets')
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
	    'level' => $this->getUser()->getAttribute('db_user_type')
	)
    );
  }

}
