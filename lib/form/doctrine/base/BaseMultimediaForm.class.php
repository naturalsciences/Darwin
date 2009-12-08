<?php

/**
 * Multimedia form base class.
 *
 * @method Multimedia getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMultimediaForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                             => new sfWidgetFormInputHidden(),
      'is_digital'                     => new sfWidgetFormInputCheckbox(),
      'type'                           => new sfWidgetFormTextarea(),
      'sub_type'                       => new sfWidgetFormTextarea(),
      'title'                          => new sfWidgetFormTextarea(),
      'title_indexed'                  => new sfWidgetFormTextarea(),
      'subject'                        => new sfWidgetFormTextarea(),
      'coverage'                       => new sfWidgetFormTextarea(),
      'apercu_path'                    => new sfWidgetFormTextarea(),
      'copyright'                      => new sfWidgetFormTextarea(),
      'license'                        => new sfWidgetFormTextarea(),
      'uri'                            => new sfWidgetFormTextarea(),
      'descriptive_ts'                 => new sfWidgetFormTextarea(),
      'descriptive_language_full_text' => new sfWidgetFormTextarea(),
      'creation_date'                  => new sfWidgetFormTextarea(),
      'creation_date_mask'             => new sfWidgetFormInputText(),
      'publication_date_from'          => new sfWidgetFormTextarea(),
      'publication_date_from_mask'     => new sfWidgetFormInputText(),
      'publication_date_to'            => new sfWidgetFormTextarea(),
      'publication_date_to_mask'       => new sfWidgetFormInputText(),
      'parent_ref'                     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'path'                           => new sfWidgetFormTextarea(),
      'mime_type'                      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'is_digital'                     => new sfValidatorBoolean(),
      'type'                           => new sfValidatorString(array('required' => false)),
      'sub_type'                       => new sfValidatorString(array('required' => false)),
      'title'                          => new sfValidatorString(),
      'title_indexed'                  => new sfValidatorString(array('required' => false)),
      'subject'                        => new sfValidatorString(array('required' => false)),
      'coverage'                       => new sfValidatorString(array('required' => false)),
      'apercu_path'                    => new sfValidatorString(array('required' => false)),
      'copyright'                      => new sfValidatorString(array('required' => false)),
      'license'                        => new sfValidatorString(array('required' => false)),
      'uri'                            => new sfValidatorString(array('required' => false)),
      'descriptive_ts'                 => new sfValidatorString(array('required' => false)),
      'descriptive_language_full_text' => new sfValidatorString(array('required' => false)),
      'creation_date'                  => new sfValidatorString(array('required' => false)),
      'creation_date_mask'             => new sfValidatorInteger(array('required' => false)),
      'publication_date_from'          => new sfValidatorString(array('required' => false)),
      'publication_date_from_mask'     => new sfValidatorInteger(array('required' => false)),
      'publication_date_to'            => new sfValidatorString(array('required' => false)),
      'publication_date_to_mask'       => new sfValidatorInteger(array('required' => false)),
      'parent_ref'                     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'path'                           => new sfValidatorString(array('required' => false)),
      'mime_type'                      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Multimedia';
  }

}
