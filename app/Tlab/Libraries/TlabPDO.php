<?php

namespace Tlab\Libraries;

class TlabPDO extends \PDO
{
    protected $_table_prefix;

    public function __construct($dsn, $user = null, $password = null, $driver_options = array(), $prefix = '')
    {
        $this->_table_prefix = $prefix;
        parent::__construct($dsn, $user, $password, $driver_options);
    }

    public function exec($statement)
    {
        $statement = $this->_tablePrefix($statement);

        return parent::exec($statement);
    }

    public function prepare($statement, $driver_options = array())
    {
        $statement = $this->_tablePrefix($statement);

        return parent::prepare($statement, $driver_options);
    }

    public function query($statement)
    {
        $statement = $this->_tablePrefix($statement);
        $args = func_get_args();

        if (count($args) > 1) {
            return call_user_func_array(array($this, 'parent::query'), $args);
        } else {
            return parent::query($statement);
        }
    }

    protected function _tablePrefix($statement, $prefix = '#__')
    {
        $statement = trim($statement);

        $escaped = false;
        $quoteChar = '';

        $n = strlen($statement);

        $startPos = 0;
        $literal = '';
        while ($startPos < $n) {
            $ip = strpos($statement, $prefix, $startPos);
            if ($ip === false) {
                break;
            }

            $j = strpos($statement, "'", $startPos);
            $k = strpos($statement, '"', $startPos);
            if (($k !== false) && (($k < $j) || ($j === false))) {
                $quoteChar = '"';
                $j = $k;
            } else {
                $quoteChar = "'";
            }

            if ($j === false) {
                $j = $n;
            }

            $literal .= str_replace($prefix, $this->_table_prefix, substr($statement, $startPos, $j - $startPos));
            $startPos = $j;

            $j = $startPos + 1;

            if ($j >= $n) {
                break;
            }

            // quote comes first, find end of quote
            while (true) {
                $k = strpos($statement, $quoteChar, $j);
                $escaped = false;
                if ($k === false) {
                    break;
                }
                $l = $k - 1;
                while ($l >= 0 && $statement{$l} == '\\') {
                    --$l;
                    $escaped = !$escaped;
                }
                if ($escaped) {
                    $j = $k + 1;
                    continue;
                }
                break;
            }
            if ($k === false) {
                // error in the query - no end quote; ignore it
                break;
            }
            $literal .= substr($statement, $startPos, $k - $startPos + 1);
            $startPos = $k + 1;
        }
        if ($startPos < $n) {
            $literal .= substr($statement, $startPos, $n - $startPos);
        }

        return $literal;
    }
}
