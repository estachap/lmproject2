<?php

namespace Lm\CommitBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Lm\CommitBundle\Model\File;
use Lm\CommitBundle\Model\FilePeer;
use Lm\CommitBundle\Model\FileQuery;
use Lm\CommitBundle\Model\Propelcommit;

/**
 * @method FileQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FileQuery orderByFilename($order = Criteria::ASC) Order by the filename column
 * @method FileQuery orderByCommitId($order = Criteria::ASC) Order by the commit_id column
 * @method FileQuery orderByCommitStatus($order = Criteria::ASC) Order by the commit_status column
 *
 * @method FileQuery groupById() Group by the id column
 * @method FileQuery groupByFilename() Group by the filename column
 * @method FileQuery groupByCommitId() Group by the commit_id column
 * @method FileQuery groupByCommitStatus() Group by the commit_status column
 *
 * @method FileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FileQuery leftJoinPropelcommit($relationAlias = null) Adds a LEFT JOIN clause to the query using the Propelcommit relation
 * @method FileQuery rightJoinPropelcommit($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Propelcommit relation
 * @method FileQuery innerJoinPropelcommit($relationAlias = null) Adds a INNER JOIN clause to the query using the Propelcommit relation
 *
 * @method File findOne(PropelPDO $con = null) Return the first File matching the query
 * @method File findOneOrCreate(PropelPDO $con = null) Return the first File matching the query, or a new File object populated from the query conditions when no match is found
 *
 * @method File findOneByFilename(string $filename) Return the first File filtered by the filename column
 * @method File findOneByCommitId(string $commit_id) Return the first File filtered by the commit_id column
 * @method File findOneByCommitStatus(string $commit_status) Return the first File filtered by the commit_status column
 *
 * @method array findById(int $id) Return File objects filtered by the id column
 * @method array findByFilename(string $filename) Return File objects filtered by the filename column
 * @method array findByCommitId(string $commit_id) Return File objects filtered by the commit_id column
 * @method array findByCommitStatus(string $commit_status) Return File objects filtered by the commit_status column
 */
abstract class BaseFileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFileQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Lm\\CommitBundle\\Model\\File';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FileQuery) {
            return $criteria;
        }
        $query = new FileQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   File|File[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FilePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 File A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 File A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `filename`, `commit_id`, `commit_status` FROM `file` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new File();
            $obj->hydrate($row);
            FilePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return File|File[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|File[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FilePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FilePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FilePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FilePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the filename column
     *
     * Example usage:
     * <code>
     * $query->filterByFilename('fooValue');   // WHERE filename = 'fooValue'
     * $query->filterByFilename('%fooValue%'); // WHERE filename LIKE '%fooValue%'
     * </code>
     *
     * @param     string $filename The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByFilename($filename = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($filename)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $filename)) {
                $filename = str_replace('*', '%', $filename);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::FILENAME, $filename, $comparison);
    }

    /**
     * Filter the query on the commit_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCommitId('fooValue');   // WHERE commit_id = 'fooValue'
     * $query->filterByCommitId('%fooValue%'); // WHERE commit_id LIKE '%fooValue%'
     * </code>
     *
     * @param     string $commitId The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByCommitId($commitId = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($commitId)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $commitId)) {
                $commitId = str_replace('*', '%', $commitId);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::COMMIT_ID, $commitId, $comparison);
    }

    /**
     * Filter the query on the commit_status column
     *
     * Example usage:
     * <code>
     * $query->filterByCommitStatus('fooValue');   // WHERE commit_status = 'fooValue'
     * $query->filterByCommitStatus('%fooValue%'); // WHERE commit_status LIKE '%fooValue%'
     * </code>
     *
     * @param     string $commitStatus The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByCommitStatus($commitStatus = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($commitStatus)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $commitStatus)) {
                $commitStatus = str_replace('*', '%', $commitStatus);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::COMMIT_STATUS, $commitStatus, $comparison);
    }

    /**
     * Filter the query by a related Propelcommit object
     *
     * @param   Propelcommit|PropelObjectCollection $propelcommit The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPropelcommit($propelcommit, $comparison = null)
    {
        if ($propelcommit instanceof Propelcommit) {
            return $this
                ->addUsingAlias(FilePeer::COMMIT_ID, $propelcommit->getId(), $comparison);
        } elseif ($propelcommit instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FilePeer::COMMIT_ID, $propelcommit->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPropelcommit() only accepts arguments of type Propelcommit or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Propelcommit relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinPropelcommit($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Propelcommit');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Propelcommit');
        }

        return $this;
    }

    /**
     * Use the Propelcommit relation Propelcommit object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Lm\CommitBundle\Model\PropelcommitQuery A secondary query class using the current class as primary query
     */
    public function usePropelcommitQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPropelcommit($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Propelcommit', '\Lm\CommitBundle\Model\PropelcommitQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   File $file Object to remove from the list of results
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function prune($file = null)
    {
        if ($file) {
            $this->addUsingAlias(FilePeer::ID, $file->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
