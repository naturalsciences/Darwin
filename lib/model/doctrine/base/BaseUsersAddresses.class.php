<?php

/**
 * BaseUsersAddresses
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $person_user_ref
 * @property string $tag
 * @property string $entry
 * @property string $organization_unit
 * @property string $person_user_role
 * @property string $po_box
 * @property string $extended_address
 * @property string $locality
 * @property string $region
 * @property string $zip_code
 * @property string $country
 * @property string $address_parts_ts
 * @property Users $Users
 * 
 * @method integer        getId()                Returns the current record's "id" value
 * @method integer        getPersonUserRef()     Returns the current record's "person_user_ref" value
 * @method string         getTag()               Returns the current record's "tag" value
 * @method string         getEntry()             Returns the current record's "entry" value
 * @method string         getOrganizationUnit()  Returns the current record's "organization_unit" value
 * @method string         getPersonUserRole()    Returns the current record's "person_user_role" value
 * @method string         getPoBox()             Returns the current record's "po_box" value
 * @method string         getExtendedAddress()   Returns the current record's "extended_address" value
 * @method string         getLocality()          Returns the current record's "locality" value
 * @method string         getRegion()            Returns the current record's "region" value
 * @method string         getZipCode()           Returns the current record's "zip_code" value
 * @method string         getCountry()           Returns the current record's "country" value
 * @method string         getAddressPartsTs()    Returns the current record's "address_parts_ts" value
 * @method Users          getUsers()             Returns the current record's "Users" value
 * @method UsersAddresses setId()                Sets the current record's "id" value
 * @method UsersAddresses setPersonUserRef()     Sets the current record's "person_user_ref" value
 * @method UsersAddresses setTag()               Sets the current record's "tag" value
 * @method UsersAddresses setEntry()             Sets the current record's "entry" value
 * @method UsersAddresses setOrganizationUnit()  Sets the current record's "organization_unit" value
 * @method UsersAddresses setPersonUserRole()    Sets the current record's "person_user_role" value
 * @method UsersAddresses setPoBox()             Sets the current record's "po_box" value
 * @method UsersAddresses setExtendedAddress()   Sets the current record's "extended_address" value
 * @method UsersAddresses setLocality()          Sets the current record's "locality" value
 * @method UsersAddresses setRegion()            Sets the current record's "region" value
 * @method UsersAddresses setZipCode()           Sets the current record's "zip_code" value
 * @method UsersAddresses setCountry()           Sets the current record's "country" value
 * @method UsersAddresses setAddressPartsTs()    Sets the current record's "address_parts_ts" value
 * @method UsersAddresses setUsers()             Sets the current record's "Users" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUsersAddresses extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('users_addresses');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('person_user_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('tag', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             ));
        $this->hasColumn('entry', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('organization_unit', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('person_user_role', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('po_box', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('extended_address', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('locality', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('region', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('zip_code', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('country', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('address_parts_ts', 'string', null, array(
             'type' => 'string',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Users', array(
             'local' => 'person_user_ref',
             'foreign' => 'id'));
    }
}