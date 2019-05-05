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
     * Returns the html for displaying a robot media
     * @return string html to display
     */
    public function toHtml()
    {
        $html =
            '<div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <img class="robot-media" src="' . ROBOT_MEDIA_URL . $this->FileURI . '"  height="350"/>
                        </div>
                        <div class="mdl-card__actions">
                            <a target="_blank" href="' . ROBOT_MEDIA_URL . $this->FileURI . '" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>
            </div>';

        return $html;
    }

    public function toString()
    {
        // TODO: Implement toString() method.
    }


}

?>