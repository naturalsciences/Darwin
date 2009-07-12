<?php

/**
 * board actions.
 *
 * @package    darwin
 * @subpackage board
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class boardActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
     $this->widgets = Doctrine::getTable('MyPreferences')->getBoardWidgets();
  }

  public function executeAddWidget(sfWebRequest $request)
  {
    $this->forward404unless($request->getParameter('widget',false));
    return $this->renderPartial('boardwidget/wlayout',array('widget' => $request->getParameter('widget')));
  }

  public function executeChangeStatus(sfWebRequest $request)
  {
    Doctrine::getTable('MyPreferences')
      ->changeWidgetStatus('board_widget', $request->getParameter('widget'), $request->getParameter('status'));
    return $this->renderText("ok");
  }
}
