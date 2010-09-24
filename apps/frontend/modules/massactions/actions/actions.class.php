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
    $this->form = new BaseMassActionForm();
    if($request->isMethod('post') && $request->getParameter('mass_action','') != '')
    {
      $actions = $request->getParameter('mass_action',array());
      $this->form->bind($actions);
      if($this->form->isValid())
      {
        $this->form->doMassAction();
        $nb_item = count($this->form->getValue('item_list'));
        $this->redirect('massactions/status?nb_item='.$nb_item.'&'.http_build_query($this->form->getValues()));
      }

      $possibles_actions = BaseMassActionForm::getPossibleActions();
      $this->form->getWidget('field_action')->setOption('choices',array_merge(array(''=>''),$possibles_actions[$actions['source']]));

      $items_ids = $actions['item_list'];
      $this->items = Doctrine::getTable('SpecimenSearch')->getByMultipleIds($items_ids);

    }

  }

  public function executeStatus(sfWebRequest $request)
  {
    $this->nb_items = $request->getParameter('nb_item',0);
  }



  public function executeGetSubForm(sfWebRequest $request)
  {
    $this->source = $request->getParameter('source','specimen');
    $this->mAction = $request->getParameter('maction','');
    $this->form = new BaseMassActionForm();
    $this->form->addSubForm($this->mAction);
  }

  public function executeItems(sfWebRequest $request)
  {
    $source = $request->getParameter('source','specimen');
    $items_ids = $this->getUser()->getAllPinned($source);
    $this->items = Doctrine::getTable('SpecimenSearch')->getByMultipleIds($items_ids,$source);
  }
  
}
