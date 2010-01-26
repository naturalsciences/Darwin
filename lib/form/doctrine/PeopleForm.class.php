<?php

/**
 * People form.
 *
 * @package    form
 * @subpackage People
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class PeopleForm extends BasePeopleForm
{
  public function configure()
  {
    unset($this['is_physical'], $this['formated_name_indexed'], $this['formated_name_ts'], $this['sub_type'],$this['formated_name']);
    
    $this->widgetSchema['additional_names'] = new sfWidgetFormInput();
  }
}
/**
     title: { type: string }
        family_name: { type: string, notnull: true}
        given_name: { type: string}
        additional_names: { type: string}
        birth_date_mask: { type: integer, notnull: true, default:0}
        birth_date: { type: string, notnull: true, default: '0001-01-01'}
        gender: { type: enum, values: ['M','F'] }
        db_people_type:  { type: integer, notnull: true, default:1}
        end_date_mask:  { type: integer, notnull: true, default:0}
        end_date: { type: string, notnull: true, default: '0001-01-01'}
*/