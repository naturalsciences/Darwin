<?php
class LoanOverviewForm extends sfForm
{

  public function configure()
  {
    $subForm = new sfForm();
    if(isset($this->options['no_load']))
      $items = array();
    else
      $items = Doctrine::getTable('LoanItems')->findForLoan($this->options['loan']->getId());
    foreach ($items as $index => $childObject)
    {
      $form = new LoanItemsForm($childObject);
      $subForm->embedForm($index, $form);
     // $subForm->getWidgetSchema()->setLabel($index, (string) $childObject);
    }
    $this->embedForm('LoanItems', $subForm);

    $subForm2 = new sfForm();
    $this->embedForm('newLoanItems', $subForm2);

    $this->widgetSchema->setNameFormat('loan_overview[%s]');

  }

  
  public function addItem($num)
  {
    $form = new LoanItemsForm();
    $this->embeddedForms['newLoanItems']->embedForm($num, $form);
    //Re-embedding the container
    $this->embedForm('newLoanItems', $this->embeddedForms['newLoanItems']);
  }


  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['newLoanItems']))
    {
      foreach($taintedValues['newLoanItems'] as $key=>$newVal)
      {
        $taintedValues['newLoanItems'][$key]['loan_ref'] = $this->options['loan']->getId();
        if (!isset($this['newLoanItems'][$key]))
        {
          $this->addItem($key);
        }
      }
    }

    if(isset($taintedValues['LoanItems']))
    {
      foreach($taintedValues['LoanItems'] as $key=>$newVal)
      {
        $taintedValues['LoanItems'][$key]['loan_ref'] = $this->options['loan']->getId();
      }
    }
    parent::bind($taintedValues, $taintedFiles);
  }


  public function save()
  {
    $value = $this->getValues();
    foreach($this->embeddedForms['newLoanItems']->getEmbeddedForms() as $name => $form)
    {
      // @TODO: FIND BETTER deterministic key
      if (!isset($value['newLoanItems'][$name]['details'])/* || $value['newLoanItems'][$name]['details']=='' */)
      {
        unset($this->embeddedForms['newLoanItems'][$name]);
      }
      else
      {
        $form->updateObject($value['newLoanItems'][$name]);
        $form->getObject()->save();
      }
    }

    foreach($this->embeddedForms['LoanItems']->getEmbeddedForms() as $name => $form)
    {
      // @TODO: FIND BETTER deterministic key
      if (!isset($value['LoanItems'][$name]['details']) /*|| $value['LoanItems'][$name]['details']==''*/ )
      {
        $form->getObject()->delete();
        unset($this->embeddedForms['LoanItems'][$name]);
      }
      else
      {
        $form->updateObject($value['LoanItems'][$name]);
        $form->getObject()->save();
      }
    }
    
  }

  public function getStylesheets()
  {
    return array('/css/ui.datepicker.css' => 'all');
  }
}
