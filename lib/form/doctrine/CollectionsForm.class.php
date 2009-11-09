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
//     $this->widgetSchema['collection_type'] = new sfWidgetFormInput();
    $this->widgetSchema['code'] = new sfWidgetFormInput();
    $this->widgetSchema['name'] = new sfWidgetFormInput();
/* @TODO remove this line when people search will be ready */
    $this->widgetSchema['institution_ref'] = new sfWidgetFormInput();
/* @TODO end of line to remove */
  }
}