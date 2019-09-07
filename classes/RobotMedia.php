<?php

class RobotMedia
{
    public $Id;
    public $TeamId;
    public $FileURI;
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

        if($this->saveImage($this->Base64Image) > 0)
        {
            $database = new Database();

            if (empty($this->Id))
            {
                $sql = 'INSERT INTO ' . self::$TABLE_NAME . '
                                      (
                                      TeamId,
                                      FileURI
                                      )
                                      VALUES
                                      (
                                      ' . ((empty($this->TeamId)) ? '0' : $database->quote($this->TeamId)) . ',
                                      ' . ((empty($this->FileURI)) ? 'NULL' : $database->quote($this->FileURI)) . '
                                      );';

                if ($database->query($sql)) {
                    $this->Id = $database->lastInsertedID();
                    $database->close();

                    return true;
                }
                $database->close();
                return false;

            }
            else
                {
                $sql = "UPDATE " . self::$TABLE_NAME . " SET
            TeamId = " . ((empty($this->TeamId)) ? "0" : $database->quote($this->TeamId)) . ",
            FileURI = " . ((empty($this->FileURI)) ? "NULL" : $database->quote($this->FileURI)) . "
            WHERE (Id = " . $database->quote($this->Id) . ");";

                if ($database->query($sql)) {
                    $database->close();
                    return true;
                }

                $database->close();
                return false;
            }
        }

        return false;
    }

    /**
     * Saves a base64 encoded image to the server
     * @param $base64Img
     * @return bool if error | | int of bytes written
     */
    private function saveImage($base64Img)
    {

        $uid = uniqid();

        //make sure the file doesn't exist
        while(file_exists($uid . '.jpeg'))
            $uid = uniqid();

        $image = base64_decode($base64Img);

        $file = fopen("../assets/robot-media/$uid.jpeg", 'wb');

        $success = fwrite($file, $image);

        fclose($file);

        if($success)
            $this->FileURI = $uid . '.jpeg';

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
//                $file = fopen("../assets/robot-media/" . $row['ImageURI'] . ".png", 'r');
//                $row['Base64Image'] = base64_encode($file);
                $response[] = $row;
            }
        }

        return $response;
    }


}

?>