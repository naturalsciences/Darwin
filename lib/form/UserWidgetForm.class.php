<?php

/**
 * Base project form.
 * 
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be> 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class UserWidgetForm extends sfForm
{
  public function configure()
  {
     $subForm = new sfForm();
     foreach ($this->options['collection'] as $index=>$record)
     {
      $form = new MyPreferencesForm($record,array('level' => $this->options['level']));
      $subForm->embedForm($index, $form);

     }
     $this->embedForm('MyPreferences',$subForm);
     $this->widgetSchema->setNameFormat('user_widget[%s]');
  }

  public function save()
  {
    $values = $this->getValues();
    
    foreach($this->embeddedForms['MyPreferences']->getEmbeddedForms() as $key => $prefs)
    {
      $prefs->updateObject($values['MyPreferences'][$key]);
      $prefs->getObject()->save();
    }
  }
}
