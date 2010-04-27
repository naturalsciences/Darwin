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
    unset($this['path'],
          $this['code_auto_increment'],
          $this['code_prefix'],
          $this['code_prefix_separator'],
          $this['code_suffix'],
          $this['code_suffix_separator'],
          $this['code_part_code_auto_copy']
         );
    $this->widgetSchema['code'] = new sfWidgetFormInputText();
    $this->widgetSchema['code']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema['name']->setAttributes(array('class'=>'medium_size'));
    $this->widgetSchema['institution_ref'] = new widgetFormButtonRef(array(
       'model' => 'Institutions',
       'link_url' => 'institution/choose',
       'method' => 'getFamilyName',
       'box_title' => $this->getI18N()->__('Choose Institution'),
     ));
    $this->widgetSchema['institution_ref']->setLabel('Institution');

    $this->widgetSchema['main_manager_ref'] = new widgetFormButtonRef(array(
       'model' => 'Users',
       'link_url' => 'user/choose',
       'method' => 'getFormatedName',
       'box_title' => $this->getI18N()->__('Choose Manager'),
     ));
    $this->widgetSchema['main_manager_ref']->setLabel('Main manager');

    $this->widgetSchema['parent_ref'] = new sfWidgetFormChoice(array(
      'choices' =>  array(),
    ));
    $this->widgetSchema['parent_ref']->setLabel('Parent collection');


    $this->validatorSchema['collection_type'] = new sfValidatorChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'), 'required' => true));

   if(! $this->getObject()->isNew())
      $this->widgetSchema['parent_ref']->setOption('choices', Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($this->getObject()->getInstitutionRef()) );
   elseif(isset($this->options['new_with_error']))
      $this->widgetSchema['parent_ref']->setOption('choices', Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($this->options['institution']));   	

     $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'checkSelfAttached')))
     );
  }

  public function checkSelfAttached($validator, $values)
  {
    if(! empty($values['id']) )
    {
      if($values['parent_ref'] == $values['id'])
      {
	$error = new sfValidatorError($validator, "A collection can't be attached to itself");
        throw new sfValidatorErrorSchema($validator, array('parent_ref' => $error));
      }
    }
    return $values;
  }
  
}
