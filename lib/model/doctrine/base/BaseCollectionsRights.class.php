<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCollectionsRights extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('collections_rights');
        $this->hasColumn('collection_ref', 'integer', null, array('type' => 'integer', 'notnull' => true, 'default' => 0));
        $this->hasColumn('user_ref', 'integer', null, array('type' => 'integer', 'notnull' => true, 'default' => 0));
        $this->hasColumn('rights', 'integer', null, array('type' => 'integer', 'notnull' => true, 'default' => '1'));
    }

    public function setUp()
    {
        $this->hasOne('Collections', array('local' => 'collection_ref',
                                           'foreign' => 'id'));

        $this->hasOne('Users', array('local' => 'user_ref',
                                     'foreign' => 'id'));
    }
}