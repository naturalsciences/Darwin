<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCollectionMaintenance extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('collection_maintenance');
        $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true));
        $this->hasColumn('table_name', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('user_ref', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('category', 'string', null, array('type' => 'string', 'notnull' => true, 'default' => 'action'));
        $this->hasColumn('action_observation', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('description', 'string', null, array('type' => 'string'));
        $this->hasColumn('description_ts', 'string', null, array('type' => 'string'));
        $this->hasColumn('language_full_text', 'string', null, array('type' => 'string'));
        $this->hasColumn('modification_date_time', 'timestamp', null, array('type' => 'timestamp', 'notnull' => true));
    }

    public function setUp()
    {
        $this->hasOne('Users', array('local' => 'user_ref',
                                     'foreign' => 'id'));
    }
}