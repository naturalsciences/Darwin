<?php

/**
 * BaseVernacularNames
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $referenced_relation
 * @property integer $record_id
 * @property string $community
 * @property string $name
 * @property string $name_indexed
 * 
 * @method integer         getId()                  Returns the current record's "id" value
 * @method string          getReferencedRelation()  Returns the current record's "referenced_relation" value
 * @method integer         getRecordId()            Returns the current record's "record_id" value
 * @method string          getCommunity()           Returns the current record's "community" value
 * @method string          getName()                Returns the current record's "name" value
 * @method string          getNameIndexed()         Returns the current record's "name_indexed" value
 * @method VernacularNames setId()                  Sets the current record's "id" value
 * @method VernacularNames setReferencedRelation()  Sets the current record's "referenced_relation" value
 * @method VernacularNames setRecordId()            Sets the current record's "record_id" value
 * @method VernacularNames setCommunity()           Sets the current record's "community" value
 * @method VernacularNames setName()                Sets the current record's "name" value
 * @method VernacularNames setNameIndexed()         Sets the current record's "name_indexed" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseVernacularNames extends DarwinModel
{
    public function setTableDefinition()
    {
        $this->setTableName('vernacular_names');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('referenced_relation', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('record_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('community', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('name_indexed', 'string', null, array(
             'type' => 'string',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}
