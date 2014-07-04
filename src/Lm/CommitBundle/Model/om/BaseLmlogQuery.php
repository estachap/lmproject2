<?php

namespace Lm\CommitBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Lm\CommitBundle\Model\Lmlog;
use Lm\CommitBundle\Model\LmlogPeer;
use Lm\CommitBundle\Model\LmlogQuery;

/**
 * @method LmlogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method LmlogQuery orderByLogtype($order = Criteria::ASC) Order by the logtype column
 * @method LmlogQuery orderByLogmessage($order = Criteria::ASC) Order by the logmessage column
 * @method LmlogQuery orderByLogdate($order = Criteria::ASC) Order by the logdate column
 *
 * @method LmlogQuery groupById() Group by the id column
 * @method LmlogQuery groupByLogtype() Group by the logtype column
 * @method LmlogQuery groupByLogmessage() Group by the logmessage column
 * @method LmlogQuery groupByLogdate() Group by the logdate column
 *
 * @method LmlogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method LmlogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method LmlogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method Lmlog findOne(PropelPDO $con = null) Return the first Lmlog matching the query
 * @method Lmlog findOneOrCreate(PropelPDO $con = null) Return the first Lmlog matching the query, or a new Lmlog object populated from the query conditions when no match is found
 *
 * @method Lmlog findOneByLogtype(int $logtype) Return the first Lmlog filtered by the logtype column
 * @method Lmlog findOneByLogmessage(string $logmessage) Return the first Lmlog filtered by the logmessage column
 * @method Lmlog findOneByLogdate(string $logdate) Return the first Lmlog filtered by the logdate column
 *
 * @method array findById(int $id) Return Lmlog objects filtered by the id column
 * @method array findByLogtype(int $logtype) Return Lmlog objects filtered by the logtype column
 * @method array findByLogmessage(string $logmessage) Return Lmlog objects filtered by the logmessage column
 * @method array findByLogdate(string $logdate) Return Lmlog objects filtered by the logdate column
 */
abstract class BaseLmlogQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseLmlogQuery object.
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
            $modelName = 'Lm\\CommitBundle\\Model\\Lmlog';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new LmlogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   LmlogQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return LmlogQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof LmlogQuery) {
            return $criteria;
        }
        $query = new LmlogQuery(null, null, $modelAlias);

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
     * @return   Lmlog|Lmlog[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = LmlogPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(LmlogPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Lmlog A model object, or null if the key is not found
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
     * @return                 Lmlog A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `logtype`, `logmessage`, `logdate` FROM `lmlog` WHERE `id` = :p0';
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
            $obj = new Lmlog();
            $obj->hydrate($row);
            LmlogPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Lmlog|Lmlog[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Lmlog[]|mixed the list of results, formatted by the current formatter
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
     * @return LmlogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(LmlogPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return LmlogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(LmlogPeer::ID, $keys, Criteria::IN);
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
     * @return LmlogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(LmlogPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(LmlogPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LmlogPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the logtype column
     *
     * @param     mixed $logtype The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LmlogQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByLogtype($logtype = null, $comparison = null)
    {
        if (is_scalar($logtype)) {
            $logtype = LmlogPeer::getSqlValueForEnum(LmlogPeer::LOGTYPE, $logtype);
        } elseif (is_array($logtype)) {
            $convertedValues = array();
            foreach ($logtype as $value) {
                $convertedValues[] = LmlogPeer::getSqlValueForEnum(LmlogPeer::LOGTYPE, $value);
            }
            $logtype = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LmlogPeer::LOGTYPE, $logtype, $comparison);
    }

    /**
     * Filter the query on the logmessage column
     *
     * Example usage:
     * <code>
     * $query->filterByLogmessage('fooValue');   // WHERE logmessage = 'fooValue'
     * $query->filterByLogmessage('%fooValue%'); // WHERE logmessage LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logmessage The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LmlogQuery The current query, for fluid interface
     */
    public function filterByLogmessage($logmessage = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logmessage)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $logmessage)) {
                $logmessage = str_replace('*', '%', $logmessage);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(LmlogPeer::LOGMESSAGE, $logmessage, $comparison);
    }

    /**
     * Filter the query on the logdate column
     *
     * Example usage:
     * <code>
     * $query->filterByLogdate('2011-03-14'); // WHERE logdate = '2011-03-14'
     * $query->filterByLogdate('now'); // WHERE logdate = '2011-03-14'
     * $query->filterByLogdate(array('max' => 'yesterday')); // WHERE logdate < '2011-03-13'
     * </code>
     *
     * @param     mixed $logdate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return LmlogQuery The current query, for fluid interface
     */
    public function filterByLogdate($logdate = null, $comparison = null)
    {
        if (is_array($logdate)) {
            $useMinMax = false;
            if (isset($logdate['min'])) {
                $this->addUsingAlias(LmlogPeer::LOGDATE, $logdate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($logdate['max'])) {
                $this->addUsingAlias(LmlogPeer::LOGDATE, $logdate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(LmlogPeer::LOGDATE, $logdate, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   Lmlog $lmlog Object to remove from the list of results
     *
     * @return LmlogQuery The current query, for fluid interface
     */
    public function prune($lmlog = null)
    {
        if ($lmlog) {
            $this->addUsingAlias(LmlogPeer::ID, $lmlog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
