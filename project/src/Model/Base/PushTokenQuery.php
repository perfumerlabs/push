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
use Push\Model\PushToken as ChildPushToken;
use Push\Model\PushTokenQuery as ChildPushTokenQuery;
use Push\Model\Map\PushTokenTableMap;

/**
 * Base class that represents a query for the 'push_token' table.
 *
 *
 *
 * @method     ChildPushTokenQuery orderByCustomerToken($order = Criteria::ASC) Order by the customer_token column
 * @method     ChildPushTokenQuery orderByAppleToken($order = Criteria::ASC) Order by the apple_token column
 * @method     ChildPushTokenQuery orderByGoogleToken($order = Criteria::ASC) Order by the google_token column
 * @method     ChildPushTokenQuery orderByHuaweiToken($order = Criteria::ASC) Order by the huawei_token column
 * @method     ChildPushTokenQuery orderByWebToken($order = Criteria::ASC) Order by the web_token column
 *
 * @method     ChildPushTokenQuery groupByCustomerToken() Group by the customer_token column
 * @method     ChildPushTokenQuery groupByAppleToken() Group by the apple_token column
 * @method     ChildPushTokenQuery groupByGoogleToken() Group by the google_token column
 * @method     ChildPushTokenQuery groupByHuaweiToken() Group by the huawei_token column
 * @method     ChildPushTokenQuery groupByWebToken() Group by the web_token column
 *
 * @method     ChildPushTokenQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPushTokenQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPushTokenQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPushTokenQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPushTokenQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPushTokenQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPushToken findOne(ConnectionInterface $con = null) Return the first ChildPushToken matching the query
 * @method     ChildPushToken findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPushToken matching the query, or a new ChildPushToken object populated from the query conditions when no match is found
 *
 * @method     ChildPushToken findOneByCustomerToken(string $customer_token) Return the first ChildPushToken filtered by the customer_token column
 * @method     ChildPushToken findOneByAppleToken(string $apple_token) Return the first ChildPushToken filtered by the apple_token column
 * @method     ChildPushToken findOneByGoogleToken(string $google_token) Return the first ChildPushToken filtered by the google_token column
 * @method     ChildPushToken findOneByHuaweiToken(string $huawei_token) Return the first ChildPushToken filtered by the huawei_token column
 * @method     ChildPushToken findOneByWebToken(string $web_token) Return the first ChildPushToken filtered by the web_token column *

 * @method     ChildPushToken requirePk($key, ConnectionInterface $con = null) Return the ChildPushToken by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushToken requireOne(ConnectionInterface $con = null) Return the first ChildPushToken matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPushToken requireOneByCustomerToken(string $customer_token) Return the first ChildPushToken filtered by the customer_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushToken requireOneByAppleToken(string $apple_token) Return the first ChildPushToken filtered by the apple_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushToken requireOneByGoogleToken(string $google_token) Return the first ChildPushToken filtered by the google_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushToken requireOneByHuaweiToken(string $huawei_token) Return the first ChildPushToken filtered by the huawei_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPushToken requireOneByWebToken(string $web_token) Return the first ChildPushToken filtered by the web_token column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPushToken[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPushToken objects based on current ModelCriteria
 * @method     ChildPushToken[]|ObjectCollection findByCustomerToken(string $customer_token) Return ChildPushToken objects filtered by the customer_token column
 * @method     ChildPushToken[]|ObjectCollection findByAppleToken(string $apple_token) Return ChildPushToken objects filtered by the apple_token column
 * @method     ChildPushToken[]|ObjectCollection findByGoogleToken(string $google_token) Return ChildPushToken objects filtered by the google_token column
 * @method     ChildPushToken[]|ObjectCollection findByHuaweiToken(string $huawei_token) Return ChildPushToken objects filtered by the huawei_token column
 * @method     ChildPushToken[]|ObjectCollection findByWebToken(string $web_token) Return ChildPushToken objects filtered by the web_token column
 * @method     ChildPushToken[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PushTokenQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Push\Model\Base\PushTokenQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'push', $modelName = '\\Push\\Model\\PushToken', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPushTokenQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPushTokenQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPushTokenQuery) {
            return $criteria;
        }
        $query = new ChildPushTokenQuery();
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
     * @return ChildPushToken|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PushTokenTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = PushTokenTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildPushToken A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT customer_token, apple_token, google_token, huawei_token, web_token FROM push_token WHERE customer_token = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPushToken $obj */
            $obj = new ChildPushToken();
            $obj->hydrate($row);
            PushTokenTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildPushToken|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PushTokenTableMap::COL_CUSTOMER_TOKEN, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PushTokenTableMap::COL_CUSTOMER_TOKEN, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the customer_token column
     *
     * Example usage:
     * <code>
     * $query->filterByCustomerToken('fooValue');   // WHERE customer_token = 'fooValue'
     * $query->filterByCustomerToken('%fooValue%', Criteria::LIKE); // WHERE customer_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $customerToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByCustomerToken($customerToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($customerToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushTokenTableMap::COL_CUSTOMER_TOKEN, $customerToken, $comparison);
    }

    /**
     * Filter the query on the apple_token column
     *
     * Example usage:
     * <code>
     * $query->filterByAppleToken('fooValue');   // WHERE apple_token = 'fooValue'
     * $query->filterByAppleToken('%fooValue%', Criteria::LIKE); // WHERE apple_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $appleToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByAppleToken($appleToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($appleToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushTokenTableMap::COL_APPLE_TOKEN, $appleToken, $comparison);
    }

    /**
     * Filter the query on the google_token column
     *
     * Example usage:
     * <code>
     * $query->filterByGoogleToken('fooValue');   // WHERE google_token = 'fooValue'
     * $query->filterByGoogleToken('%fooValue%', Criteria::LIKE); // WHERE google_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $googleToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByGoogleToken($googleToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($googleToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushTokenTableMap::COL_GOOGLE_TOKEN, $googleToken, $comparison);
    }

    /**
     * Filter the query on the huawei_token column
     *
     * Example usage:
     * <code>
     * $query->filterByHuaweiToken('fooValue');   // WHERE huawei_token = 'fooValue'
     * $query->filterByHuaweiToken('%fooValue%', Criteria::LIKE); // WHERE huawei_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $huaweiToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByHuaweiToken($huaweiToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($huaweiToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushTokenTableMap::COL_HUAWEI_TOKEN, $huaweiToken, $comparison);
    }

    /**
     * Filter the query on the web_token column
     *
     * Example usage:
     * <code>
     * $query->filterByWebToken('fooValue');   // WHERE web_token = 'fooValue'
     * $query->filterByWebToken('%fooValue%', Criteria::LIKE); // WHERE web_token LIKE '%fooValue%'
     * </code>
     *
     * @param     string $webToken The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function filterByWebToken($webToken = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($webToken)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PushTokenTableMap::COL_WEB_TOKEN, $webToken, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPushToken $pushToken Object to remove from the list of results
     *
     * @return $this|ChildPushTokenQuery The current query, for fluid interface
     */
    public function prune($pushToken = null)
    {
        if ($pushToken) {
            $this->addUsingAlias(PushTokenTableMap::COL_CUSTOMER_TOKEN, $pushToken->getCustomerToken(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the push_token table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PushTokenTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PushTokenTableMap::clearInstancePool();
            PushTokenTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(PushTokenTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PushTokenTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PushTokenTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PushTokenTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // PushTokenQuery
