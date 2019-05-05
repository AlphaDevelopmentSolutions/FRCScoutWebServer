<?php

abstract class Table
{
    /**
     * Loads a new instance by its database id
     * @param string | int $id
     * @param string $columnName
     * @return static
     */
    static function withId($id, $columnName = null)
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
    protected function loadById($id, $columnName = null)
    {
        //select item from database
        $database = new Database();
        $sql = 'SELECT * FROM ' . $this::$TABLE_NAME . ' WHERE ' . ((empty($columnName)) ? 'Id' : $columnName) .' = ' . $database->quote($id);
        $rs = $database->query($sql);

        if($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();

            //assign row results to object
            if(is_array($row)) {
                foreach($row as $key => $value){
                    if(property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
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
        $database = new Database();

        if(empty($this->Id))
        {
            //create the starting statement
            $sql = 'INSERT INTO ' . $this::$TABLE_NAME . ' 
                                      (';

            //gather all the columns and values to be used in the save statement
            $columns = '';
            $values = '';
            foreach ($this as $key => $value)
            {
                //dont use Id in cols or vals
                if($key != 'Id')
                {
                    //only add to insert statement if value is not empty
                    if(!empty($value))
                    {
                        if(!empty($columns))
                            $columns .= ', ';
                        $columns .= $database->quoteColumn($key);

                        if(!empty($values))
                            $values .= ', ';
                        $values .=  $database->quote($value);
                    }
                }
            }

            $sql .= $columns;



            $sql .=')
                                      VALUES 
                                      (';


            $sql .= $values;

            $sql .=

                                      ');';

            if($database->query($sql))
            {
                $this->Id = $database->lastInsertedID();
                $database->close();

                return true;
            }
            $database->close();
            return false;

        }
        else
        {
            $sql = "UPDATE " . $this::$TABLE_NAME . " SET ";

            //gather all the columns and values to be used in the update statement
            $updates = '';
            foreach ($this as $key => $value)
            {
                //dont use Id in cols or vals
                if($key != 'Id' && property_exists($this, $key))
                {
                    if(!empty($updates))
                        $updates .= ', ';
                    $updates .= $database->quoteColumn($key) . ' = ' . ((empty($value)) ? 'NULL' : $database->quote($value));

                }
            }

            $sql .= $updates;

            $sql .= " WHERE (Id = " . $database->quote($this->Id) . ");";

            if($database->query($sql))
            {
                $database->close();
                return true;
            }

            $database->close();
            return false;
        }
    }

    abstract public function toString();
    abstract public function toHtml();

}

?>