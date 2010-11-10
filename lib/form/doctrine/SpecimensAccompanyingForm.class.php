<?php

/**
 * Specipmens Accompanying form.
 *
 * @package    form
 * @subpackage Specimens accompanying
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensAccompanyingForm extends BaseSpecimensAccompanyingForm
{
  public function configure()
  {
    $accompanying_types = array('biological'=>'Biological', 'mineral'=>'Mineral');
    $units = array(''=>'','%'=>'%');
    $this->widgetSchema['id'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['id'] = new sfValidatorInteger(array('required'=>false));
    $this->widgetSchema['specimen_ref'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['specimen_ref'] = new sfValidatorInteger(array('required'=>false));    
    $this->widgetSchema['accompanying_type'] = new sfWidgetFormChoice(array('choices'=>$accompanying_types));
    $this->validatorSchema['accompanying_type'] = new sfValidatorChoice(array('choices'=>array_keys($accompanying_types), 'required'=>true));
    $this->widgetSchema['unit'] = new sfWidgetFormChoice(array('choices'=>$units, 'default'=>'%'));
    $this->validatorSchema['unit'] = new sfValidatorChoice(array('choices'=>array_keys($units), 'empty_value'=>null));
    $this->widgetSchema['form'] = new widgetFormSelectComplete(array('model' => 'SpecimensAccompanying',
                                                                     'table_method' => 'getDistinctForms',
                                                                     'method' => 'getForm',
                                                                     'key_method' => 'getForm',
                                                                     'add_empty' => false,
                                                                     'change_label' => '',
                                                                     'add_label' => '',
                                                                    )
                                                              );
    $this->widgetSchema['taxon_ref'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'link_url' => 'taxonomy/choose',
       'method' => 'getNameWithFormat',
       'box_title' => $this->getI18N()->__('Choose Taxon'),
       'nullable' => false,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );
    $this->widgetSchema['mineral_ref'] = new widgetFormButtonRef(array(
       'model' => 'Mineralogy',
       'link_url' => 'mineralogy/choose',
       'method' => 'getNameWithFormat',
       'box_title' => $this->getI18N()->__('Choose Mineral'),
       'nullable' => false,
       'button_class'=>'',
     ),
      array('class'=>'inline',
           )
    );

    $this->widgetSchema->setLabels(array('accompanying_type' => 'Type' ,
                                         'form' => 'Form',
                                         'Quantity' => 'Quantity',
                                         'taxon_ref' => 'Unit:',
                                         'mineral_ref' => 'Unit:'
                                        )
                                  );

    $this->widgetSchema['quantity']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['form']->setAttributes(array('class'=>'small_size'));

    /*Specimens accompanying post-validation to empty null values*/
    $this->mergePostValidator(new SpecimensAccompanyingValidatorSchema());

  }
  
}
