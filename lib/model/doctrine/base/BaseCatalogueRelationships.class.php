<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseCatalogueRelationships extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('catalogue_relationships');
        $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true));
        $this->hasColumn('table_name', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('record_id_1', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('record_id_2', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('relationship_type', 'integer', null, array('type' => 'integer', 'notnull' => true, 'default' => 'is synonym of'));
    }

}