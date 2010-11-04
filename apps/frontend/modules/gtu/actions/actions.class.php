<?php

/**
 * Gtu actions.
 *
 * @package    darwin
 * @subpackage GTU
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gtuActions extends DarwinActions
{
  protected $widgetCategory = 'catalogue_gtu_widget';

  public function executeChoose(sfWebRequest $request)
  {
    $this->form = new GtuFormFilter();
    $this->form->addValue(0);
  }

  public function executeIndex(sfWebRequest $request)
  {
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();  
    $this->form = new GtuFormFilter();
    $this->form->addValue(0);
  }

 public function executeSearch(sfWebRequest $request)
  {
//     $this->forward404Unless($request->isMethod('post'));

    $this->setCommonValues('gtu', 'code', $request);

    $this->form = new GtuFormFilter();
    $this->is_choose = ($request->getParameter('is_choose', '') == '')?0:intval($request->getParameter('is_choose'));

    if($request->getParameter('gtu_filters','') !== '')
    {
      $this->form->bind($request->getParameter('gtu_filters'));

      if ($this->form->isValid())
      {
        $query = $this->form->getQuery()->orderBy($this->orderBy .' '.$this->orderDir);
        if($request->getParameter('format') == 'xml') $query->andWhere('latitude is not null');
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
        
        if($request->getParameter('format') == 'xml')
        {
          $this->setLayout(false);
          $this->getResponse()->setContentType('application/xml');
          $this->setTemplate('georss');
          return;
        }
      }
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();    
    $gtu = new Gtu() ;
    $duplic = $request->getParameter('duplicate_id','0');
    $gtu = $this->getRecordIfDuplicate($duplic, $gtu);
    // if there is no duplicate $gtu is an empty array
    $this->form = new GtuForm($gtu);
    if ($duplic)
    {
      $Tag = Doctrine::getTable('TagGroups')->fetchTag($duplic) ;
      if(count($Tag))
      {
        foreach ($Tag[$duplic] as $key=>$val)
        {
           $tag = new TagGroups() ;
           $tag = $this->getRecordIfDuplicate($val->getId(), $tag);
           $this->form->addValue($key, $val->getGroupName(), $tag);

        }
      }
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GtuForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();      
    $this->forward404Unless($gtu = Doctrine::getTable('Gtu')->findExcept($request->getParameter('id')), sprintf('Object gtu does not exist (%s).', $request->getParameter('id')));
    $this->no_right_col = Doctrine::getTable('Gtu')->testNoRightsCollections('gtu_ref',$request->getParameter('id'), $this->getUser()->getId());

    $this->form = new GtuForm($gtu);
    $this->loadWidgets();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gtu = Doctrine::getTable('Gtu')->findExcept($request->getParameter('id')), sprintf('Object gtu does not exist (%s).', $request->getParameter('id')));
    $this->form = new GtuForm($gtu);

    $this->processForm($request, $this->form);
    $this->loadWidgets();
    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if($this->getUser()->getDbUserType() < Users::ENCODER) $this->forwardToSecureAction();    
    $request->checkCSRFProtection();

    $this->forward404Unless($gtu = Doctrine::getTable('Gtu')->findExcept($request->getParameter('id')), sprintf('Object gtu does not exist (%s).', $request->getParameter('id')));
    try
    {
        $gtu->delete();
        $this->redirect('gtu/index');
    }
    catch(Doctrine_Exception $ne)
    {
      $e = new DarwinPgErrorParser($ne);
      $error = new sfValidatorError(new savedValidator(),$e->getMessage());
      $this->form = new GtuForm($gtu);
      $this->form->getErrorSchema()->addError($error); 
      $this->loadWidgets();
      $this->setTemplate('edit');
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      try
      {
        $item = $form->save();
        $this->redirect('gtu/edit?id='.$item->getId());
      }
      catch(Doctrine_Exception $ne)
      {
        $e = new DarwinPgErrorParser($ne);
        $error = new sfValidatorError(new savedValidator(),$e->getMessage());
        $form->getErrorSchema()->addError($error); 
      }
    }
  }

  public function executePurposeTag(sfWebRequest $request)
  {
    $this->tags = Doctrine::getTable('TagGroups')->getPropositions($request->getParameter('value'), $request->getParameter('group_name'), $request->getParameter('sub_group_name'));
  }

  public function executeAddGroup(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));
    $gtu = null;

    if($request->hasParameter('id') && $request->getParameter('id'))
      $gtu = Doctrine::getTable('Gtu')->findExcept($request->getParameter('id') );

    $form = new GtuForm($gtu);
    $form->addValue($number, $request->getParameter('group'));
    return $this->renderPartial('taggroups',array('form' => $form['newVal'][$number]));
  }
  
  public function executeAndSearch(sfWebRequest $request)
  {
    $number = intval($request->getParameter('num'));

    $form = new GtuFormFilter();
    $form->addValue($number);
    return $this->renderPartial('andSearch',array('form' => $form['Tags'][$number], 'row_line' => $number));
  }

  /**
  * Return tags for a GTU without the country part
  */
  public function executeCompleteTag(sfWebRequest $request)
  {
    $gtu = false;
    if($request->hasParameter('id') && $request->getParameter('id'))
    {
      $spec = Doctrine::getTable('Specimens')->fetchOneWithRights($request->getParameter('id'), $this->getUser());
      if($spec->getHasEncodingRights() || $this->getUser()->isAtLeast(Users::ADMIN))
        $gtu = Doctrine::getTable('Gtu')->findExcept($spec->getGtuRef() );
      else
        $this->forwardToSecureAction();
    }

    $this->forward404Unless($gtu);

    $str = '<ul  class="search_tags">';
    foreach($gtu->TagGroups as $group)
    {
      $str .= '<li><label>'.$group->getSubGroupName().'<span class="gtu_group"> - '.TagGroups::getGroup($group->getGroupName()).'</span></label><ul class="name_tags">';
      $tags = explode(";",$group->getTagValue());
      foreach($tags as $value)
        if (strlen($value))
          $str .=  '<li>' . trim($value).'</li>';
      $str .= '</ul><div class="clear" />';
    }
    $str .= '</ul><div class="clear" />';
    return $this->renderText($str); 
  }
}
