<?php

/**
 * Collections form.
 *
 * @package    form
 * @subpackage Collections
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SubCollectionsRightsForm extends BaseCollectionsRightsForm
{
  public function configure()
  {
    unset($this['id']) ;
    $is_new = $this->is_new_right($this->options) ;
    $name = Doctrine::getTable('Collections')->findCollection($this->options['collection_ref'])->getName() ;
    $this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['user_ref'] = new sfValidatorinteger(array('required' => false)) ;
    $this->widgetSchema['check_right'] = new sfWidgetFormInputCheckbox(array(),array('checked'=> $is_new,'old_right' => $is_new));
    $this->validatorSchema['check_right'] = new sfValidatorBoolean();
//    $this->widgetSchema['check_right']->setAttribute('checked', ) ;
    $this->widgetSchema['collection_ref'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['collection_ref']->setLabel($name);
    $this->validatorSchema['collection_ref'] = new sfValidatorinteger(array('required' => false)) ;
    $this->mergePostValidator(new CollectionsRightsValidatorSchema());     
  }

  public function is_new_right($tab)
  {
	foreach($tab['old_rights'] as $right)
		if($tab['collection_ref'] == $right->getCollectionRef()) return true ;
	return false ;
  }  
}
