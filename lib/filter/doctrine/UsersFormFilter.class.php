<?php

/**
 * Users filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UsersFormFilter extends BaseUsersFormFilter
{
  public function configure()
  {
    $this->useFields(array('family_name'));
    $this->widgetSchema['family_name'] = new sfWidgetFormFilterInput(array('template' => '%input%'));

  }

  public function addFamilyNameColumnQuery($query, $field, $val)
  {
    return $this->addNamingColumnQuery($query, 'users', 'formated_name_ts', $val['text']);
  }
}
