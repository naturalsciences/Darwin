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
    $this->useFields(array('id','is_physical','sub_type','title','family_name','given_name')) ;
    $choices = array($this->getI18N()->__('Moral person'), $this->getI18N()->__('Physical person'));
    $this->widgetSchema['is_physical'] = new sfWidgetFormChoice(array ('choices' => $choices));
    $this->setDefault('is_physical', 1);
    $this->widgetSchema['is_physical']->setAttributes(array('class'=>'required_field'));
    $this->widgetSchema['sub_type'] = new sfWidgetFormDoctrineChoice(array('model' => 'Users',
                                                                           'table_method' => 'getDistinctSubType',
                                                                           'method' => 'getSubType',
                                                                           'key_method' => 'getSubType',
                                                                           'add_empty'=>true,
                                                                          )
                                                                    );
    $this->widgetSchema['title'] = new sfWidgetFormDoctrineChoice(array('model' => 'Users',
                                                                        'table_method' => 'getDistinctTitle',
                                                                        'method' => 'getTitle',
                                                                        'key_method' => 'getTitle',
                                                                        'add_empty'=>true,
                                                                       )
                                                                 );
    $this->widgetSchema['given_name'] = new sfWidgetFormInput();
    $this->widgetSchema['given_name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['family_name'] = new sfWidgetFormInput();
    $this->widgetSchema['family_name']->setAttributes(array('class'=>'medium_size required_field'));

    $this->widgetSchema['terms_of_use'] = new sfWidgetFormInputCheckbox();
    $this->widgetSchema['terms_of_use']->setAttributes(array('class'=>'required_field'));
    $this->setDefault('terms_of_use',false);

    /* Validators */
    
    // Write a post validator for is_physical here
    $this->validatorSchema['terms_of_use'] = new sfValidatorBoolean(array('required'=>true),
                                                                    array('required'=> 'You cannot register without accepting DaRWIN 2  term of use',
                                                                          'invalid' => 'The value provided for term of use is invalid'
                                                                         )
                                                                   );
    $this->validatorSchema['is_physical'] = new sfValidatorBoolean(array('required'=>true),
                                                                   array('required'=> 'Status is required',
                                                                         'invalid' => 'The value provided for status is invalid'
                                                                        )
                                                                  );
    $this->validatorSchema['family_name'] = new sfValidatorString(array('trim'=>true, 
                                                                        'required'=>true
                                                                       ),
                                                                   array('required'=> 'Name is required',
                                                                         'invalid' => 'The value provided for name is invalid'
                                                                        )
                                                                 );

    /* Labels */
    $this->widgetSchema->setLabels(array('family_name'=>'Name',
                                         'given_name'=>'First name', 
                                         'is_physical'=>'Status', 
                                         'sub_type'=>'Type'
                                        )
                                  );

    /* Login infos as embedded form */

      $login = new UsersLoginInfos();
      $login->User = $this->getObject();
      $form = new RegisterLoginInfosForm($login);
      $this->embedForm('RegisterLoginInfosForm',$form);
//       $this->embeddedForms['RegisterLoginInfos']->embedForm($form);
//       //Re-embedding the container
//       $this->embedForm('RegisterLoginInfos', $this->embeddedForms['RegisterLoginInfos']);

//     $regLoginSubForm = new RegisterLoginInfosForm();
//      $this->embeddedForms['RegisterLoginInfosForm']->embedForm(1, $form);
//      $this->embedForm('RegisterLoginInfosForm', $this->embeddedForms['RegisterLoginInfosForm']);

    /* Comm means as embedded form */
    $regCommSubForm = new RegisterCommForm();
    $this->embedForm('RegisterCommForm',$regCommSubForm);

    /* Languages as embedded form */
    $regLangSubForm = new RegisterLanguagesForm();
    $this->embedForm('RegisterLanguagesForm',$regLangSubForm);

  }
}