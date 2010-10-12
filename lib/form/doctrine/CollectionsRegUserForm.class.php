<?php

/**
 * CollectionsRegUser form.
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CollectionsRegUserForm extends BaseCollectionsRegUserForm
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
