<?php

/**
 * BaseSpecimenParts
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $path
 * @property integer $parent_ref
 * @property integer $specimen_individual_ref
 * @property string $specimen_part
 * @property boolean $complete
 * @property integer $institution_ref
 * @property string $building
 * @property string $floor
 * @property string $room
 * @property string $row
 * @property string $shelf
 * @property string $container
 * @property string $sub_container
 * @property string $container_type
 * @property string $sub_container_type
 * @property string $container_storage
 * @property string $sub_container_storage
 * @property boolean $surnumerary
 * @property string $specimen_status
 * @property integer $specimen_part_count_min
 * @property integer $specimen_part_count_max
 * @property SpecimenIndividuals $Individual
 * @property SpecimenParts $Parent
 * @property Doctrine_Collection $SpecimenParts
 * @property Doctrine_Collection $LoanItems
 * 
 * @method integer             getId()                      Returns the current record's "id" value
 * @method string              getPath()                    Returns the current record's "path" value
 * @method integer             getParentRef()               Returns the current record's "parent_ref" value
 * @method integer             getSpecimenIndividualRef()   Returns the current record's "specimen_individual_ref" value
 * @method string              getSpecimenPart()            Returns the current record's "specimen_part" value
 * @method boolean             getComplete()                Returns the current record's "complete" value
 * @method integer             getInstitutionRef()          Returns the current record's "institution_ref" value
 * @method string              getBuilding()                Returns the current record's "building" value
 * @method string              getFloor()                   Returns the current record's "floor" value
 * @method string              getRoom()                    Returns the current record's "room" value
 * @method string              getRow()                     Returns the current record's "row" value
 * @method string              getShelf()                   Returns the current record's "shelf" value
 * @method string              getContainer()               Returns the current record's "container" value
 * @method string              getSubContainer()            Returns the current record's "sub_container" value
 * @method string              getContainerType()           Returns the current record's "container_type" value
 * @method string              getSubContainerType()        Returns the current record's "sub_container_type" value
 * @method string              getContainerStorage()        Returns the current record's "container_storage" value
 * @method string              getSubContainerStorage()     Returns the current record's "sub_container_storage" value
 * @method boolean             getSurnumerary()             Returns the current record's "surnumerary" value
 * @method string              getSpecimenStatus()          Returns the current record's "specimen_status" value
 * @method integer             getSpecimenPartCountMin()    Returns the current record's "specimen_part_count_min" value
 * @method integer             getSpecimenPartCountMax()    Returns the current record's "specimen_part_count_max" value
 * @method SpecimenIndividuals getIndividual()              Returns the current record's "Individual" value
 * @method SpecimenParts       getParent()                  Returns the current record's "Parent" value
 * @method Doctrine_Collection getSpecimenParts()           Returns the current record's "SpecimenParts" collection
 * @method Doctrine_Collection getLoanItems()               Returns the current record's "LoanItems" collection
 * @method SpecimenParts       setId()                      Sets the current record's "id" value
 * @method SpecimenParts       setPath()                    Sets the current record's "path" value
 * @method SpecimenParts       setParentRef()               Sets the current record's "parent_ref" value
 * @method SpecimenParts       setSpecimenIndividualRef()   Sets the current record's "specimen_individual_ref" value
 * @method SpecimenParts       setSpecimenPart()            Sets the current record's "specimen_part" value
 * @method SpecimenParts       setComplete()                Sets the current record's "complete" value
 * @method SpecimenParts       setInstitutionRef()          Sets the current record's "institution_ref" value
 * @method SpecimenParts       setBuilding()                Sets the current record's "building" value
 * @method SpecimenParts       setFloor()                   Sets the current record's "floor" value
 * @method SpecimenParts       setRoom()                    Sets the current record's "room" value
 * @method SpecimenParts       setRow()                     Sets the current record's "row" value
 * @method SpecimenParts       setShelf()                   Sets the current record's "shelf" value
 * @method SpecimenParts       setContainer()               Sets the current record's "container" value
 * @method SpecimenParts       setSubContainer()            Sets the current record's "sub_container" value
 * @method SpecimenParts       setContainerType()           Sets the current record's "container_type" value
 * @method SpecimenParts       setSubContainerType()        Sets the current record's "sub_container_type" value
 * @method SpecimenParts       setContainerStorage()        Sets the current record's "container_storage" value
 * @method SpecimenParts       setSubContainerStorage()     Sets the current record's "sub_container_storage" value
 * @method SpecimenParts       setSurnumerary()             Sets the current record's "surnumerary" value
 * @method SpecimenParts       setSpecimenStatus()          Sets the current record's "specimen_status" value
 * @method SpecimenParts       setSpecimenPartCountMin()    Sets the current record's "specimen_part_count_min" value
 * @method SpecimenParts       setSpecimenPartCountMax()    Sets the current record's "specimen_part_count_max" value
 * @method SpecimenParts       setIndividual()              Sets the current record's "Individual" value
 * @method SpecimenParts       setParent()                  Sets the current record's "Parent" value
 * @method SpecimenParts       setSpecimenParts()           Sets the current record's "SpecimenParts" collection
 * @method SpecimenParts       setLoanItems()               Sets the current record's "LoanItems" collection
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSpecimenParts extends DarwinModel
{
    public function setTableDefinition()
    {
        $this->setTableName('specimen_parts');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('path', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '/',
             ));
        $this->hasColumn('parent_ref', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('specimen_individual_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('specimen_part', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'specimen',
             ));
        $this->hasColumn('complete', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => true,
             ));
        $this->hasColumn('institution_ref', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('building', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('floor', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('room', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('row', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('shelf', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('container', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('sub_container', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('container_type', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'container',
             ));
        $this->hasColumn('sub_container_type', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'container',
             ));
        $this->hasColumn('container_storage', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'dry',
             ));
        $this->hasColumn('sub_container_storage', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'dry',
             ));
        $this->hasColumn('surnumerary', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('specimen_status', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => 'good state',
             ));
        $this->hasColumn('specimen_part_count_min', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             ));
        $this->hasColumn('specimen_part_count_max', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('SpecimenIndividuals as Individual', array(
             'local' => 'specimen_individual_ref',
             'foreign' => 'id'));

        $this->hasOne('SpecimenParts as Parent', array(
             'local' => 'parent_ref',
             'foreign' => 'id'));

        $this->hasMany('SpecimenParts', array(
             'local' => 'id',
             'foreign' => 'parent_ref'));

        $this->hasMany('LoanItems', array(
             'local' => 'id',
             'foreign' => 'part_ref'));
    }
}