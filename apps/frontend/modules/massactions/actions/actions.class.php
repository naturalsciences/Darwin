<?php

/**
 * massactions actions.
 *
 * @package    darwin
 * @subpackage massactions
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class massactionsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
//     $actions = $request->getParameter('mass_action',array());
//     if(!empty($actions) && isset($actions['action']))
//     {
//       if($actions['action'] == 'collection_ref')
//       {
//         $this->form = new MaCollectionRefForm();
//       }
//       else
//         $this->forward404();
//     }
//     else
//     {
     $this->form = new BaseMassActionForm();
//     }
  }

  public function executeGetSubForm(sfWebRequest $request)
  {
    $this->source = $request->getParameter('source','');
    $this->action = $request->getParameter('action','');
    
    $this->form = new BaseMassActionForm();
    $this->form->setSubForm('MaCollectionRefForm');
  }

  public function executeItems(sfWebRequest $request)
  {
    $items_ids = $this->getUser()->getAllPinned();
    if($request->getParameter('source','') =='specimens')
      $items_ids = array(1,2,3,4);
    $this->items = Doctrine::getTable('SpecimenSearch')->getByMultipleIds($items_ids);
  }
  
  public function executeGetActions(sfWebRequest $request)
  {
    $this->source = $request->getParameter('source','');
    $this->actions = BaseMassActionForm::getPossibleActions();
    $this->forward404unless( isset($this->actions[$this->source]));
  }
}
