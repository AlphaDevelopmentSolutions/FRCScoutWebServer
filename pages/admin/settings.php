<?php
require_once("../../config.php");

if (!getUser()->IsAdmin)
    redirect(URL_PATH);

require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");
require_once(ROOT_DIR . "/classes/tables/local/Config.php");

$yearId = $_GET['yearId'];
$year = ((empty($yearId)) ? null : Years::withId($coreDb, $yearId));

$panel = $_GET['adminPanel'];
$settingId = $_GET['settingId'];

interface AdminPanels
{
    const ROBOT_INFO_KEYS = RobotInfoKeys::class;
    const SCOUT_CARD_INFO_KEYS = ScoutCardInfoKeys::class;
    const CHECKLIST_INFO = ChecklistItems::class;
    const CONFIG = Config::class;
    const USERS = Users::class;
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Admin Page</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<script>
    let datatypeToPlainTextArray = JSON.parse('<?php echo json_encode(DataTypes::DATATYPE_TO_PLAIN_TEXT_ARRAY); ?>');
    let plainTextToDataTypeArray = JSON.parse('<?php echo json_encode(DataTypes::PLAIN_TEXT_TO_DATATYPE_ARRAY); ?>');
</script>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" style="min-width: 1200px !important;">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Application', ADMIN_URL . 'settings?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CONFIG, ($panel == AdminPanels::CONFIG || empty($panel)));
    $navBarLinksArray[] = new NavBarLink('Users', ADMIN_URL . 'list?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::USERS, ($panel == AdminPanels::USERS));
    $navBarLinksArray[] = new NavBarLink('Robot Info', ADMIN_URL . 'list?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::ROBOT_INFO_KEYS, ($panel == AdminPanels::ROBOT_INFO_KEYS));
    $navBarLinksArray[] = new NavBarLink('Scout Card Info', ADMIN_URL . 'list?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::SCOUT_CARD_INFO_KEYS, ($panel == AdminPanels::SCOUT_CARD_INFO_KEYS));
    $navBarLinksArray[] = new NavBarLink('Checklist Info', ADMIN_URL . 'list?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CHECKLIST_INFO, ($panel == AdminPanels::CHECKLIST_INFO));

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header('Admin Panel', null, $navBar, null, $year, ADMIN_URL . 'list?yearId=' . $year->Id);

    echo $header->toHtml();
    ?>

    <main class="mdl-layout__content">

        <?php require_once(INCLUDES_DIR . 'modals.php'); ?>

        <div class="mdl-layout__tab-panel is-active" id="overview" style="overflow: visible;">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">

        <?php
        switch($panel)
        {
            case AdminPanels::CONFIG:

                ?>
                    <script src="<?php echo JS_URL ?>jscolor.js"></script>
                    <h4 style="padding: 0 40px;">Application Config</h4>
                    <div class="mdl-card__supporting-text">
                <?php
                    $objs = Config::getObjects($localDb, null, null, null,"Id", "ASC");
                    $obj = new Config();

                    $i = 0;
                    foreach ($objs as $config)
                    {
                        $i++;
                        $titleText = str_replace("_", " ", $config->Key);
                        $titleText = strtolower($titleText);
                        $titleText = ucwords($titleText);

                        ?>
                        <span class="setting-title">
                            <strong><?php echo $titleText ?></strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc<?php echo $i ?>"
                                     class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc<?php echo $i ?>">
                                <?php

                                switch ($config->Key)
                                {
                                    case "APP_NAME":
                                        ?>
                                        The name of your teams app.
                                        <?php
                                        break;

                                    case "PRIMARY_COLOR":
                                        ?>
                                        Primary color for the web and mobile application.
                                        <?php
                                        break;

                                    case "PRIMARY_COLOR_DARK":
                                        ?>
                                        Darker primary color, usually a color accent, for the web and mobile application.
                                        <?php
                                        break;
                                }
                                ?>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label"
                             data-upgraded=",MaterialTextfield">
                            <input maxlength="3000" class="<?php if (strpos(strtolower($titleText), "color")) echo "jscolor" ?> mdl-textfield__input"
                                   type="text" value="<?php echo $config->Value ?>"
                                   name="<?php echo $config->Key ?>" id="<?php echo $config->Key ?>">
                        </div>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                break;

                case AdminPanels::USERS:
                    $obj = Users::withId($localDb, $settingId);
                ?>
                    <h4 style="padding: 0 40px;">User Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <span class="setting-title">
                            <strong>First Name</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc1" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc1">
                                First name of the user.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">

                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->FirstName ?>" name="FirstName" id="FirstName">
                        </div>

                        <span class="setting-title">
                            <strong>Last Name</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc2" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc2">
                                Last name of the user.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->LastName ?>" name="LastName" id="LastName">
                        </div>

                        <span class="setting-title">
                            <strong>Admin Account</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc3" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc3">
                                Does the user have admin privileges?
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="IsAdmin">
                                <input type="checkbox" name="IsAdmin" id="IsAdmin" class="mdl-switch__input" <?php if($obj->IsAdmin == 1) echo "checked" ?>>
                                <span class="mdl-switch__label"></span>
                            </label>
                        </div>

                        <div id="hideable-data" <?php echo $obj->IsAdmin == 1 ? "" : "hidden" ?>>
                           <span class="setting-title">
                                <strong>Username</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc4" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc4">
                                    Username to log into the admin system.
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input maxlength="45" autocomplete="new-password" class="mdl-textfield__input" type="text" value="<?php echo $obj->UserName ?>" name="UserName" id="UserName" <?php echo $obj->IsAdmin == 1 ? "" : "disabled" ?>>
                            </div>

                            <span class="setting-title">
                                <strong>Password</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc5" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc5">
                                    Password to log into the admin system.
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input maxlength="200" autocomplete="new-password" class="mdl-textfield__input" type="password" value="<?php if(strlen($obj->Password) > 0) echo "•••••••••••••••••" ?>" name="Password" id="Password" <?php echo $obj->IsAdmin == 1 ? "" : "disabled" ?>>
                            </div>
                        </div>
                    </div>
                <?php
                break;

                case AdminPanels::ROBOT_INFO_KEYS:
                    $obj = RobotInfoKeys::withId($localDb, $settingId);
                ?>
                    <h4 style="padding: 0 40px;">Robot Info Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <span class="setting-title">
                            <strong>Year</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc1" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc1">
                                The year this robot info key is stored under.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                        </div>

                        <span class="setting-title">
                            <strong>Game State</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc2" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc2">
                                The state of the game to gather information for.
                                <br>
                                <br>
                                Examples:
                                <ul>
                                    <li>Pre Game</li>
                                    <li>Autonomous</li>
                                    <li>Teleop</li>
                                    <li>End Game</li>
                                    <li>Post Game</li>
                                </ul>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyState ?>" name="KeyState" id="KeyState">
                        </div>

                        <span class="setting-title">
                            <strong>Info Name</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc3" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc3">
                                Information you will be gathering.
                                <br>
                                <br>
                                Examples:
                                <ul>
                                    <li>Drivetrain</li>
                                    <li>Weight</li>
                                    <li>Width</li>
                                    <li>Height</li>
                                    <li>Climb Time</li>
                                    <li>Notes</li>
                                </ul>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyName ?>" name="KeyName" id="KeyName">
                        </div>

                        <span class="setting-title">
                            <strong>Sort Order</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc4" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc4">
                                Order number when sorting it into a list.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="number" value="<?php echo $obj->SortOrder ?>" name="SortOrder" id="SortOrder">
                        </div>
                    </div>
                        <?php
                    break;

                case AdminPanels::SCOUT_CARD_INFO_KEYS:
                    $obj = ScoutCardInfoKeys::withId($localDb, $settingId);
                    ?>
                    <h4 style="padding: 0 40px;">Scout Card Info Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <span class="setting-title">
                            <strong>Year</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc1" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc1">
                                The year this robot info key is stored under.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                        </div>

                        <span class="setting-title">
                            <strong>Game State</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc2" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc2">
                                The state of the game to gather information for.
                                <br>
                                <br>
                                Examples:
                                <ul>
                                    <li>Pre Game</li>
                                    <li>Autonomous</li>
                                    <li>Teleop</li>
                                    <li>End Game</li>
                                    <li>Post Game</li>
                                </ul>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyState ?>" name="KeyState" id="KeyState">
                        </div>

                        <span class="setting-title">
                            <strong>Info Name</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc3" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc3">
                                Information you will be gathering.
                                <br>
                                <br>
                                Examples:
                                <ul>
                                    <li>Drivetrain</li>
                                    <li>Weight</li>
                                    <li>Width</li>
                                    <li>Height</li>
                                    <li>Climb Time</li>
                                    <li>Notes</li>
                                </ul>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input maxlength="45" class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyName ?>" name="KeyName" id="KeyName">
                        </div>

                        <span class="setting-title">
                            <strong>Sort Order</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc4" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc4">
                                Order number when sorting it into a list.
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="number" value="<?php echo $obj->SortOrder ?>" name="SortOrder" id="SortOrder">
                        </div>

                        <span class="setting-title">
                            <strong>Data Type</strong>
                            <span class="center-div-vertical-outer">
                                <div id="desc5" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                            </span>
                            <div class="mdl-tooltip mdl-tooltip--large" for="desc5">
                                Type of data being collected.
                                <br>
                                <br>
                                <i>Note: <?php echo DataTypes::TEXT_PLAIN_TEXT ?> data types can't be used when displaying statistics.</i>
                            </div>
                        </span>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input readonly maxlength="4" id="DataType" class="mdl-textfield__input mdl-js-button" type="text" value="<?php echo DataTypes::DATATYPE_TO_PLAIN_TEXT_ARRAY[$obj->DataType] ?>" name="DataType" id="DataType"/>
                            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="DataType">
                                <?php
                                foreach (DataTypes::DATATYPE_TO_PLAIN_TEXT_ARRAY as $key => $value)
                                {
                                    ?>
                                        <li class="mdl-menu__item datatype-menu-item" value="<?php echo $key ?>"><?php echo $value ?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <div id="min-value-div" <?php echo (($obj->DataType != DataTypes::INT) ? "hidden" : "") ?>>
                            <span class="setting-title">
                                <strong>Minimum Value</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc6" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc6">
                                    Minimum value when entering data.
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="mdl-textfield__input" type="number" value="<?php echo $obj->MinValue ?>" name="MinValue" id="MinValue" >
                            </div>
                        </div>
                        <div id="max-value-div" <?php echo (($obj->DataType != DataTypes::INT) ? "hidden" : "") ?>>
                            <span class="setting-title">
                                <strong>Maximum Value</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc7" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc7">
                                    Maximum value when entering data.
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="mdl-textfield__input" type="number" value="<?php echo $obj->MaxValue ?>" name="MaxValue" id="MaxValue">
                            </div>
                        </div>
                        <div id="null-zero-div" <?php echo (($obj->DataType != DataTypes::INT) ? "hidden" : "") ?>>
                            <span class="setting-title">
                                <strong>Nullify Zeros</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc8" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc8">
                                    Should zero (0) values be ignored when calculating statistics?
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="NullZeros">
                                    <input type="checkbox" name="NullZeros" id="NullZeros" class="mdl-switch__input" <?php if($obj->NullZeros == 1) echo "checked" ?>
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>
                        </div>
                        <div id="include-in-stats-div" <?php echo (($obj->DataType != DataTypes::INT && $obj->DataType != DataTypes::BOOL) ? "hidden" : "") ?>>
                            <span class="setting-title">
                                <strong>Include In Stats</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc9" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc9">
                                    Should this info be included in statistics?
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="IncludeInStats">
                                    <input type="checkbox" name="IncludeInStats" id="IncludeInStats" class="mdl-switch__input" <?php if($obj->IncludeInStats == 1) echo "checked" ?>
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                        <?php
                    break;

                    case AdminPanels::CHECKLIST_INFO:
                        $obj = ChecklistItems::withId($localDb, $settingId);
                        ?>
                        <h4 style="padding: 0 40px;">Checlist Item Settings</h4>
                        <div class="mdl-card__supporting-text">
                            <span class="setting-title">
                                <strong>Year</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc1" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc1">
                                    The year this robot info key is stored under
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                            </div>

                            <span class="setting-title">
                                <strong>Title</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc2" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc2">
                                    Title of the checklist item.
                                </div>
                            </span>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input maxlength="3000" class="mdl-textfield__input" type="text" value="<?php echo $obj->Title ?>" name="Title" id="Title">
                            </div>

                            <span class="setting-title">
                                <strong>Description</strong>
                                <span class="center-div-vertical-outer">
                                    <div id="desc3" class="center-div-vertical-inner material-side-padding icon material-icons">help_outline</div>
                                </span>
                                <div class="mdl-tooltip mdl-tooltip--large" for="desc3">
                                    Brief description of what should be done when completing this item.
                                </div>
                            </span>
                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea maxlength="3000" class="mdl-textfield__input" type="text" rows= "4" id="Description" name="Description"><?php echo $obj->Description ?></textarea>
                                <label class="mdl-textfield__label" for="Description"></label>
                            </div>
                        </div>
                    <?php
                    break;
        }
        ?>
                    <div class="card-buttons">
                        <?php
                        if(get_class($obj) != Config::class)
                        {
                            ?>
                            <button onclick="deleteRecord('<?php echo get_class($obj); ?>', <?php echo $obj->Id ?>)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect">
                                <span class="button-text">Delete</span>
                            </button>
                            <?php
                        }
                        ?>
                        <button onclick="saveRecord('<?php echo get_class($obj); ?>', <?php echo $obj->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                </div>
            </section>
        </div>
        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<script src="<?php echo JS_URL ?>modify-record.js.php"></script>
<script>

    function saveSuccessCallback(message)
    {
        <?php
        if(empty($obj->Id) || $obj->Id == -1)
        {
        ?>
        location.href = '<?php echo ADMIN_URL . 'list?yearId=' . $year->Id . '&adminPanel=' . $panel ?>';
        <?php
        }
        else
        {
        ?>
        //display response to screen
        showToast(message);
        <?php
        }
        ?>
    }

    function saveFailCallback(message)
    {
        //display response to screen
        showToast(message);
    }

    function deleteSuccessCallback(message)
    {
        location.href = "<?php echo ADMIN_URL . "?yearId" . $yearId . "&adminPanel=" . $panel ?>";
    }

    function deleteFailCallback(message)
    {
        //display response to screen
        showToast(message);
    }

    <?php if($panel == AdminPanels::USERS)
    {
    ?>
    $(document).ready(function ()
    {
        $("#IsAdmin").change(function ()
        {
            if (this.checked)
            {
                $("#hideable-data").removeAttr("hidden");
                $("#UserName").removeAttr("disabled");
                $("#Password").removeAttr("disabled");
            }

            else
            {
                $("#hideable-data").attr("hidden", "hidden");
                $("#UserName").attr("disabled", "disabled");
                $("#Password").attr("disabled", "disabled");
            }
        });
    });
    <?php
    }
    ?>

    <?php if($panel == AdminPanels::SCOUT_CARD_INFO_KEYS)
    {
    ?>
    $(document).ready(function ()
    {
        $(".datatype-menu-item").click(function ()
        {
            var value = $(this).attr("value");

            $("#DataType").attr("value", datatypeToPlainTextArray[value]);

            if (value == "<?php echo DataTypes::TEXT ?>")
            {
                $("#MinValue").attr("disabled", "disabled");
                $("#min-value-div").attr("hidden", "hidden");
                $("#MaxValue").attr("disabled", "disabled");
                $("#max-value-div").attr("hidden", "hidden");
                $("#NullZeros").attr("disabled", "disabled");
                $("#null-zero-div").attr("hidden", "hidden");
                $("#IncludeInStats").attr("disabled", "disabled");
                $("#include-in-stats-div").attr("hidden", "hidden");
            }

            else if (value == "<?php echo DataTypes::BOOL ?>")
            {
                $("#MinValue").attr("disabled", "disabled");
                $("#min-value-div").attr("hidden", "hidden");
                $("#MaxValue").attr("disabled", "disabled");
                $("#max-value-div").attr("hidden", "hidden");
                $("#NullZeros").attr("disabled", "disabled");
                $("#null-zero-div").attr("hidden", "hidden");
                $("#IncludeInStats").removeAttr("disabled");
                $("#include-in-stats-div").removeAttr("hidden");
            }

            else if (value == "<?php echo DataTypes::INT ?>")
            {
                $("#MinValue").removeAttr("disabled");
                $("#min-value-div").removeAttr("hidden");
                $("#MaxValue").removeAttr("disabled");
                $("#max-value-div").removeAttr("hidden");
                $("#NullZeros").removeAttr("disabled");
                $("#null-zero-div").removeAttr("hidden");
                $("#IncludeInStats").removeAttr("disabled");
                $("#include-in-stats-div").removeAttr("hidden");
            }
        });
    });
    <?php
    }
    ?>
</script>
</body>
</html>
