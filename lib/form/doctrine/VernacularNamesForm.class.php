<?php

/**
 * VernacularNames form.
 *
 * @package    form
 * @subpackage VernacularNames
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class VernacularNamesForm extends BaseVernacularNamesForm
{
  public function configure()
  {
    $this->useFields(array('id', 'name'));
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['name']->setAttributes(array('class'=>'xlarge_size'));
    $this->validatorSchema['name'] = new sfValidatorString(array('required' => false));
    $this->mergePostValidator(new VernacularnamesValidatorSchema());

  }
}