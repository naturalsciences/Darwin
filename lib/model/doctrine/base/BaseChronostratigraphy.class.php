<?php

/**
 * BaseChronostratigraphy
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property string $name_indexed
 * @property string $name_order_by
 * @property integer $level_ref
 * @property string $status
 * @property string $path
 * @property integer $parent_ref
 * @property decimal $lower_bound
 * @property decimal $upper_bound
 * @property Chronostratigraphy $Parent
 * @property CatalogueLevels $Level
 * @property Doctrine_Collection $Chronostratigraphy
 * @property Doctrine_Collection $Specimens
 * @property Doctrine_Collection $SpecimenSearch
 * 
 * @method integer             getId()                 Returns the current record's "id" value
 * @method string              getName()               Returns the current record's "name" value
 * @method string              getNameIndexed()        Returns the current record's "name_indexed" value
 * @method string              getNameOrderBy()        Returns the current record's "name_order_by" value
 * @method integer             getLevelRef()           Returns the current record's "level_ref" value
 * @method string              getStatus()             Returns the current record's "status" value
 * @method string              getPath()               Returns the current record's "path" value
 * @method integer             getParentRef()          Returns the current record's "parent_ref" value
 * @method decimal             getLowerBound()         Returns the current record's "lower_bound" value
 * @method decimal             getUpperBound()         Returns the current record's "upper_bound" value
 * @method Chronostratigraphy  getParent()             Returns the current record's "Parent" value
 * @method CatalogueLevels     getLevel()              Returns the current record's "Level" value
 * @method Doctrine_Collection getChronostratigraphy() Returns the current record's "Chronostratigraphy" collection
 * @method Doctrine_Collection getSpecimens()          Returns the current record's "Specimens" collection
 * @method Doctrine_Collection getSpecimenSearch()     Returns the current record's "SpecimenSearch" collection
 * @method Chronostratigraphy  setId()                 Sets the current record's "id" value
 * @method Chronostratigraphy  setName()               Sets the current record's "name" value
 * @method Chronostratigraphy  setNameIndexed()        Sets the current record's "name_indexed" value
 * @method Chronostratigraphy  setNameOrderBy()        Sets the current record's "name_order_by" value
 * @method Chronostratigraphy  setLevelRef()           Sets the current record's "level_ref" value
 * @method Chronostratigraphy  setStatus()             Sets the current record's "status" value
 * @method Chronostratigraphy  setPath()               Sets the current record's "path" value
 * @method Chronostratigraphy  setParentRef()          Sets the current record's "parent_ref" value
 * @method Chronostratigraphy  setLowerBound()         Sets the current record's "lower_bound" value
 * @method Chronostratigraphy  setUpperBound()         Sets the current record's "upper_bound" value
 * @method Chronostratigraphy  setParent()             Sets the current record's "Parent" value
 * @method Chronostratigraphy  setLevel()              Sets the current record's "Level" value
 * @method Chronostratigraphy  setChronostratigraphy() Sets the current record's "Chronostratigraphy" collection
 * @method Chronostratigraphy  setSpecimens()          Sets the current record's "Specimens" collection
 * @method Chronostratigraphy  setSpecimenSearch()     Sets the current record's "SpecimenSearch" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseChronostratigraphy extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('chronostratigraphy');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('name_indexed', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('name_order_by', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('level_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('status', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'valid',
             ));
        $this->hasColumn('path', 'string', null, array(
             'type' => 'string',
             'notnull' => false,
             'default' => '/',
             ));
        $this->hasColumn('parent_ref', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             ));
        $this->hasColumn('lower_bound', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => 3,
             ));
        $this->hasColumn('upper_bound', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'scale' => 3,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Chronostratigraphy as Parent', array(
             'local' => 'parent_ref',
             'foreign' => 'id'));

        $this->hasOne('CatalogueLevels as Level', array(
             'local' => 'level_ref',
             'foreign' => 'id'));

        $this->hasMany('Chronostratigraphy', array(
             'local' => 'id',
             'foreign' => 'parent_ref'));

        $this->hasMany('Specimens', array(
             'local' => 'id',
             'foreign' => 'chrono_ref'));

        $this->hasMany('SpecimenSearch', array(
             'local' => 'id',
             'foreign' => 'chrono_ref'));
    }
}