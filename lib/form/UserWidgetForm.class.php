<?php

/**
 * Base project form.
 * 
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be> 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class UserWidgetForm extends sfFormDoctrine
{
  public function configure()
  {
     $subForm = new sfForm();
     $this->embedForm('MyPreferences',$subForm);
	foreach ($this->options['collection'] as $index=>$record)
     {
      $form = new MyPreferencesForm($record,array('level' => $this->options['level']));
      $this->embeddedForms['MyPreferences']->embedForm($index, $form);
      //Re-embedding the container
      $this->embedForm('MyPreferences', $this->embeddedForms['MyPreferences']);
     }
  $this->widgetSchema->setNameFormat('user_widget[%s]');
  }
  
  public function getModelName()
  {
      return 'MyPreferences' ; // modelName a little nasty but it's for the good cause
  }
  
  protected function doSave($con = null)
  {
	    if (null === $con)
	    {
	      $con = $this->getConnection();
	    }
	
	    $this->updateObject();
	    //we have copied the same DoSave as sfFormDoctrine without the Save(), because we don't want an 'insert' in the DB
	
	    // embedded forms
	    $this->saveEmbeddedForms($con);
  }
}
