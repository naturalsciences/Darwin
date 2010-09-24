<?php

/**
 * RegisterLoginInfos form.
 *
 * @package    form
 * @subpackage RegisterLoginInfos
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class RegisterLoginInfosForm extends UsersLoginInfosForm
{

  public function configure()
  {
    parent::configure();
    unset($this['login_type'], $this['id']);
    $this->widgetSchema->setLabels(array('new_password'=>'Password', 'user_name'=>'Login'));
    $this->widgetSchema['user_name']->setAttributes(array('class'=>'medium_small_size required_field'));
    $this->widgetSchema['new_password']->setAttributes(array('class'=>'medium_small_size required_field'));
    $this->widgetSchema['confirm_password']->setAttributes(array('class'=>'medium_small_size required_field'));
    $this->validatorSchema['user_name'] = new sfValidatorString(array('required' => true, 'min_length' => 4),
                                                                array('min_length' => '"%value%" must be at least %min_length% characters.',
                                                                      'required' => 'Login is required'
                                                                     )
                                                               ) ;
    $this->validatorSchema['new_password'] = new sfValidatorString(array('trim'=>true, 'required'=>true),
                                                                   array('required'=>'You need to provide a password')
                                                                  );
    $this->validatorSchema['confirm_password'] = new sfValidatorString(array('trim'=>true, 'required'=>true),
                                                                       array('required'=>'You need to confirm your password')
                                                                      );
    $this->validatorSchema['user_ref'] = new sfValidatorInteger(array('required'=>false));
  }

  public function checkPassword($validator, $values)
  {
    if($values['new_password'] != $values['confirm_password'])
    {
      $error = new sfValidatorError($validator, 'Password does not match' ) ;
      throw new sfvalidatorErrorSchema($validator, array('new_password' => $error));
    }
    return $values;
  } 
}