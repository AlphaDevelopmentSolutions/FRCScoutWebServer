<?php

class Years extends Table
{
    public $Id;
    public $Name;
    public $StartDate;
    public $EndDate;
    public $ImageUri;

    public static $TABLE_NAME = 'years';

    /**
     * Gets all events within this year
     * @return Events[]
     */
    public function getEvents()
    {
        require_once(ROOT_DIR . '/classes/tables/Events.php');

        $response = array();

        //create the sql statement
        $sql = "SELECT * FROM ! WHERE ! = ? ORDER BY ! DESC";
        $cols[] = Events::$TABLE_NAME;
        $cols[] = 'YearId';
        $args[] = $this->Id;
        $cols[] = 'StartDate';

        $rows = self::queryRecords($sql, $cols, $args);

        foreach ($rows as $row)
            $response[] = Events::withProperties($row);

        return $response;
    }

    /**
     * Returns the object once converted into HTML
     * @return string
     */
    public function toHtml()
    {

        $html =
            '<section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp team-card">
                <header class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--white mdl-color-text--white">';

            $html .=
                    '<div style="height: unset">' .
                        '<div class="team-card-image" style="background-image: url(' . ((empty($this->ImageUri)) ? ROBOT_MEDIA_URL . 'frc_logo.jpg' : YEAR_MEDIA_URL . $this->ImageUri) . ')"></div>' .
                    '</div>';

            $html .=
                '</header>
                    <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
                        <div class="mdl-card__supporting-text">
                            <h4>' . $this->toString() . '</h4>
                            ' . date('F j, Y', strtotime($this->StartDate)) . ' - ' . date('F j, Y', strtotime($this->EndDate)) .
                        '</div>
                        <div class="mdl-card__actions">
                            <a href="/event-list.php?yearId=' . $this->Id . '" class="mdl-button">View</a>
                        </div>
                    </div>
                </section>';

            return $html;
    }


    /**
     * Compiles the name of the object when displayed as a string
     * @return string
     */
    public function toString()
    {
        return $this->Id . ' - ' . $this->Name;
    }
}

?>