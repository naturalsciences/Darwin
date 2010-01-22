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
    $languages = VernacularNamesTable::getDistinctLanguages(),
    $this->useFields(array('id', 'name', 'country_language_full_text'));
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->validatorSchema['name']->setOption('required', false);
    $this->widgetSchema['country_language_full_text'] = new sfWidgetFormChoice(array('choices'  => $languages,
                                                                                    )
                                                                              );
    $this->validatorSchema['country_language_full_text'] = new sfValidatorChoice(array(
        'choices' => VernacularNamesTable::getDistinctLanguages(),
        'required' => false,
        ));
    $this->mergePostValidator(new ClassVernacularNamesValidatorSchema());
  }
}