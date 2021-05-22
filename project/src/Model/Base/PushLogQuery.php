<?php

namespace Push\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Push\Model\PushLog as ChildPushLog;
use Push\Model\PushLogQuery as ChildPushLogQuery;
use Push\Model\Map\PushLogTableMap;

/**
 * Base class that represents a query for the 'push_log' table.
 *
 *
 *
 * @method     ChildPushLogQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPushLogQuery orderByUsers($order = Criteria::ASC) Order by the users column
 * @method     ChildPushLogQuery orderByPush($order = Criteria::ASC) Order by the push column
 * @method     ChildPushLogQuery orderByErrors($order = Criteria::ASC) Order by the errors column
 * @method     ChildPushLogQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 *
 * @method     ChildPushLogQuery groupById() Group by the id column
 * @method     ChildPushLogQuery groupByUsers() Group by the users column
 * @method     ChildPushLogQuery groupByPush() Group by the push column
 * @method     ChildPushLogQuery groupByErrors() Group by the errors column
 * @method     ChildPushLogQuery groupByCreatedAt() Group by the created_at column
 *
 * @method     ChildPushLogQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPushLogQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPushLogQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPushLogQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPushLogQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPushLogQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPushLog findOne(ConnectionInterface $con = null) Return the first ChildPushLog matching the query
 * @method     ChildPushLog findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPushLog matching the query, or a new ChildPushLog object populated from the query conditions when no match is found
 *
 * @method     ChildPushLog findOneById(int $id) Return the first ChildPushLog filtered by the id column
 * @method     ChildPushLog findOneByUsers(string $users) Return the first ChildPushLog filtered by the users column
 * @method     ChildPushLog findOneByPush(string $push) Return the first ChildPushLog filtered by the push column
 * @method     ChildPushLog findOneByErrors(string $errors) Return the first ChildPushLog filtered by the errors column
 * @method     ChildPushLog findOneByCreatedAt(string $created_at) Return the first ChildPushLog filtered by the created_at column *

 * @method     ChildPushLog requirePk($key, ConnectionInterface $con = null) Return the ChildPushLog by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushLog requireOne(ConnectionInterface $con = null) Return the first ChildPushLog matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPushLog requireOneById(int $id) Return the first ChildPushLog filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushLog requireOneByUsers(string $users) Return the first ChildPushLog filtered by the users column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushLog requireOneByPush(string $push) Return the first ChildPushLog filtered by the push column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushLog requireOneByErrors(string $errors) Return the first ChildPushLog filtered by the errors column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushLog requireOneByCreatedAt(string $created_at) Return the first ChildPushLog filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPushLog[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPushLog objects based on current ModelCriteria
 * @method     ChildPushLog[]|ObjectCollection findById(int $id) Return ChildPushLog objects filtered by the id column
 * @method     ChildPushLog[]|ObjectCollection findByUsers(string $users) Return ChildPushLog objects filtered by the users column
 * @method     ChildPushLog[]|ObjectCollection findByPush(string $push) Return ChildPushLog objects filtered by the push column
 * @method     ChildPushLog[]|ObjectCollection findByErrors(string $errors) Return ChildPushLog objects filtered by the errors column
 * @method     ChildPushLog[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildPushLog objects filtered by the created_at column
 * @method     ChildPushLog[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PushLogQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Push\Model\Base\PushLogQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'push', $modelName = '\\Push\\Model\\PushLog', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPushLogQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPushLogQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPushLogQuery) {
            return $criteria;
        }
        $query = new ChildPushLogQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPushLog|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PushLogTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PushLogTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPushLog A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, users, push, errors, created_at FROM push_log WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPushLog $obj */
            $obj = new ChildPushLog();
            $obj->hydrate($row);
            PushLogTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPushLog|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PushLogTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PushLogTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PushLogTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PushLogTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushLogTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the users column
     *
     * Example usage:
     * <code>
     * $query->filterByUsers('fooValue');   // WHERE users = 'fooValue'
     * $query->filterByUsers('%fooValue%', Criteria::LIKE); // WHERE users LIKE '%fooValue%'
     * </code>
     *
     * @param     string $users The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByUsers($users = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($users)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushLogTableMap::COL_USERS, $users, $comparison);
    }

    /**
     * Filter the query on the push column
     *
     * Example usage:
     * <code>
     * $query->filterByPush('fooValue');   // WHERE push = 'fooValue'
     * $query->filterByPush('%fooValue%', Criteria::LIKE); // WHERE push LIKE '%fooValue%'
     * </code>
     *
     * @param     string $push The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByPush($push = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($push)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushLogTableMap::COL_PUSH, $push, $comparison);
    }

    /**
     * Filter the query on the errors column
     *
     * Example usage:
     * <code>
     * $query->filterByErrors('fooValue');   // WHERE errors = 'fooValue'
     * $query->filterByErrors('%fooValue%', Criteria::LIKE); // WHERE errors LIKE '%fooValue%'
     * </code>
     *
     * @param     string $errors The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByErrors($errors = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($errors)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushLogTableMap::COL_ERRORS, $errors, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PushLogTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PushLogTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushLogTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPushLog $pushLog Object to remove from the list of results
     *
     * @return $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function prune($pushLog = null)
    {
        if ($pushLog) {
            $this->addUsingAlias(PushLogTableMap::COL_ID, $pushLog->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the push_log table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PushLogTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PushLogTableMap::clearInstancePool();
            PushLogTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PushLogTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PushLogTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PushLogTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PushLogTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Order by create date desc
     *
     * @return     $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PushLogTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PushLogTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildPushLogQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PushLogTableMap::COL_CREATED_AT);
    }

} // PushLogQuery
