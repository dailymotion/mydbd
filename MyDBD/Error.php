<?php

/**
 * @package MyDBD
 */

/**
 * @package MyDBD
 * @author Olivier Poitrey (rs@dailymotion.com)
 */
abstract class MyDBD_Error
{
    static protected
        $errorMap = array
        (
            1004 => 'SQLCannotCreateException',
            1005 => 'SQLCannotCreateException',
            1006 => 'SQLCannotCreateException',
            1007 => 'SQLAlreadyExistsException',
            1008 => 'SQLCannotDropException',
            1022 => 'SQLAlreadyExistsException',
            1044 => 'SQLAccessViolationException',
            1046 => 'SQLNodbselectedException',
            1048 => 'SQLConstraintException',
            1049 => 'SQLNoSuchDBException',
            1050 => 'SQLAlreadyExistsException',
            1051 => 'SQLNoSuchTableException',
            1053 => 'SQLServerShutdownException',
            1054 => 'SQLNoSuchFieldException',
            1061 => 'SQLAlreadyExistsException',
            1062 => 'SQLAlreadyExistsException',
            1064 => 'SQLSyntaxException',
            1066 => 'SQLNotUniqueTableAliasException',
            1091 => 'SQLNotFoundException',
            1100 => 'SQLNotLockedException',
            1136 => 'SQLValueCountOnRowException',
            1142 => 'SQLAccessViolationException',
            1146 => 'SQLNoSuchTableException',
            1213 => 'SQLDeadlockException',
            1216 => 'SQLConstraintException',
            1217 => 'SQLConstraintException',
            1227 => 'SQLPrivilegeException',
            1356 => 'SQLDivzeroException',
            1451 => 'SQLConstraintException',
            1452 => 'SQLConstraintException',
            2006 => 'SQLLostConnectionException',
            2013 => 'SQLGenocideException',
            2014 => 'SQLSyncException',
            2030 => 'SQLNotPreparedStatementException',
        );

    static public function throwError($errorno, $error, $sqlstate = null, $query = null, array $params = null)
    {
        if (isset($query))
        {
            $error .= ' for query: ' . $query;
        }

        if (isset($params))
        {
            $error .= ' with params: ' . implode(', ', $params);
        }

        if (isset(self::$errorMap[$errorno]))
        {
            $class = self::$errorMap[$errorno];
            throw new $class($error, $errorno, $sqlstate);
        }
        else
        {
            throw new SQLUnknownException('SQLERROR[' . $errorno . '] ' . $error, $errorno, $sqlstate);
        }
    }
}

/**#@+ @ignore */
class SQLException extends Exception
{
    protected $sqlstate;

    public function __construct($message = null, $code = null, $sqlstate = null)
    {
        $this->message = $message;
        $this->code = $code;
        $this->sqlstate = $sqlstate;
    }

    public function appendMessage ($m)
    {
        $this->message .= " $m";
    }
}

class SQLAccessViolationException extends SQLException {}
class SQLAlreadyExistsException extends SQLException {}
class SQLCannotCreateException extends SQLException {}
class SQLCannotDropException extends SQLException {}
class SQLConnectFailedException extends SQLException {}
class SQLConstraintException extends SQLException {}
class SQLConstraintNotNullException extends SQLException {}
class SQLDeadlockException extends SQLException {}
class SQLDivzeroException extends SQLException {}
class SQLExtensionNotFoundException extends SQLException {}
class SQLGenocideException extends SQLException {}
class SQLInvalidDateException extends SQLException {}
class SQLInvalidDSNException extends SQLException {}
class SQLInvalidException extends SQLException {}
class SQLInvalidNumberException extends SQLException {}
class SQLLostConnectionException extends SQLException {}
class SQLMismatchException extends SQLException {}
class SQLNeedMoreDataException extends SQLException {}
class SQLNodbselectedException extends SQLException {}
class SQLNoSuchDBException extends SQLException {}
class SQLNoSuchFieldException extends SQLException {}
class SQLNoSuchTableException extends SQLException {}
class SQLNotConnectedException extends SQLException {}
class SQLNotFoundException extends SQLException {}
class SQLNotLockedException extends SQLException {}
class SQLNotPreparedStatementException extends SQLException {}
class SQLNotUniqueTableAliasException extends SQLException {}
class SQLPrivilegeException extends SQLException {}
class SQLReadOnlyException extends SQLException {}
class SQLServerShutdownException extends SQLException {}
class SQLSyncException extends SQLException {}
class SQLSyntaxException extends SQLException {}
class SQLTruncatedException extends SQLException {}
class SQLUnknownException extends SQLException {}
class SQLUnsupportedException extends SQLException {}
class SQLValueCountOnRowException extends SQLException {}

/**#@-*/
