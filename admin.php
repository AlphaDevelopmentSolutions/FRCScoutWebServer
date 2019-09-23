<?php
require_once("config.php");

if (!getUser()->IsAdmin)
    header('Location: ' . URL_PATH);

require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/Config.php");
require_once(ROOT_DIR . "/classes/tables/local/Users.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");

$yearId = $_GET['yearId'];

$year = ((empty($yearId)) ? null : Years::withId($yearId));

$panel = $_GET['adminPanel'];

interface AdminPanels
{
    const ROBOT_INFO = RobotInfoKeys::class;
    const SCOUT_CARD_INFO = ScoutCardInfoKeys::class;
    const CHECKLIST_INFO = ChecklistItems::class;
    const CONFIG = Config::class;
    const USERS = Users::class;
}

if(empty($panel) || empty($yearId))
    header('Location: ' . URL_PATH . "/admin-setting.php?yearId=" . ((empty($yearId)) ? date('Y') : $yearId) . "&adminPanel=" . AdminPanels::CONFIG);

$htmlMysqlDatatypes =
    [
      'varchar' => 'text',
      'int' => 'number'
    ];
?>

<!doctype html>
<html lang="en">
<head>
    <title>Admin Page</title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" style="min-width: 1200px !important;">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Application', '/admin-setting.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CONFIG, ($panel == AdminPanels::CONFIG || empty($panel)));
    $navBarLinksArray[] = new NavBarLink('Users', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::USERS, ($panel == AdminPanels::USERS));
    $navBarLinksArray[] = new NavBarLink('Robot Info', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::ROBOT_INFO, ($panel == AdminPanels::ROBOT_INFO));
    $navBarLinksArray[] = new NavBarLink('Scout Card Info', '/admin.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::SCOUT_CARD_INFO, ($panel == AdminPanels::SCOUT_CARD_INFO));
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
                <button id="dialog-cancel" type="button" class="mdl-button">Cancel</button>
                <button id="dialog-confirm" type="button"
                        class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent confirm">
                    Delete
                </button>
            </div>
        </dialog>

        <script>
            const ACTIONS =
                {
                    SAVE: 'SAVE',
                    EDIT: 'EDIT',
                    CANCEL: 'CANCEL',
                    DELETE: 'DELETE'
                };

            const HTML_MYSQL_DATATYPES =
                {
                    varchar: 'text',
                    int: 'number'
                };

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

        <div class="admin-table-wrapper">
            <table width="95%" align="center" style="table-layout: fixed;  white-space: unset"
                   class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
            <?php
            switch($panel)
            {
                case AdminPanels::USERS:
                    $users = Users::getObjects('FirstName', 'ASC');
                ?>
                        <tr>
                            <th class="admin-table-header">First Name</th>
                            <th class="admin-table-header">Last Name</th>
                            <th class="admin-table-header">Username</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        foreach ($users as $obj)
                        {
                            ?>
                            <tr>
                                <td class="admin-table-data" changeable="changeable"><?php echo $obj->FirstName ?></td>
                                <td class="admin-table-data" changeable="changeable"><?php echo $obj->LastName ?></td>
                                <td class="admin-table-data" changeable="changeable"><?php echo $obj->UserName ?></td>
                                <td class="admin-table-data">
                                    <button onclick="deleteRecord($(this).parent().parent()[0], <?php echo $obj->Id ?>)"
                                            class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                        <span class="button-text">Delete</span>
                                    </button>
                                </td>
                                <td class="admin-table-data">
                                    <button onclick="location.href  = '<?php echo ROOT_URL ?>/admin-setting.php?yearId=2019&adminPanel=<?php echo AdminPanels::USERS ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                        <span class="button-text">Edit</span>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    break;

                case AdminPanels::ROBOT_INFO:
                    $robotInfoKeys = RobotInfoKeys::getObjects('SortOrder', 'ASC');
                ?>
                        <tr>
                            <th class="admin-table-header">Game State</th>
                            <th class="admin-table-header">Info Name</th>
                            <th class="admin-table-header">Sort Order</th>
                            <th></th>
                            <th></th>
                        </tr>
                </thead>
                <tbody>

                <?php
                foreach ($robotInfoKeys as $obj)
                {
                    ?>
                    <tr>
                        <td class="admin-table-data" changeable="changeable"><?php echo $obj->KeyState ?></td>
                        <td class="admin-table-data" changeable="changeable"><?php echo $obj->KeyName ?></td>
                        <td class="admin-table-data" changeable="changeable"><?php echo $obj->SortOrder ?></td>
                        <td class="admin-table-data">
                            <button onclick="deleteRecord($(this).parent().parent()[0], <?php echo $obj->Id ?>)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                <span class="button-text">Delete</span>
                            </button>
                        </td>
                        <td class="admin-table-data">
                            <button onclick="location.href  = '<?php echo ROOT_URL ?>/admin-setting.php?yearId=2019&adminPanel=<?php echo AdminPanels::ROBOT_INFO ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                <span class="button-text">Edit</span>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                break;

                case AdminPanels::SCOUT_CARD_INFO:
                    $scoutCardInfos = ScoutCardInfoKeys::getObjects('SortOrder', 'ASC');
                    ?>
                    <tr>
                        <th class="admin-table-header">Game State</th>
                        <th class="admin-table-header">Info Name</th>
                        <th class="admin-table-header">Sort Order</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($scoutCardInfos as $obj)
                    {
                        ?>
                        <tr>
                            <td class="admin-table-data" changeable="changeable"><?php echo $obj->KeyState ?></td>
                            <td class="admin-table-data" changeable="changeable"><?php echo $obj->KeyName ?></td>
                            <td class="admin-table-data" changeable="changeable"><?php echo $obj->SortOrder ?></td>
                            <td class="admin-table-data">
                                <button onclick="deleteRecord($(this).parent().parent()[0], <?php echo $obj->Id ?>)"
                                        class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                    <span class="button-text">Delete</span>
                                </button>
                            </td>
                            <td class="admin-table-data">
                                <button onclick="location.href  = '<?php echo ROOT_URL ?>/admin-setting.php?yearId=2019&adminPanel=<?php echo AdminPanels::SCOUT_CARD_INFO ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                    <span class="button-text">Edit</span>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                    break;

                case AdminPanels::CHECKLIST_INFO:
                    $checklistItems = ChecklistItems::getObjects('Title', 'ASC');
                    ?>
                        <tr>
                            <th class="admin-table-header">Title</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($checklistItems as $obj)
                    {
                        ?>
                        <tr>
                            <td class="admin-table-data" changeable="changeable"><?php echo $obj->Title ?></td>
                            <td class="admin-table-data">
                                <button onclick="deleteRecord($(this).parent().parent()[0], <?php echo $obj->Id ?>)"
                                        class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                    <span class="button-text">Delete</span>
                                </button>
                            </td>
                            <td class="admin-table-data">
                                <button onclick="location.href  = '<?php echo ROOT_URL ?>/admin-setting.php?yearId=2019&adminPanel=<?php echo AdminPanels::CHECKLIST_INFO ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                    <span class="button-text">Edit</span>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                    break;
            }
            ?>
                </tbody>
            </table>
        </div>
        <button onclick="location.href  = '<?php echo ROOT_URL ?>/admin-setting.php?yearId=2019&adminPanel=<?php echo $panel ?>&settingId=-1';" class="settings-fab mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
            <i class="material-icons">add</i>
        </button>
        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
<script>
    function deleteRecord(row, recordId)
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
                            $(row).remove();


                        //display response to screen
                        showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
                    });

                dialog.close();
            });

        dialog.showModal();
    }

</script>
</body>
</html>
