<?php

class RobotMedia extends Table
{
    public $Id;
    public $TeamId;
    public $FileURI;
    public $Base64Image;

    protected static $TABLE_NAME = 'robot_media';

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