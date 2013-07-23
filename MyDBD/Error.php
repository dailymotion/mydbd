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
            1091 => 'SQLNotFoundException',
            1100 => 'SQLNotLockedException',
            1136 => 'SQLValueCountOnRowException',
            1142 => 'SQLAccessViolationException',
            1146 => 'SQLNoSuchTableException',
            1216 => 'SQLConstraintException',
            1217 => 'SQLConstraintException',
            1356 => 'SQLDivzeroException',
            1451 => 'SQLConstraintException',
            1452 => 'SQLConstraintException',
            1066 => 'SQLNotUniqueTableAliasException',
            2030 => 'SQLNotPreparedStatementException',
            1227 => 'SQLPrivilegeException',
            1213 => 'SQLDeadlockException',
            2006 => 'SQLLostConnectionException',
            2013 => 'SQLGenocideException',
            2014 => 'SQLSyncException',
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

class SQLSyncException extends SQLException {}
class SQLUnknownException extends SQLException {}
class SQLSyntaxException extends SQLException {}
class SQLConstraintException extends SQLException {}
class SQLNotFoundException extends SQLException {}
class SQLAlreadyExistsException extends SQLException {}
class SQLUnsupportedException extends SQLException {}
class SQLMismatchException extends SQLException {}
class SQLInvalidException extends SQLException {}
class SQLTruncatedException extends SQLException {}
class SQLInvalidNumberException extends SQLException {}
class SQLInvalidDateException extends SQLException {}
class SQLDivzeroException extends SQLException {}
class SQLNodbselectedException extends SQLException {}
class SQLCannotCreateException extends SQLException {}
class SQLCannotDropException extends SQLException {}
class SQLNoSuchTableException extends SQLException {}
class SQLServerShutdownException extends SQLException {}
class SQLNoSuchFieldException extends SQLException {}
class SQLNeedMoreDataException extends SQLException {}
class SQLNotLockedException extends SQLException {}
class SQLValueCountOnRowException extends SQLException {}
class SQLInvalidDSNException extends SQLException {}
class SQLConnectFailedException extends SQLException {}
class SQLExtensionNotFoundException extends SQLException {}
class SQLAccessViolationException extends SQLException {}
class SQLNoSuchDBException extends SQLException {}
class SQLConstraintNotNullException extends SQLException {}
class SQLReadOnlyException extends SQLException {}
class SQLNotConnectedException extends SQLException {}
class SQLNotPreparedStatementException extends SQLException {}
class SQLNotUniqueTableAliasException extends SQLException {}
class SQLPrivilegeException extends SQLException {}
class SQLDeadlockException extends SQLException {}
class SQLLostConnectionException extends SQLException {}
class SQLGenocideException extends SQLException {}

/**#@-*/
