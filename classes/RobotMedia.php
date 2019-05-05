<?php

class RobotMedia extends Table
{
    public $Id;
    public $TeamId;
    public $FileURI;
    public $Base64Image;

    protected static $TABLE_NAME = 'robot_media';

    /**
     * Overrides parent::save() method
     * Attempts to save the image before saving the record
     * @return bool
     */
    public function save()
    {
        if(!empty($this->Id))
            if($this->saveImage())
                return parent::save();

        return false;
    }

    /**
     * Saves the specified robot media base64image to the file system
     * @return boolean
     */
    private function saveImage()
    {
        //create a unique id
        $uid = uniqid();

        //make sure the file doesn't already exist
        while(file_exists($uid . '.jpeg'))
            $uid = uniqid();

        //decode the base64 image
        $image = base64_decode($this->Base64Image);

        //prep the file to be written to
        $file = fopen("../assets/robot-media/$uid.jpeg", 'wb');

        //store if write was successful
        $success = fwrite($file, $image);

        fclose($file);

        //if successful, store the file URI
        if($success)
            $this->FileURI = $uid . '.jpeg';

        return $success;
    }

    /**
     * Deletes the saved robot image from the file system
     * @return boolean
     */
    private function deleteImage()
    {
        //prep the file to be deleted
        $file = fopen("../assets/robot-media/$this->FileURI", 'wb');

        //store if delete was successful
        $success = unlink($file);

        fclose($file);

        return $success;
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