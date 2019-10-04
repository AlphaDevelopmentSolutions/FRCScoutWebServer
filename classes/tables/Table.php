<?php

require_once(ROOT_DIR . '/classes/tables/core/CoreTable.php');
require_once(ROOT_DIR . '/classes/tables/local/LocalTable.php');

abstract class Table
{

    /**
     * Loads a new instance by its database id
     * @param string | int $id
     * @param string $columnName
     * @return static
     */
    static function withId($id, $columnName = 'Id')
    {
        $class = get_called_class();
        $instance = new $class();
        $instance->loadById($id, $columnName);
        return $instance;
    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     * @return static
     */
    static function withProperties(Array $properties = array())
    {
        $class = get_called_class();
        $instance = new $class();
        $instance->loadByProperties($properties);
        return $instance;
    }

    /**
     * Loads a new instance by specified properties
     * @param array $properties
     */
    protected function loadByProperties(Array $properties = array())
    {
        foreach($properties as $key => $value)
            if(property_exists($this, $key))
                $this->{$key} = $value;
    }

    /**
     * Loads a new instance by its database id
     * @param string | int $id
     * @param string $columnName
     * @return boolean
     */
    protected function loadById($id, $columnName)
    {
        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ?";
        $cols[] = $this::$TABLE_NAME;

        $cols[] = $columnName;
        $args[] = $id;

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
        {
            foreach($row as $key => $value)
            {
                if(property_exists($this, $key))
                    $this->$key = $value;

            }

            return true;
        }

        return false;
    }

    /**
     * Saves or updates the object into the database
     * @return bool
     */
    function save()
    {
        if(empty($this->Id) || $this->Id == -1)
        {
            //create the sql statement
            $sql = "INSERT INTO ! (";
            $cols[] = $this::$TABLE_NAME;

            $columnsString = '';
            $valuesString = '';
            //iterate through each field in the current class
            foreach ($this as $key => $value)
            {
                //dont use Id in cols or vals
                if($key != 'Id' && property_exists($this, $key))
                {
                    //only add to insert statement if value is not empty
                    if(!empty($value) || $value == '0')
                    {
                        if(!empty($columnsString))
                            $columnsString .= ', ';

                        $columnsString .= '!';
                        $cols[] = $key;

                        if(!empty($valuesString))
                            $valuesString .= ', ';

                        $valuesString .=  '?';
                        $args[] = $value;
                    }
                }
            }

            $sql .="$columnsString) VALUES ($valuesString)";

            if($insertId = self::insertOrUpdateRecords($sql, $cols, $args) > -1)
            {
                $this->Id = $insertId;

                return true;
            }
            return false;

        }
        else
        {
            //create the sql statement
            $sql = "UPDATE ! SET ";
            $cols[] = $this::$TABLE_NAME;

            $updates = '';
            //iterate through each field in the current class
            foreach ($this as $key => $value)
            {
                //dont use Id in cols or vals
                if($key != 'Id' && property_exists($this, $key))
                {
                    if(!empty($updates))
                        $updates .= ', ';

                    if(!empty($value) || $value == '0')
                    {
                        $updates .= ' ! = ?';
                        $cols[] = $key;
                        $args[] = $value;
                    }
                    else
                    {
                        $updates .= ' ! = NULL';
                        $cols[] = $key;
                    }
                }
            }

            $sql .= $updates . " WHERE ! = ?";
            $cols[] = 'Id';
            $args[] = $this->Id;


            if($insertId = self::insertOrUpdateRecords($sql, $cols, $args) > -1)
                return true;

            return false;
        }
    }

    /**
     * Attempts to delete the record from the database
     * @return bool
     */
    public function delete()
    {
        if(empty($this->Id))
            return false;

        $query = 'DELETE FROM ! WHERE ! = ?';
        $cols[] = $this::$TABLE_NAME;
        $cols[] = 'Id';
        $args[] = $this->Id;

        return self::deleteRecords($query, $cols, $args);
    }

    /**
     * Queries the database to gather rows
     * @param string $query to run
     * @param string[] cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @param string | null $db database name to read from
     * @return string[]
     */
    protected static function queryRecords($query, $cols = array(), $args = array(), $db = null)
    {
        $database = new Database((($db == null) ? static::$DB_NAME : $db));
        $results = $database->query($query, $cols, $args);
        unset($database);

        return $results;

    }

    /**
     * Inserts or updates a record in the database
     * @param string $query to run
     * @param string[] $cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return int
     */
    protected static function insertOrUpdateRecords($query, $cols = array(), $args = array())
    {
        $database = new Database(static::$DB_NAME);
        $id = $database->insertOrUpdate($query, $cols, $args);
        unset($database);
        return $id;
    }

    /**
     * Deletes records from the database
     * @param string $query to run
     * @param string[] cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return bool
     */
    protected static function deleteRecords($query, $cols = array(), $args = array())
    {
        $database = new Database(static::$DB_NAME);
        $success = $database->delete($query, $cols, $args);
        unset($database);

        return $success;

    }

    /**
     * Retrieves objects from the database
     * @param string $whereStatement where statement to be used in the MySQL fetch
     * @param array $whereCols cols for $whereStatement
     * @param array $whereArgs args for $whereStatement
     * @param string $orderBy override if the order by column needs to be changed
     * @param string $orderDirection override if the order direction needs to be changed
     * @return static[]
     */
    public static function getObjects($whereStatement = "", $whereCols = array(), $whereArgs = array(), $orderBy = 'Id', $orderDirection = 'DESC')
    {
        $sql = 'SELECT * FROM ! ';
        $cols[] = static::$TABLE_NAME;
        $args = array();

        //populate where args if specified
        if(!empty($whereStatement))
        {
            $sql .= ' WHERE ' . $whereStatement;

            foreach ($whereCols as $whereCol)
                $cols[] = $whereCol;

            foreach ($whereArgs as $whereArg)
                $args[] = $whereArg;
        }
        $sql .= ' ORDER BY ! ' . $orderDirection;
        $cols[] = $orderBy;

        $rows = self::queryRecords($sql, $cols, $args);

        $response = array();

        foreach($rows as $row)
            $response[] = self::withProperties($row);

        return $response;
    }

    /**
     * Retrieves column names from the database
     * @return string[] array of column names and types
     */
    public static function getColumns()
    {
        $sql = 'SHOW COLUMNS FROM !';
        $cols[] = static::$TABLE_NAME;

        $rows = self::queryRecords($sql, $cols);

        return $rows;
    }

    abstract public function toString();
    abstract public function toHtml();

}

?>