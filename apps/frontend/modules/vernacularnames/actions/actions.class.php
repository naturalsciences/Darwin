<?php

/**
 * vernacularnames actions.
 *
 * @package    darwin
 * @subpackage vernacularnames
 * @author     DB team <collections@naturalsciences.be>
 */
class vernacularnamesActions extends sfActions
{
  public function executeAdd(sfWebRequest $request)
  {
    $this->vernacularnames = null;
    if($request->hasParameter('rid'))
    {
      $this->vernacularnames = Doctrine::getTable('ClassVernacularNames')->findExcept($request->getParameter('rid'));
    }

    if(! $this->vernacularnames)
    {
     $this->vernacularnames = new ClassVernacularNames();
     $this->vernacularnames->setRecordId($request->getParameter('id'));
     $this->vernacularnames->setReferencedRelation($request->getParameter('table'));
    }
    $this->form = new ClassVernacularNamesForm($this->vernacularnames);
    
    if($request->isMethod('post'))
    {
	$this->form->bind($request->getParameter('class_vernacular_names'));
	if($this->form->isValid())
	{
	  try{
	    $this->form->save();
	    $this->form->getObject()->refreshRelated();
	    $this->form = new ClassVernacularNamesForm($this->form->getObject()); //Ugly refresh
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
  
  public function executeAddValue(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $vern = null;

    if($request->hasParameter('id') && $request->getParameter('id'))
      $vern = Doctrine::getTable('ClassVernacularNames')->findExcept($request->getParameter('id') );

    $form = new ClassVernacularNamesForm($vern);
    $form->addValue($number);
    return $this->renderPartial('vernacular_names_values',array('form' => $form['newVal'][$number]));
  }

}
