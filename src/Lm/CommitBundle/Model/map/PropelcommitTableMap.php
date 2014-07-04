<?php

namespace Lm\CommitBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'propelcommit' table.
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
class PropelcommitTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Lm.CommitBundle.Model.map.PropelcommitTableMap';

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
        $this->setName('propelcommit');
        $this->setPhpName('Propelcommit');
        $this->setClassname('Lm\\CommitBundle\\Model\\Propelcommit');
        $this->setPackage('src.Lm.CommitBundle.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'VARCHAR', true, 255, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 250, null);
        $this->addColumn('link', 'Link', 'VARCHAR', true, 255, null);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', false, null, null);
        $this->addColumn('update_date', 'UpdateDate', 'TIMESTAMP', false, null, null);
        $this->addForeignKey('author_id', 'AuthorId', 'INTEGER', 'author', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Author', 'Lm\\CommitBundle\\Model\\Author', RelationMap::MANY_TO_ONE, array('author_id' => 'id', ), null, null);
        $this->addRelation('File', 'Lm\\CommitBundle\\Model\\File', RelationMap::ONE_TO_MANY, array('id' => 'commit_id', ), null, null, 'Files');
    } // buildRelations()

} // PropelcommitTableMap
