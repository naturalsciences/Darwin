<?php

class BaseMassActionForm extends sfFormSymfony
{
  protected static function getI18N()
  {
    return sfContext::getInstance()->getI18N();
  }

  public static function getActionsSources()
  {
    return array('' => '', 'specimens'=>'specimens','individuals'=>'individuals','parts'=>'parts');
  }
  
  public static function getPossibleActions()
  {
    return array(
      'specimens' => array(
        'collection_ref' => self::getI18N()->__('Collection'),
      ),
      'individuals' => array(
      ),
      'parts' => array(
      ),
    );
  }

  public function setSubForm($form_name)
  {
    $subForm = new $form_name();
    $this->embedForm('MassActionForm',$subForm);
  }
  public function configure()
  {
    $action_sources = self::getActionsSources();
    $action_possibles = self::getPossibleActions();

    sfWidgetFormSchema::setDefaultFormFormatterName('list');
    $this->widgetSchema->setNameFormat('mass_action[%s]');

    $this->widgetSchema['source'] = new sfWidgetFormChoice(array( 
     'choices' => $action_sources
    ));

    $this->widgetSchema['action'] = new sfWidgetFormChoice(array( 
     'choices' =>  array()
    ));

    $this->widgetSchema['item_list'] = new sfWidgetFormChoice(array( 'choices' => array() ));
    $this->validatorSchema['item_list'] = new sfValidatorDoctrineChoice(array(
      'multiple' => true,
      'model' => 'Specimens',
      'min' => 0,
    ));

    $subForm = new sfForm();
    $this->embedForm('MassActionForm',$subForm);
  }
}
