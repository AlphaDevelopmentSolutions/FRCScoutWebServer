<?php

class RobotMedia
{
    public $Id;
    public $TeamId;
    public $FileName;
    public $Base64Image;

    private static $TABLE_NAME = 'robot_media';

    function load($id)
    {
        $database = new Database();
        $sql = 'SELECT * FROM '.$this::$TABLE_NAME.' WHERE '.'id = '.$database->quote($id);
        $rs = $database->query($sql);

        if($rs && $rs->num_rows > 0) {
            $row = $rs->fetch_assoc();

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

    function save()
    {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if($this->saveImage($this->Base64Image))
        {
            $database = new Database();

            if (empty($this->Id)) {
                $sql = 'INSERT INTO ' . self::$TABLE_NAME . '
                                      (
                                      TeamId,
                                      FileName
                                      )
                                      VALUES
                                      (
                                      ' . ((empty($this->TeamId)) ? 'NULL' : $database->quote($this->TeamId)) . ',
                                      ' . ((empty($this->FileName)) ? 'NULL' : $database->quote($this->FileName)) . '
                                      );';

                if ($database->query($sql)) {
                    $this->Id = $database->lastInsertedID();
                    $database->close();

                    return true;
                }
                $database->close();
                return false;

            } else {
                $sql = "UPDATE " . self::$TABLE_NAME . " SET
            TeamId = " . ((empty($this->TeamId)) ? "NULL" : $database->quote($this->TeamId)) . ",
            FileName = " . ((empty($this->FileName)) ? "NULL" : $database->quote($this->FileName)) . "
            WHERE (Id = " . $database->quote($this->Id) . ");";

                if ($database->query($sql)) {
                    $database->close();
                    return true;
                }

                $database->close();
                return false;
            }
        }
    }

    /**
     * Saves a base64 encoded image to the server
     * @param $base64Img
     * @return bool
     */
    private function saveImage($base64Img)
    {

        $uid = uniqid();

        //make sure the file doesn't exist
        while(file_exists($uid . '.png'))
            $uid = uniqid();

        $image = base64_decode($base64Img);

        $file = fopen("../assets/robot-media/$uid.png", 'wb');

        $success = fwrite($file, $image);

        fclose($file);

        return $success;

    }

    function delete()
    {
        if(empty($this->Id))
            return false;

        $database = new Database();
        $sql = 'DELETE FROM '.self::$TABLE_NAME.' WHERE '.'id = '.$database->quote($this->Id);
        $rs = $database->query($sql);

        if($rs)
            return true;


        return false;
    }

    /**
     * Returns the base 64 encoded image
     * @param $teamId
     * @return array
     */
    public static function getRobotMediaForTeam($teamId)
    {
        $database = new Database();
        $robotMedia = $database->query(
            "SELECT 
                      * 
                    FROM 
                      " . self::$TABLE_NAME . "  
                    WHERE 
                      TeamId = " . $database->quote($teamId)
        );
        $database->close();

        $response = array();

        if($robotMedia && $robotMedia->num_rows > 0)
        {
            while ($row = $robotMedia->fetch_assoc())
            {
                $file = fopen("../assets/robot-media/" . $row['ImageURI'] . ".png", 'r');
                $row['Base64Image'] = base64_encode($file);
                $response[] = $row;
            }
        }

        return $response;
    }


}

?>