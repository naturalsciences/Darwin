<?php

/**
 * Register form.
 *
 * @package    form
 * @subpackage Register
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RegisterForm extends BaseUsersForm
{
  public function configure()
  {
    $this->useFields(array('is_physical','sub_type','title','family_name','given_name')) ;
    $this->widgetSchema['is_physical'] = new sfWidgetFormInputCheckbox(array ('default' => 'true')); 
    $this->widgetSchema['sub_type'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                  'table_method' => 'getDistinctSubType',
                                                                  'method' => 'getSubType',
                                                                  'key_method' => 'getSubType',
                                                                  'add_empty' => true,
                                                                  'change_label' => 'Pick a sub type in the list',
                                                                  'add_label' => 'Add another sub type',
                                                                        )
                                                                  );
    $this->widgetSchema['title'] = new widgetFormSelectComplete(array('model' => 'Users',
                                                                      'table_method' => 'getDistinctTitle',
                                                                      'method' => 'getTitle',
                                                                      'key_method' => 'getTitle',
                                                                      'add_empty' => true,
                                                                      'change_label' => 'Pick a title in the list',
                                                                      'add_label' => 'Add another title',
                                                                        )
                                                                  );
    $this->widgetSchema['title']->setAttributes(array('class'=>'small_size'));

    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name']->setAttributes(array('class'=>'medium_size'));    
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name']->setAttributes(array('class'=>'medium_size'));

    /* Labels */
    $this->widgetSchema->setLabels(array('family_name'=>'Name','given_name'=>'First name', 'is_physical'=>'Physical person ?', 'sub_type'=>'Type'));

    /* Login infos as embedded form */
    $regLoginSubForm = new RegisterLoginInfosForm();
    $this->embedForm('RegisterLoginInfosForm',$regLoginSubForm);

    /* Comm means as embedded form */
    $regCommSubForm = new RegisterCommForm();
    $this->embedForm('RegisterCommForm',$regCommSubForm);

    /* Languages as embedded form */
    $regLangSubForm = new RegisterLanguagesForm();
    $this->embedForm('RegisterLanguagesForm',$regLangSubForm);

  }
}