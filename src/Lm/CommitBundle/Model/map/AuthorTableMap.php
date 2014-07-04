<?php

namespace Lm\CommitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'author' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Lm.CommitBundle.Model.map
 */
class AuthorTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Lm.CommitBundle.Model.map.AuthorTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('author');
        $this->setPhpName('Author');
        $this->setClassname('Lm\\CommitBundle\\Model\\Author');
        $this->setPackage('src.Lm.CommitBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 250, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('uri', 'Uri', 'VARCHAR', false, 250, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Propelcommit', 'Lm\\CommitBundle\\Model\\Propelcommit', RelationMap::ONE_TO_MANY, array('id' => 'author_id', ), null, null, 'Propelcommits');
    } // buildRelations()

} // AuthorTableMap
