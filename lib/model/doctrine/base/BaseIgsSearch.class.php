<?php

/**
 * BaseIgsSearch
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $ig_num
 * @property string $ig_num_indexed
 * @property integer $ig_date_mask
 * @property integer $ig_ref
 * @property string $expedition_name
 * @property string $expedition_name_ts
 * @property string $expedition_name_indexed
 * @property integer $expedition_ref
 * 
 * @method string    getIgNum()                   Returns the current record's "ig_num" value
 * @method string    getIgNumIndexed()            Returns the current record's "ig_num_indexed" value
 * @method integer   getIgDateMask()              Returns the current record's "ig_date_mask" value
 * @method integer   getIgRef()                   Returns the current record's "ig_ref" value
 * @method string    getExpeditionName()          Returns the current record's "expedition_name" value
 * @method string    getExpeditionNameTs()        Returns the current record's "expedition_name_ts" value
 * @method string    getExpeditionNameIndexed()   Returns the current record's "expedition_name_indexed" value
 * @method integer   getExpeditionRef()           Returns the current record's "expedition_ref" value
 * @method IgsSearch setIgNum()                   Sets the current record's "ig_num" value
 * @method IgsSearch setIgNumIndexed()            Sets the current record's "ig_num_indexed" value
 * @method IgsSearch setIgDateMask()              Sets the current record's "ig_date_mask" value
 * @method IgsSearch setIgRef()                   Sets the current record's "ig_ref" value
 * @method IgsSearch setExpeditionName()          Sets the current record's "expedition_name" value
 * @method IgsSearch setExpeditionNameTs()        Sets the current record's "expedition_name_ts" value
 * @method IgsSearch setExpeditionNameIndexed()   Sets the current record's "expedition_name_indexed" value
 * @method IgsSearch setExpeditionRef()           Sets the current record's "expedition_ref" value
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseIgsSearch extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('specimens_flat');
        $this->hasColumn('ig_num', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('ig_num_indexed', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('ig_date_mask', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ig_ref', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('expedition_name', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('expedition_name_ts', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('expedition_name_indexed', 'string', null, array(
             'type' => 'string',
             ));
        $this->hasColumn('expedition_ref', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}