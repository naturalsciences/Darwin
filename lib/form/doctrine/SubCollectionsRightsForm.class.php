<?php

/**
 * CollectionsRights form.
 *
 * @package    form
 * @subpackage CollectionsRights
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SubCollectionsRightsForm extends BaseCollectionsRightsForm
{
  public function configure()
  {
    unset($this['id'],
          $this['collection_ref'],
          $this['user_ref']) ;

    /*$this->widgetSchema['user_ref'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['user_ref'] = new sfValidatorInteger();*/
    $types = array(''=>'') + Users::getTypes(array('screen' => 2,'db_user_type' => Users::ADMIN)); //WHAAAT ?
    $this->widgetSchema['db_user_type'] = new sfWidgetFormChoice(array('choices' => $types));
    $this->widgetSchema['db_user_type']->setLabel($this->options['collection']->getName());
    $this->validatorSchema['db_user_type'] = new sfValidatorChoice(array('choices'=>array_keys($types)));
  }
  public function getCollection()
  {
    return $this->options['collection'];
  }
}
