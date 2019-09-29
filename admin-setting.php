<?php
require_once("config.php");

if (!getUser()->IsAdmin)
    header('Location: ' . URL_PATH);

require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");
require_once(ROOT_DIR . "/classes/tables/local/Config.php");

$yearId = $_GET['yearId'];
$year = ((empty($yearId)) ? null : Years::withId($yearId));

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
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<script>
    let datatypeToPlainTextArray = JSON.parse('<?php echo json_encode(DataTypes::DATATYPE_TO_PLAIN_TEXT_ARRAY); ?>');
    let plainTextToDataTypeArray = JSON.parse('<?php echo json_encode(DataTypes::PLAIN_TEXT_TO_DATATYPE_ARRAY); ?>');
</script>
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" style="min-width: 1200px !important;">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Application', '/admin-setting.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CONFIG, ($panel == AdminPanels::CONFIG || empty($panel)));
    $navBarLinksArray[] = new NavBarLink('Users', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::USERS, ($panel == AdminPanels::USERS));
    $navBarLinksArray[] = new NavBarLink('Robot Info', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::ROBOT_INFO_KEYS, ($panel == AdminPanels::ROBOT_INFO_KEYS));
    $navBarLinksArray[] = new NavBarLink('Scout Card Info', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::SCOUT_CARD_INFO_KEYS, ($panel == AdminPanels::SCOUT_CARD_INFO_KEYS));
    $navBarLinksArray[] = new NavBarLink('Checklist Info', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CHECKLIST_INFO, ($panel == AdminPanels::CHECKLIST_INFO));

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header('Admin Panel', null, $navBar, null, $year, 'admin.php?yearId=' . $year->Id);

    echo $header->toHtml();
    ?>

    <main class="mdl-layout__content">

        <div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>

        <script>
            var snackbarContainer = document.querySelector('#demo-toast-example');


            function showToast(message)
            {
                'use strict';
                var data = {message: message};
                snackbarContainer.MaterialSnackbar.showSnackbar(data);
            }
        </script>

        <dialog class="mdl-dialog" style="width: 500px;">
            <h3 class="mdl-dialog__title" style="font-size: 20px;">Delete Record?</h3>
            <div class="mdl-dialog__content">
                <p>
                    All records in the database will be deleted. This action cannot be undone.
                </p>
            </div>
            <div class="mdl-dialog__actions">
                <button id="dialog-confirm" type="button"
                        class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent confirm">
                    Delete
                </button>
                <button id="dialog-cancel" type="button" class="mdl-button">Cancel</button>
            </div>
        </dialog>

        <script>

            var dialog;

            $(document).ready(function ()
            {
                dialog = document.querySelector('dialog');
                if (!dialog.showModal)
                {
                    dialogPolyfill.registerDialog(dialog);
                }

                $('#dialog-cancel').click(function ()
                {
                    $('#dialog-confirm').unbind('click');
                    dialog.close();
                });
            });
        </script>

        <div class="mdl-layout__tab-panel is-active" id="overview" style="overflow: visible;">
            <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--2dp">
                <div class="mdl-card mdl-cell mdl-cell--12-col">

        <?php
        switch($panel)
        {
            case AdminPanels::CONFIG:

                ?>
                    <script src="<?php echo JS_URL ?>jscolor.js"></script>
                    <h4 style="padding-left: 40px;">Application Config</h4>
                    <div class="mdl-card__supporting-text">
                <?php
                    $obj = Config::getObjects(null, null, null,"Id", "ASC");

                    foreach ($obj as $config)
                    {
                        $titleText = str_replace("_", " ", $config->Key);
                        $titleText = strtolower($titleText);
                        $titleText = ucwords($titleText);

                        ?>
                            <strong class="setting-title"><?php echo $titleText ?></strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="<?php if(strpos(strtolower($titleText), "color")) echo "jscolor" ?> mdl-textfield__input" type="text" value="<?php echo $config->Value?>" name="<?php echo $config->Key ?>" id="<?php echo $config->Key ?>">
                            </div>

                        <?php
                    }
                    ?>
                    </div>

                     <div style="text-align: right">
                        <button style="width: 95px; margin: 24px;" onclick="saveRecord(-1)" class="center-div-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                    <?php
                break;

                case AdminPanels::USERS:
                    $obj = Users::withId($settingId);
                ?>
                    <h4 style="padding-left: 40px;">User Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <strong class="setting-title">First Name</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->FirstName ?>" name="FirstName" id="FirstName">
                        </div>
                        <strong class="setting-title">Last Name</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->LastName ?>" name="LastName" id="LastName">
                        </div>
                        <strong class="setting-title">Admin Account</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="IsAdmin">
                                <input type="checkbox" name="IsAdmin" id="IsAdmin" class="mdl-switch__input" <?php if($obj->IsAdmin == 1) echo "checked" ?>
                                <span class="mdl-switch__label"></span>
                            </label>
                        </div>
                        <div id="hideable-data" <?php echo $obj->IsAdmin == 1 ? "" : "hidden" ?>>
                            <strong class="setting-title">Username</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input autocomplete="new-password" class="mdl-textfield__input" type="text" value="<?php echo $obj->UserName ?>" name="UserName" id="UserName" <?php echo $obj->IsAdmin == 1 ? "" : "disabled" ?>>
                            </div>
                            <strong class="setting-title">Password</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input autocomplete="new-password" class="mdl-textfield__input" type="password" value="<?php if(strlen($obj->Password) > 0) echo "•••••••••••••••••" ?>" name="Password" id="Password" <?php echo $obj->IsAdmin == 1 ? "" : "disabled" ?>>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right">
                        <button style="width: 95px; margin: 24px;" onclick="saveRecord(<?php echo $obj->Id ?>)" class="center-div-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                <?php
                break;

                case AdminPanels::ROBOT_INFO_KEYS:
                    $obj = RobotInfoKeys::withId($settingId);
                ?>
                    <h4 style="padding-left: 40px;">Robot Info Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <strong class="setting-title">Year</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                        </div>
                        <strong class="setting-title">Game State</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyState ?>" name="KeyState" id="KeyState">
                        </div>
                        <strong class="setting-title">Info Name</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyName ?>" name="KeyName" id="KeyName">
                        </div>
                        <strong class="setting-title">Sort Order</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="number" value="<?php echo $obj->SortOrder ?>" name="SortOrder" id="SortOrder">
                        </div>
                    </div>

                    <div style="text-align: right">
                        <button style="width: 95px; margin: 24px;" onclick="saveRecord(<?php echo $obj->Id ?>)" class="center-div-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                        <?php
                    break;

                case AdminPanels::SCOUT_CARD_INFO_KEYS:
                    $obj = ScoutCardInfoKeys::withId($settingId);
                    ?>
                    <h4 style="padding-left: 40px;">Scout Card Info Settings</h4>
                    <div class="mdl-card__supporting-text">
                        <strong class="setting-title">Year</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                        </div>
                        <strong class="setting-title">Game State</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyState ?>" name="KeyState" id="KeyState">
                        </div>
                        <strong class="setting-title">Info Name</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="text" value="<?php echo $obj->KeyName ?>" name="KeyName" id="KeyName">
                        </div>
                        <strong class="setting-title">Sort Order</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input class="mdl-textfield__input" type="number" value="<?php echo $obj->SortOrder ?>" name="SortOrder" id="SortOrder">
                        </div>
                        <strong class="setting-title">Data Type</strong>
                        <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                            <input id="DataType" class="mdl-textfield__input mdl-js-button" type="text" value="<?php echo DataTypes::DATATYPE_TO_PLAIN_TEXT_ARRAY[$obj->DataType] ?>" name="DataType" id="DataType"/>
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
                            <strong class="setting-title">Minimum Value</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="mdl-textfield__input" type="number" value="<?php echo $obj->MinValue ?>" name="MinValue" id="MinValue" >
                            </div>
                        </div>
                        <div id="max-value-div" <?php echo (($obj->DataType != DataTypes::INT) ? "hidden" : "") ?>>
                            <strong class="setting-title">Maximum Value</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="mdl-textfield__input" type="number" value="<?php echo $obj->MaxValue ?>" name="MaxValue" id="MaxValue">
                            </div>
                        </div>
                        <div id="null-zero-div" <?php echo (($obj->DataType != DataTypes::INT) ? "hidden" : "") ?>>
                            <strong class="setting-title">Nullify Zeros</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="NullZeros">
                                    <input type="checkbox" name="NullZeros" id="NullZeros" class="mdl-switch__input" <?php if($obj->NullZeros == 1) echo "checked" ?>
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>
                        </div>
                        <div id="include-in-stats-div" <?php echo (($obj->DataType != DataTypes::INT && $obj->DataType != DataTypes::BOOL) ? "hidden" : "") ?>>
                            <strong class="setting-title">Include In Stats</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="IncludeInStats">
                                    <input type="checkbox" name="IncludeInStats" id="IncludeInStats" class="mdl-switch__input" <?php if($obj->IncludeInStats == 1) echo "checked" ?>
                                    <span class="mdl-switch__label"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: right">
                        <button onclick="deleteRecord(<?php echo $obj->Id ?>)" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                            <span class="button-text">Delete</span>
                        </button>
                        <button style="width: 95px; margin: 24px;" onclick="saveRecord(<?php echo $obj->Id ?>)" class="center-div-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                            <span class="button-text">Save</span>
                        </button>
                    </div>
                        <?php
                    break;

                    case AdminPanels::CHECKLIST_INFO:
                        $obj = ChecklistItems::withId($settingId);
                        ?>
                        <h4 style="padding-left: 40px;">Checlist Item Settings</h4>
                        <div class="mdl-card__supporting-text">
                            <strong class="setting-title">Year</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input disabled class="mdl-textfield__input" type="number" value="<?php echo ((!empty($obj->YearId)) ? $obj->YearId : $year->Id) ?>" name="YearId" id="YearId">
                            </div>
                            <strong class="setting-title">Title</strong>
                            <div class="setting-value mdl-textfield mdl-js-textfield mdl-textfield--floating-label" data-upgraded=",MaterialTextfield">
                                <input class="mdl-textfield__input" type="text" value="<?php echo $obj->Title ?>" name="Title" id="Title">
                            </div>
                            <strong class="setting-title">Description</strong>
                            <div class="mdl-textfield mdl-js-textfield">
                                <textarea class="mdl-textfield__input" type="text" rows= "4" id="Description" name="Description"><?php echo $obj->Description ?></textarea>
                                <label class="mdl-textfield__label" for="Description"></label>
                            </div>
                        </div>
                        <div style="text-align: right">
                            <button style="width: 95px; margin: 24px;" onclick="saveRecord(<?php echo $obj->Id ?>)" class="center-div-inner mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent">
                                <span class="button-text">Save</span>
                            </button>
                        </div>
                    <?php
                    break;
        }

        ?>
                </div>
            </section>
        </div>
        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script>

    var href = "<?php echo URL_PATH . "/admin.php?yearId" . $yearId . "&adminPanel=" . $panel ?>"; //get the path to return to once saving is complete

    /**
     * Saves record to the database
     * @param id int to use when saving, if -1 it will create a new record
     */
    function saveRecord(id)
    {
        var data;

        //assign the data based on what panel we are viewing
        <?php
        switch ($panel)
        {
            case AdminPanels::CONFIG:
            ?>
            data =
                {
                    AppName: $('#APP_NAME').val(),
                    ApiKey: $('#API_KEY').val(),
                    PrimaryColor: $('#PRIMARY_COLOR').val(),
                    PrimaryColorDark: $('#PRIMARY_COLOR_DARK').val()
                };
            <?php
            break;

            case AdminPanels::USERS:
            ?>
            data =
                {
                    Id: id,
                    FirstName: $('#FirstName').val(),
                    LastName: $('#LastName').val(),
                    UserName: $('#UserName').val(),
                    Password: $('#Password').val(),
                    IsAdmin: $('#IsAdmin').prop("checked") ? "1" : "0"
                };
            <?php
            break;

            case AdminPanels::ROBOT_INFO_KEYS:
            ?>
            data =
                {
                    Id: id,
                    YearId: $('#YearId').val(),
                    KeyState: $('#KeyState').val(),
                    KeyName: $('#KeyName').val(),
                    SortOrder: $('#SortOrder').val()
                };
            <?php
            break;

            case AdminPanels::SCOUT_CARD_INFO_KEYS:
            ?>
            data =
                {
                    Id: id,
                    YearId: $('#YearId').val(),
                    KeyState: $('#KeyState').val(),
                    KeyName: $('#KeyName').val(),
                    SortOrder: $('#SortOrder').val(),
                    MinValue: $('#MinValue').val(),
                    MaxValue: $('#MaxValue').val(),
                    NullZeros: $('#NullZeros').prop("checked") ? "1" : "0",
                    IncludeInStats: $('#IncludeInStats').prop("checked") ? "1" : "0",
                    DataType: plainTextToDataTypeArray[$('#DataType').val()]
                };
            <?php
            break;

            case AdminPanels::CHECKLIST_INFO:
            ?>
            data =
                {
                    Id: id,
                    YearId: $('#YearId').val(),
                    Title: $('#Title').val(),
                    Description: $('#Description').val()
                };
            <?php
            break;
        }
        ?>

        //call the admin ajax script to modify the records in the database
        $.post('/ajax/admin.php',
            {
                action: 'save',
                class: '<?php echo $panel ?>',
                data: data
            },
            function (data)
            {
                data = JSON.parse(data);

                //check success status code
                if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>' && id === undefined)
                    location.href = href;


                //display response to screen
                showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
            });
    }

    /**
     * Deletes record from database
     * @param recordId int id of record to delete
     */
    function deleteRecord(recordId)
    {
        //update classes, onclick button and text for the edit button
        $("#dialog-confirm")
            .unbind('click')
            .click(function ()
            {
                //call the admin ajax script to modify the records in the database
                $.post('/ajax/admin.php',
                    {
                        action: 'delete',
                        class: '<?php echo $panel ?>',
                        recordId: recordId
                    },
                    function (data)
                    {
                        data = JSON.parse(data);

                        //check success status code
                        if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                            location.href = href;

                        //display response to screen
                        showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
                    });

                dialog.close();
            });

        dialog.showModal();
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
