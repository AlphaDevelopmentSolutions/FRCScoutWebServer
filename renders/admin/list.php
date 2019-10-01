<?php
require_once("../../config.php");

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
    const ROBOT_INFO_KEYS = RobotInfoKeys::class;
    const SCOUT_CARD_INFO_KEYS = ScoutCardInfoKeys::class;
    const CHECKLIST_INFO = ChecklistItems::class;
    const CONFIG = Config::class;
    const USERS = Users::class;
}

if(empty($panel) || empty($yearId))
    header('Location: ' . ADMIN_URL . "settings?yearId=" . ((empty($yearId)) ? date('Y') : $yearId) . "&adminPanel=" . AdminPanels::CONFIG);

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
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
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
        <div class="admin-table-wrapper">
            <table width="95%" align="center" style="table-layout: fixed;  white-space: unset"
                   class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
            <?php
            switch($panel)
            {
                case AdminPanels::USERS:
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
                        foreach (Users::getObjects(null, null, null,'FirstName', 'ASC') as $obj)
                        {
                            ?>
                            <tr>
                                <td class="admin-table-data"><?php echo $obj->FirstName ?></td>
                                <td class="admin-table-data"><?php echo $obj->LastName ?></td>
                                <td class="admin-table-data"><?php echo $obj->UserName ?></td>
                                <td class="admin-table-data">
                                    <button onclick="deleteRecordOverride($(this).parent().parent()[0], '<?php echo get_class($obj) ?>', <?php echo $obj->Id ?>)"
                                            class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                        <span class="button-text">Delete</span>
                                    </button>
                                </td>
                                <td class="admin-table-data">
                                    <button onclick="location.href  = '<?php echo ROOT_URL . ADMIN_URL ?>settings?yearId=2019&adminPanel=<?php echo AdminPanels::USERS ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                        <span class="button-text">Edit</span>
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    break;

                case AdminPanels::ROBOT_INFO_KEYS:
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
                foreach (RobotInfoKeys::getObjects($year, 'SortOrder', 'ASC') as $obj)
                {
                    ?>
                    <tr>
                        <td class="admin-table-data"><?php echo $obj->KeyState ?></td>
                        <td class="admin-table-data"><?php echo $obj->KeyName ?></td>
                        <td class="admin-table-data"><?php echo $obj->SortOrder ?></td>
                        <td class="admin-table-data">
                            <button onclick="deleteRecordOverride($(this).parent().parent()[0], '<?php echo get_class($obj) ?>', <?php echo $obj->Id ?>)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                <span class="button-text">Delete</span>
                            </button>
                        </td>
                        <td class="admin-table-data">
                            <button onclick="location.href  = '<?php echo ROOT_URL . ADMIN_URL ?>settings?yearId=2019&adminPanel=<?php echo AdminPanels::ROBOT_INFO_KEYS ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                <span class="button-text">Edit</span>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                break;

                case AdminPanels::SCOUT_CARD_INFO_KEYS:
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
                    foreach (ScoutCardInfoKeys::getObjects($year, 'SortOrder', 'ASC') as $obj)
                    {
                        ?>
                        <tr>
                            <td class="admin-table-data"><?php echo $obj->KeyState ?></td>
                            <td class="admin-table-data"><?php echo $obj->KeyName ?></td>
                            <td class="admin-table-data"><?php echo $obj->SortOrder ?></td>
                            <td class="admin-table-data">
                                <button onclick="deleteRecordOverride($(this).parent().parent()[0], '<?php echo get_class($obj) ?>', <?php echo $obj->Id ?>)"
                                        class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                    <span class="button-text">Delete</span>
                                </button>
                            </td>
                            <td class="admin-table-data">
                                <button onclick="location.href  = '<?php echo ROOT_URL . ADMIN_URL ?>settings?yearId=2019&adminPanel=<?php echo AdminPanels::SCOUT_CARD_INFO_KEYS ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                    <span class="button-text">Edit</span>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                    break;

                case AdminPanels::CHECKLIST_INFO:
                    $checklistItems = ChecklistItems::getObjects($year, 'Title', 'ASC');
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
                            <td class="admin-table-data"><?php echo $obj->Title ?></td>
                            <td class="admin-table-data">
                                <button onclick="deleteRecordOverride($(this).parent().parent()[0], '<?php echo get_class($obj) ?>', <?php echo $obj->Id ?>)"
                                        class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                    <span class="button-text">Delete</span>
                                </button>
                            </td>
                            <td class="admin-table-data">
                                <button onclick="location.href  = '<?php echo ROOT_URL . ADMIN_URL ?>settings?yearId=2019&adminPanel=<?php echo AdminPanels::CHECKLIST_INFO ?>&settingId=<?php echo $obj->Id ?>';" class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
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
        <button onclick="location.href  = '<?php echo ROOT_URL . ADMIN_URL ?>settings?yearId=<?php echo $year->Id ?>&adminPanel=<?php echo $panel ?>&settingId=-1';" class="settings-fab mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored">
            <i class="material-icons">add</i>
        </button>
        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
<?php require_once(INCLUDES_DIR . 'modals.php'); ?>
<script src="<?php echo JS_URL ?>modify-record.js.php"></script>
<script>

    var pendingRowRemoval = [];

    function deleteRecordOverride(row, recordType, recordId)
    {
        pendingRowRemoval.push($(row));
        deleteRecord(recordType, recordId);
    }

    function deleteSuccessCallback(message)
    {
        if(pendingRowRemoval.length > 0)
            $(pendingRowRemoval[0]).remove();

        showToast(message);
    }
</script>
</body>
</html>
