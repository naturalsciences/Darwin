<?php

/**
 * Multimedia form base class.
 *
 * @package    form
 * @subpackage multimedia
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMultimediaForm extends BaseFormDoctrine
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
      'creation_date'                  => new sfWidgetFormDate(),
      'creation_date_mask'             => new sfWidgetFormInput(),
      'publication_date_from'          => new sfWidgetFormDate(),
      'publication_date_from_mask'     => new sfWidgetFormInput(),
      'publication_date_to'            => new sfWidgetFormDate(),
      'publication_date_to_mask'       => new sfWidgetFormInput(),
      'parent_ref'                     => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
      'path'                           => new sfWidgetFormTextarea(),
      'mime_type'                      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                             => new sfValidatorDoctrineChoice(array('model' => 'Multimedia', 'column' => 'id', 'required' => false)),
      'is_digital'                     => new sfValidatorBoolean(),
      'type'                           => new sfValidatorString(),
      'sub_type'                       => new sfValidatorString(array('required' => false)),
      'title'                          => new sfValidatorString(),
      'title_indexed'                  => new sfValidatorString(array('required' => false)),
      'subject'                        => new sfValidatorString(),
      'coverage'                       => new sfValidatorString(),
      'apercu_path'                    => new sfValidatorString(array('required' => false)),
      'copyright'                      => new sfValidatorString(array('required' => false)),
      'license'                        => new sfValidatorString(array('required' => false)),
      'uri'                            => new sfValidatorString(array('required' => false)),
      'descriptive_ts'                 => new sfValidatorString(array('required' => false)),
      'descriptive_language_full_text' => new sfValidatorString(array('required' => false)),
      'creation_date'                  => new sfValidatorDate(),
      'creation_date_mask'             => new sfValidatorInteger(),
      'publication_date_from'          => new sfValidatorDate(),
      'publication_date_from_mask'     => new sfValidatorInteger(),
      'publication_date_to'            => new sfValidatorDate(),
      'publication_date_to_mask'       => new sfValidatorInteger(),
      'parent_ref'                     => new sfValidatorDoctrineChoice(array('model' => 'Multimedia', 'required' => false)),
      'path'                           => new sfValidatorString(),
      'mime_type'                      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('multimedia[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Multimedia';
  }

}
