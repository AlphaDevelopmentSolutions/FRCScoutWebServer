<?php

class RobotMedia extends LocalTable
{
    public $Id;
    public $YearId;
    public $EventId;
    public $TeamId;
    public $FileURI;

    public static $TABLE_NAME = 'robot_media';

    /**
     * Retrieves objects from the database
     * @param Years | null $year if specified, filters by id
     * @param Events | null $event if specified, filters by id
     * @param Teams | null $team if specified, filters by id
     * @param boolean $asNormalArray if true, uses [array] instead of [ScoutCardInfoArray]
     * @return ScoutCardInfoArray | ScoutCardInfo[]
     */
    public static function getObjects($year = null, $event = null, $team = null)
    {
        $whereStatment = "";
        $cols = array();
        $args = array();

        //if year specified, filter by year
        if(!empty($year))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'YearId';
            $args[] = $year->Id;
        }

        //if event specified, filter by event
        if(!empty($event))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'EventId';
            $args[] = $event->BlueAllianceId;
        }

        //if team specified, filter by team
        if(!empty($team))
        {
            $whereStatment .= ((empty($whereStatment)) ? "" : " AND ") . " ! = ? ";
            $cols[] = 'TeamId';
            $args[] = $team->Id;
        }

        return parent::getObjects($whereStatment, $cols, $args);
    }

    /**
     * Overrides parent::save() method
     * Attempts to save the image before saving the record
     * @param bool $bypassFileSave bypasses the file save to the system
     * @return bool
     */
    public function save($bypassFileSave = false)
    {
        if(empty($this->Id))
        {
            if($bypassFileSave)
                return parent::save();

            else if($this->saveImage())
                return parent::save();

            return false;
        }

        return parent::save();
    }


    /**
     * Overrides parent::delete() method
     * Attempts to delete the image before deleting the record
     * @return bool
     */
    function delete()
    {
        if($this->deleteImage())
            return parent::delete();

        return false;
    }

    /**
     * Saves the specified robot media base64image to the file system
     * @return boolean
     */
    private function saveImage()
    {
        mt_srand(crc32(serialize([microtime(true), $_SERVER['HTTP_CLIENT_IP'], $_SERVER['HTTP_USER_AGENT']])));

        //create a unique id
        $uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0fff ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ));

        //make sure the file doesn't already exist
        while(file_exists(ROBOT_MEDIA_DIR . $uuid . '.jpeg'))
            $uuid = sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ));

        $fileName = $uuid . '.jpeg';

        $success = file_put_contents(ROBOT_MEDIA_DIR . $fileName, base64_decode($this->FileURI)) != false;

        //if successful, store the file URI
        if($success)
        {
            $this->FileURI = $fileName;

            $image = imagecreatefromjpeg(ROBOT_MEDIA_DIR . $fileName);

            $width = getimagesize(ROBOT_MEDIA_DIR . $fileName);
            $height = $width[1];
            $width = $width[0];

            $ratio = $width / $height;
            $targetWidth = 250;
            $targetHeight = $targetWidth;
            $targetWidth = floor($targetWidth * $ratio);

            $thumb = imagecreatetruecolor($targetWidth, $targetHeight);

            imagecopyresampled(
                $thumb,
                $image,
                0, 0, 0, 0,
                $targetWidth, $targetHeight,
                $width, $height
            );

            $success = imagejpeg($thumb, ROBOT_MEDIA_THUMBS_DIR . $fileName);
        }

        return $success;
    }

    /**
     * Deletes the saved robot image from the file system
     * @return boolean
     */
    private function deleteImage()
    {
        //prep the file to be deleted
        $file = fopen(ROBOT_MEDIA_DIR . "$this->FileURI", 'wb');

        //store if delete was successful
        $success = unlink($file);

        fclose($file);

        if($success)
        {
            //prep the file to be deleted
            $file = fopen(ROBOT_MEDIA_THUMBS_DIR . "$this->FileURI", 'wb');

            //store if delete was successful
            $success = unlink($file);

            fclose($file);

            return $success;
        }

        return false;
    }

    /**
     * Prints the object once converted into HTML
     */
    public function toHtml()
    {
        ?>
            <div class="mdl-layout__tab-panel is-active" id="overview">
                <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                    <div class="mdl-card mdl-cell mdl-cell--12-col">
                        <div class="mdl-card__supporting-text">
                            <img class="robot-media" src="<?php echo ROBOT_MEDIA_URL . $this->FileURI ?>"  height="350"/>
                        </div>
                        <div class="mdl-card__actions">
                            <a target="_blank" href="<?php echo ROBOT_MEDIA_URL . $this->FileURI ?>" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                <span class="button-text">View</span>
                            </a>
                            <button onclick="deleteRecord('<?php echo self::class ?>', <?php echo $this->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                <span class="button-text">Delete</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
    <?php
    }

    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->TeamId . ' Robot Media';
    }
}
?>