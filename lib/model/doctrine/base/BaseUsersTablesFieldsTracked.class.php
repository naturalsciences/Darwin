<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseUsersTablesFieldsTracked extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('users_tables_fields_tracked');
        $this->hasColumn('table_name', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('field_name', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('user_ref', 'integer', null, array('type' => 'integer', 'notnull' => true));
    }

    public function setUp()
    {
        $this->hasOne('Users', array('local' => 'user_ref',
                                     'foreign' => 'id'));
    }
}