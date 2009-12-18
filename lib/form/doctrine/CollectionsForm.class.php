<?php

/**
 * Collections form.
 *
 * @package    form
 * @subpackage Collections
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CollectionsForm extends BaseCollectionsForm
{
  public function configure()
  {
    unset(
        $this['path']
    );
    $this->widgetSchema['code'] = new sfWidgetFormInputText();
    $this->widgetSchema['name'] = new sfWidgetFormInputText();
/* @TODO remove this line when people search will be ready */
    $this->widgetSchema['institution_ref'] = new sfWidgetFormInputText();
/* @TODO end of line to remove */
    $this->validatorSchema['collection_type'] = new sfValidatorChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'), 'required' => true));
  }
}