<?php
require_once("config.php");

if (loggedIn())
    header('Location: ' . URL_PATH);

require_once(ROOT_DIR . "/classes/tables/Years.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");

$yearId = $_GET['yearId'];
$year = Years::withId($yearId);

$setting = $_GET['setting'];

interface SETTINGS
{
    const SCOUT_CARDS = 'SCOUT_CARDS';
    const ROBOT_INFO = 'ROBOT_INFO';
}

;

?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $year->toString() ?></title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php

    $navBarArray = new NavBarArray();

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Scout Cards', '/admin-year.php?yearId=' . $year->Id . '&setting=' . SETTINGS::SCOUT_CARDS, $setting == SETTINGS::SCOUT_CARDS);
    $navBarLinksArray[] = new NavBarLink('Robot Info', '/admin-year.php?yearId=' . $year->Id . '&setting=' . SETTINGS::ROBOT_INFO, $setting == SETTINGS::ROBOT_INFO);

    $navBarArray[] = new NavBar($navBarLinksArray);

    $header = new Header($year->toString() . ' - Admin Panel', null, $navBarArray, null, $year);

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content">

        <!--        <div style="width: 100%; text-align: center; margin: 15px;">-->
        <!--            <button id="addRecord" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect">-->
        <!--                Add-->
        <!--            </button>-->
        <!---->
        <!--            <button id="editRecord" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" disabled>-->
        <!--                Edit-->
        <!--            </button>-->
        <!---->
        <!--            <button id="deleteRecord" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" disabled>-->
        <!--                Delete-->
        <!--            </button>-->
        <!--        </div>-->

        <dialog class="mdl-dialog" id="modifyDialog">
            <h4 class="mdl-dialog__title">Modify Record</h4>
            <div class="mdl-dialog__content">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="KeyState" value="" placeholder="">
                    <label class="mdl-textfield__label" >Key State</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="KeyName" value="" placeholder="">
                    <label class="mdl-textfield__label" >Key Name</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="SortOrder" value="" placeholder="">
                    <label class="mdl-textfield__label" >Sort Order</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="MinValue" value="" placeholder="">
                    <label class="mdl-textfield__label" >Min Value</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="MaxValue" value="" placeholder="">
                    <label class="mdl-textfield__label" >Max Value</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="NullZeros" value="" placeholder="">
                    <label class="mdl-textfield__label" >Null Zeros</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="IncludeInStats" value="" placeholder="">
                    <label class="mdl-textfield__label" >Include In Stats</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input  class="mdl-textfield__input" type="text" name="DataType" value="" placeholder="">
                    <label class="mdl-textfield__label" >Data Type</label>
                </div>

            </div>
            <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button">Save</button>
                <button type="button" class="mdl-button close" onclick="modifyDialog.close()">Cancel</button>
            </div>
        </dialog>

        <dialog class="mdl-dialog" id="deleteDialog">
            <h4 class="mdl-dialog__title">Delete Record?</h4>
            <div class="mdl-dialog__content">
                <p>
                    *WARNING*<br><br>
                    THIS WILL DELETE ALL RECORDS FOR THIS KEY FROM THE DATABASE.<br><br>
                    <strong>THIS ACTION CAN NOT BE UNDONE.</strong><br><br>
                    *WARNING*
                </p>
            </div>
            <div class="mdl-dialog__actions">
                <button type="button" class="mdl-button">Delete</button>
                <button type="button" class="mdl-button close" onclick="deleteDialog.close()">Cancel</button>
            </div>
        </dialog>




        <table align="center" class="mdl-data-table mdl-js-data-table mdl-data-table mdl-shadow--2dp">
            <thead>
            <tr id="header">
                <th class="mdl-data-table__cell--non-numeric">Key State</th>
                <th>Key Name</th>
                <th>Sort Order</th>
                <th>Min Value</th>
                <th>Max Value</th>
                <th>Nullify Zeros</th>
                <th>Include In Stats</th>
                <th>Data Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php



            foreach(ScoutCardInfoKeys::getObjects() as $scoutCardInfoKey)
            {

            ?>
            <tr>
                <td class="mdl-data-table__cell--non-numeric"><?php echo $scoutCardInfoKey->KeyState ?></td>
                <td><?php echo $scoutCardInfoKey->KeyName ?></td>
                <td><?php echo $scoutCardInfoKey->SortOrder ?></td>
                <td><?php echo $scoutCardInfoKey->MinValue ?></td>
                <td><?php echo $scoutCardInfoKey->MaxValue ?></td>
                <td><?php echo ($scoutCardInfoKey->NullZeros == 1) ? 'Yes' : 'No' ?></td>
                <td><?php echo ($scoutCardInfoKey->IncludeInStats == 1) ? 'Yes' : 'No' ?></td>
                <td><?php echo $scoutCardInfoKey->DataType ?></td>


                <td>
                    <div style="width: 100%; text-align: center; margin: 15px;">

                        <button class="editRecord mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="modifyRecord(
                            '<?php echo $scoutCardInfoKey->KeyState ?>',
                            '<?php echo $scoutCardInfoKey->KeyName ?>',
                            '<?php echo $scoutCardInfoKey->SortOrder ?>',
                            '<?php echo $scoutCardInfoKey->MinValue ?>',
                            '<?php echo $scoutCardInfoKey->MaxValue ?>',
                            '<?php echo ($scoutCardInfoKey->NullZeros == 1) ? 'Yes' : 'No' ?>',
                            '<?php echo ($scoutCardInfoKey->IncludeInStats == 1) ? 'Yes' : 'No'  ?>',
                            '<?php echo $scoutCardInfoKey->DataType ?>');">
                            Edit
                        </button>

                        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" onclick="deleteRecord(<?php echo $scoutCardInfoKey->Id ?>)">
                            Delete
                        </button>
                    </div>
                </td>
            </tr>
            <?php
            }
            ?>
            </tbody>
        </table>

        <script>

                var modifyDialog = $('#modifyDialog')[0];
                var deleteDialog = $('#deleteDialog')[0];

                if (! modifyDialog.showModal) {
                    dialogPolyfill.registerDialog(modifyDialog);
                }

                if (! deleteDialog.showModal) {
                    dialogPolyfill.registerDialog(deleteDialog);
                }

                function modifyRecord(keyState, keyName, sortOrder, minValue, maxValue, nullZeros, includeInStats, dataType)
                {
                    $(modifyDialog).find("input[name='KeyState']").attr('value', keyState);
                    $(modifyDialog).find("input[name='KeyName']").attr('value', keyName);
                    $(modifyDialog).find("input[name='SortOrder']").attr('value', sortOrder);
                    $(modifyDialog).find("input[name='MinValue']").attr('value', minValue);
                    $(modifyDialog).find("input[name='MaxValue']").attr('value', maxValue);
                    $(modifyDialog).find("input[name='NullZeros']").attr('value', nullZeros);
                    $(modifyDialog).find("input[name='IncludeInStats']").attr('value', includeInStats);
                    $(modifyDialog).find("input[name='DataType']").attr('value', dataType);


                    modifyDialog.showModal();
                }

                function deleteRecord(id)
                {
                    deleteDialog.showModal();
                }


        </script>



        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>

</body>
</html>
