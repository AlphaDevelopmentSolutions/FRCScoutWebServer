<?php

class Events extends Table
{
    public $Id;
    public $BlueAllianceId;
    public $Name;
    public $City;
    public $StateProvince;
    public $Country;
    public $StartDate;
    public $EndDate;

    protected static $TABLE_NAME = 'events';

    /**
     * Overrides parent withId method and provides a custom column name to use when loading
     * @param int|string $id
     * @return mixed|Table
     */
    public static function withId($id)
    {
        return parent::withId($id, 'BlueAllianceId');
    }

    public static function getEvents()
    {
        $database = new Database();
        $events = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME .
                    " ORDER BY StartDate DESC "
        );
        $database->close();

        $response = array();

        if($events && $events->num_rows > 0)
        {
            while ($row = $events->fetch_assoc())
            {
                $response[] = $row;
            }
        }

        return $response;
    }

    public function toHtml()
    {
        // TODO: Implement toHtml() method.
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }

}

?>