<?php

/**
 * @package MyDBD
 */

/**
 * MyDBD_Logger is an SQL query logger for MyDBD.
 *
 * @package MyDBD
 * @author Olivier Poitrey (rs@dailymotion.com)
 */
class MyDBD_Logger
{
    static private
        $instance = null;

    private
        $logs = array();

    static public function getDefaultInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new self();
        }

        return self::$instance;
    }

    static public function setDefaultInstance(MyDBD_Logger $logger)
    {
        self::$instance = $logger;
    }

    /**
     * Log a new query. This method is meant to be call by MyDBD and MyDBD_PreparedStatement when
     * the query_log option is activated.
     *
     * @internal
     *
     * @param string  $command  The command executed, should be "query", "prepare" or "execute".
     * @param string  $query    The SQL query being executed or prepared.
     * @param array   $params   The optional list of parameters corresponding to SQL query markers.
     * @param integer $duration The time taken to complete the command in milisecond.
     *
     * @return void
     */
    public function log($command, $query, array $params = null, $duration)
    {
        $callPath = array();

        foreach (array_reverse(debug_backtrace()) as $step)
        {
            if (isset($step['class']))
            {
                if (!preg_match('/^MyDBD/', $step['class']))
                {
                    $callPath[] = $step['class'] . (isset($step['line']) ? ':' . $step['line'] : '');
                }
            }
            elseif (isset($step['file']))
            {
                $callPath[] = $step['file'] . (isset($step['line']) ? ':' . $step['line'] : '');
            }
            else
            {
                $callPath[] = '{main}' . (isset($step['line']) ? ':' . $step['line'] : '');
            }
        }

        if (isset($params))
        {
            $query = preg_replace_callback(
                '/\?/',
                function($matches) use ($params){
                    return array_shift($params);
                },
                $query
            );
        }

        $this->logs[] = array(
            'command' => $command,
            'query' => $query,
            'duration' => $duration,
            'callpath' => $callPath
        );
    }

    /**
     * Retreive log events.
     *
     * @param boolean $sortByDuration Return the event sorted from the slowest to the quickest command.
     *
     * @return array Each array element is an associative array with the following keys:
     *
     * - <b>command</b>:  The command ("query", "prepare", "execute")
     * - <b>query</b>:    The SQL query with markers resolved with parameters for "execute" command.
     * - <b>duration</b>: The number of milisecond taken to complete the command.
     * - <b>callpath</b>: An array of "(class|file):line" strings telling who called the command.
     */
    public function getLogs($sortByDuration = false)
    {
        if ($sortByDuration)
        {
            $sortedLogs = $this->logs;
            usort($sortedLogs, function($a, $b) { return (($a["duration"] == $b["duration"]) ? 0 : (($a["duration"] < $b["duration"]) ? 1 : -1)); });
            return $sortedLogs;
        }
        else
        {
            return $this->logs;
        }
    }

    /**
     * Compute some statistics on all queries logged so far.
     *
     * @return array An associative array with the following keys:
     *
     * - <b>totalTime</b>:    The total amount of time (in milisecond) spent by commands.
     * - <b>totalQueries</b>: The total number of queries executed (all log entries except "prepares").
     * - <b>maxTime</b>:      The slowest command duration (in milisecond).
     */
    public function getStats()
    {
        $stats = array('totalTime' => 0, 'totalQueries' => 0, 'maxTime' => 0);

        foreach ($this->logs as $log)
        {
            if ($log['command'] != 'prepare') $stats['totalQueries']++;
            $stats['totalTime'] += $log['duration'];
            $stats['maxTime'] = max($stats['maxTime'], $log['duration']);
        }

        return $stats;
    }

    /**
     * Clear the log history.
     */
    static public function clear()
    {
        $this->logs = array();
    }
}