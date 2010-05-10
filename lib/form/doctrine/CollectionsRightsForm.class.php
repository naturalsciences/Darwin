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
    $this->widgetSchema['user_ref']->setLabel(Doctrine::getTable('Users')->findUser($user_id)->getFormatedName()) ;
    $this->validatorSchema['user_ref'] = new sfValidatorinteger(array('required' => false)) ;
    $this->mergePostValidator(new CollectionsRightsValidatorSchema());     
  }
}
