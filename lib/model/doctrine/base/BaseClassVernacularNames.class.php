<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseClassVernacularNames extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('class_vernacular_names');
        $this->hasColumn('id', 'integer', null, array('type' => 'integer', 'primary' => true));
        $this->hasColumn('table_name', 'string', null, array('type' => 'string', 'notnull' => true));
        $this->hasColumn('record_id', 'integer', null, array('type' => 'integer', 'notnull' => true));
        $this->hasColumn('community', 'string', null, array('type' => 'string', 'notnull' => true));
    }

}