<?php

class ChecklistItems extends Table
{
    public $Id;
    public $Title;
    public $Description;

    protected static $TABLE_NAME = 'checklist_items';

    /**
     * Gets all created checklist items
     * @return array
     */
    public static function getChecklistItems()
    {
        $database = new Database();
        $checklistItems = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME);
        $database->close();

        $response = array();

        if($checklistItems && $checklistItems->num_rows > 0)
        {
            while ($row = $checklistItems->fetch_assoc())
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