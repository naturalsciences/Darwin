<?php

/**
 * board actions.
 *
 * @package    darwin
 * @subpackage board
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class boardActions extends DarwinActions
{
  protected $widgetCategory = 'board_widget';

  public function executeIndex(sfWebRequest $request)
  {
   //  $this->loadWidgets();
  }

  public function executeLang(sfWebRequest $request)
  {
    if(! in_array($request->getParameter('lang'), array('en','fr','nl')))
      $this->forward404();
    $this->getUser()->setCulture($request->getParameter('lang'));
    $referer = $this->getRequest()->getReferer();
    $this->redirect($referer ? $referer : '@homepage');
  }
}
