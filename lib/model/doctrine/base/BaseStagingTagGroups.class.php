<?php

/**
 * BaseStagingTagGroups
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $staging_ref
 * @property string $group_name
 * @property string $sub_group_name
 * @property string $tag_value
 * @property Staging $Staging
 * 
 * @method integer          getId()             Returns the current record's "id" value
 * @method integer          getStagingRef()     Returns the current record's "staging_ref" value
 * @method string           getGroupName()      Returns the current record's "group_name" value
 * @method string           getSubGroupName()   Returns the current record's "sub_group_name" value
 * @method string           getTagValue()       Returns the current record's "tag_value" value
 * @method Staging          getStaging()        Returns the current record's "Staging" value
 * @method StagingTagGroups setId()             Sets the current record's "id" value
 * @method StagingTagGroups setStagingRef()     Sets the current record's "staging_ref" value
 * @method StagingTagGroups setGroupName()      Sets the current record's "group_name" value
 * @method StagingTagGroups setSubGroupName()   Sets the current record's "sub_group_name" value
 * @method StagingTagGroups setTagValue()       Sets the current record's "tag_value" value
 * @method StagingTagGroups setStaging()        Sets the current record's "Staging" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStagingTagGroups extends DarwinModel
{
    public function setTableDefinition()
    {
        $this->setTableName('staging_tag_groups');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('staging_ref', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('group_name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('sub_group_name', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
        $this->hasColumn('tag_value', 'string', null, array(
             'type' => 'string',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Staging', array(
             'local' => 'staging_ref',
             'foreign' => 'id'));
    }
}