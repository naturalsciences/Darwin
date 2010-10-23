<?php

/**
 * CollectionsRights form.
 *
 * @package    form
 * @subpackage CollectionsRights
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CollectionsRightsForm extends BaseCollectionsRightsForm
{
  public function configure()
  {
    unset($this['id'],
          $this['collection_ref']) ;
    $user_id=isset($option) ? $this->options['user_id'] : $this->getObject()->getUserRef() ;
    $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden();
    if($user_id == 0 ) $this->widgetSchema['user_ref']->setLabel('nobody') ;
    else $this->widgetSchema['user_ref']->setLabel(Doctrine::getTable('Users')->findUser($user_id)->getFormatedName()) ;
    $this->widgetSchema['db_user_type'] = new sfWidgetFormChoice(array(
      'choices' =>  Users::getTypes(array('screen' => 2,'db_user_type' => Users::ADMIN)),
    ));    
    $this->widgetSchema->setDefault('db_user_type',Users::REGISTERED_USER) ;
    $this->validatorSchema['db_user_type'] = new sfValidatorPass();
    $this->validatorSchema['user_ref'] = new sfValidatorPass();
    $this->mergePostValidator(new CollectionsRightsValidatorSchema());     
  }
}
