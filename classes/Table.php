<?php

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

        $rows = self::query($sql, $cols, $args);

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

        if(empty($this->Id))
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
                    if(!empty($value))
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

            if($insertId = self::insertOrUpdate($sql, $cols, $args) > 0)
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
                    //only add to insert statement if value is not empty
                    if(!empty($value))
                    {
                        if(!empty($updates))
                            $updates .= ', ';

                        $updates .= ' ! = ?';
                        $cols[] = $key;
                        $args[] = $value;
                    }
                }
            }

            $sql .= $updates . " WHERE ! = ?";
            $cols[] = 'Id';
            $args[] = $this->Id;


            if($insertId = self::insertOrUpdate($sql, $cols, $args) > 0)
            {
                $this->Id = $insertId;

                return true;
            }
            return false;
        }
    }

    /**
     * Queries the database to gather rows
     * @param string $query to run
     * @param string[] cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return string[]
     */
    protected static function query($query, $cols = array(), $args = array())
    {
        $database = new Database();
        $results = $database->query($query, $cols, $args);
        $database->close();

        return $results;

    }

    /**
     * Inserts or updates a record in the database
     * @param string $query to run
     * @param string[] $cols columns that will replace !
     * @param string[] | int[] $args arguments that will replace ?
     * @return int
     */
    private static function insertOrUpdate($query, $cols = array(), $args = array())
    {
        $database = new Database();
        $id = $database->insertOrUpdate($query, $cols, $args);
        $database->close();
        return $id;
    }

    /**
     * Retrieves objects from the database
     * @param string $orderBy override if the order by column needs to be changed
     * @return static[]
     */
    public static function getObjects($orderBy = 'Id')
    {
        $sql = 'SELECT * FROM ! ORDER BY ! DESC';
        $cols[] = static::$TABLE_NAME;
        $cols[] = $orderBy;

        $rows = self::query($sql, $cols);

        foreach($rows as $row)
            $response[] = self::withProperties($row);

        return $response;
    }

    abstract public function toString();
    abstract public function toHtml();

}

?>