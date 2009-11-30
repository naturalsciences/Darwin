<?php

/**
 * comment actions.
 *
 * @package    darwin
 * @subpackage comment
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class commentActions extends sfActions
{
  public function executeDelete(sfWebRequest $request)
  {
    $r = Doctrine::getTable('Comments')->find($request->getParameter('id'));
    $this->forward404Unless($r,'No such comment');
    try{
      $r->delete();
    }
    catch(Exception $e)
    {
      return $this->renderText($e->getMessage());
    }
    return $this->renderText('ok');
  }

  public function executeComment(sfWebRequest $request)
  {
    if($request->hasParameter('cid'))
      $this->comment =  Doctrine::getTable('Comments')->find($request->getParameter('cid'));
    else
    {
     $this->comment = new Comments();
     $this->comment->setRecordId($request->getParameter('id'));
     $this->comment->setReferencedRelation($request->getParameter('table'));
    }
     
    $this->form = new CommentsForm($this->comment);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('comments'));
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
}
