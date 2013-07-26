<?php

/**
 * @author Ryan Wong
 * @version 1.0
 * @package Core
 * @category Main
 * @copyright (c) 2013, Ryan Wong
 */
class Core_SqlManager {

    protected $_con = null;
    protected $_dbType = 'mysql';

    public function __construct($dbType, $dbName, $dbHostName, $dbUser, $dbPassword) {
        switch ($dbType) {
            case 'mysql':
                $dsn = "mysql:dbname={$dbName};host={$dbHostName}";
                $this->_con = new PDO($dsn, $dbUser, $dbPassword);
                $this->_dbType = 'mysql';
                break;
            case 'sqlite':
                $this->_con = new PDO("sqlite:{$dbName}");
                $this->_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->_dbType = 'sqlite';
                break;
            default:
                break;
        }
    }

    public function query($sql) {
        switch ($this->_dbType) {
            case 'mysql':
                return $this->_con->query($sql);
                break;
            case 'sqlite':
                return $this->_con->query($sql);
                break;
            default:
                break;
        }
    }

    public function prepare($sql, $params) {
        switch ($this->_dbType) {
            case 'mysql':
                $stmt = $this->_con->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                return $stmt->execute();
                break;
            case 'sqlite':
                foreach ($params as $key => $value) {
                    if ($value == "'NULL'") {
                        $value = "NULL";
                        $sql = str_replace($key, "$value", $sql);
                    } else {
                        $firstPos = startsWith($value, "'");
                        $endPos = endsWith($value, "'");
                        if ($firstPos && $endPos) {
                            $sql = str_replace($key, "$value", $sql);
                        } else {
                            $sql = str_replace($key, "'$value'", $sql);
                        }
                    }
                }                
                return $this->query($sql);
                break;
            default:
                break;
        }
    }

    public function getTables() {
        $tables = array();
        switch ($this->_dbType) {
            case 'mysql':
                $query = "SHOW TABLES";
                $result = $this->_con->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $tables[] = reset($row);
                }
                break;
            case 'sqlite':
                $result = $this->_con->query('SELECT name FROM sqlite_master WHERE type = "table"');
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $tables[] = $row['name'];
                }
                break;
            default:
                break;
        }
        return $tables;
    }

    public function getTableDescription($tableName) {
        $table = array();
        switch ($this->_dbType) {
            case 'mysql':
                $query = "DESCRIBE $tableName";
                $result = $this->_con->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $table[] = $row;
                }
                break;
            case 'sqlite':
                $query = "PRAGMA table_info($tableName)";
                $result = $this->_con->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $table[] = array(
                        'Field' => $row['name'],
                        'Type' => $row['type'],
                        'Null' => ($row['notnull'] === 0) ? 'NO' : 'YES',
                        'Key' => ($row['pk'] == '1') ? 'PRI' : '',
                        'Default' => $row['dflt_value'],
                        'Extra' => ''
                    );
                }
            default:
                break;
        }
        return $table;
    }

    public function lastInsertId() {
        return $this->_con->lastInsertId();
    }

}

?>
