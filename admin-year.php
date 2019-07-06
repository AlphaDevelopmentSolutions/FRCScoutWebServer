<?php
require_once("config.php");

if (!getUser()->IsAdmin)
    header('Location: ' . URL_PATH);

require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/Years.php");
require_once(ROOT_DIR . "/classes/tables/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/ChecklistItems.php");

$yearId = $_GET['yearId'];

$year = ((empty($yearId)) ? null : Years::withId($yearId));

$panel = $_GET['adminPanel'];

interface AdminPanels
{
    const ROBOT_INFO = RobotInfoKeys::class;
    const SCOUT_CARD_INFO = ScoutCardInfoKeys::class;
    const CHECKLIST_INFO = ChecklistItems::class;
}

$htmlMysqlDatatypes =
    [
      'varchar' => 'text',
      'int' => 'number'
    ];

switch ($panel)
{
    case AdminPanels::ROBOT_INFO:
        $cols = RobotInfoKeys::getColumns();
        $objs = RobotInfoKeys::getObjects();
        break;

    case AdminPanels::SCOUT_CARD_INFO:
        $cols = ScoutCardInfoKeys::getColumns();
        $objs = ScoutCardInfoKeys::getObjects();
        break;

    case AdminPanels::CHECKLIST_INFO:
        $cols = ChecklistItems::getColumns();
        $objs = ChecklistItems::getObjects();
        break;

    default:
        $cols = RobotInfoKeys::getColumns();
        $objs = RobotInfoKeys::getObjects();
        break;
}


?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo $year->Id ?> - Admin Page</title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" style="min-width: 1200px !important;">
    <?php
    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Robot Info', '/admin-year.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::ROBOT_INFO, ($panel == AdminPanels::ROBOT_INFO || empty($panel)));
    $navBarLinksArray[] = new NavBarLink('Scout Card Info', '/admin-year.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::SCOUT_CARD_INFO, ($panel == AdminPanels::SCOUT_CARD_INFO));
    $navBarLinksArray[] = new NavBarLink('Checklist Info', '/admin-year.php?yearId=' . $year->Id . '&adminPanel=' . AdminPanels::CHECKLIST_INFO, ($panel == AdminPanels::CHECKLIST_INFO));

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header($year->toString() . ' - Admin Panel', null, $navBar, null, $year);

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
            <table width="75%" align="center" style="table-layout: fixed;  white-space: unset"
                   class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <tr style="border-bottom-color: rgba(0, 0, 0, 0)">
                    <?php

                    foreach ($cols as $col)
                    {
                        if ($col['Field'] != 'Id')
                        {
                            ?>



                            <td <?php echo ((strpos($col['Field'], 'Year') !== false) ? 'hidden' : '') ?> changeable="changeable" sql-col-id="<?php echo $col['Field'] ?>">

                                <?php

                                if(strpos(strtolower($col['Type']), 'enum') === false)
                                {
                                    ?>
                                    <input placeholder="<?php echo $col['Field'] . '...' ?>" <?php echo ((strpos($col['Field'], 'Year') !== false) ? 'value="' . $year->Id . '"' : '') ?> class="admin-table-field" type="<?php echo $htmlMysqlDatatypes[substr($col['Type'], 0, strpos($col['Type'], '('))] ?>">
                                    <?php
                                }

                                else
                                {
                                    $enumOptions = $col['Type'];
                                    $enumOptions = str_replace('enum(', '', $enumOptions);
                                    $enumOptions = str_replace(')', '', $enumOptions);
                                    $enumOptions = str_replace('\'', '', $enumOptions);
                                    $enumOptions = explode(',', $enumOptions);

                                    ?>
                                        <select <?php echo ((strpos($col['Field'], 'Year') !== false) ? 'value="' . $year->Id . '"' : '') ?> class="admin-table-field">
                                            <?php
                                                foreach($enumOptions as $option)
                                                {
                                                    ?>
                                                        <option value="<?php echo $option ?>"><?php echo $option ?></option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                        }
                    }
                    ?>
                    <td></td>
                    <td class="admin-table-data">
                        <button onclick="addRecord($(this).parent().parent()[0])"
                                class="mdl-button mdl-js-button mdl-js-ripple-effect table-button add">
                            <span class="button-text">Add</span>
                        </button>
                    </td>
                </tr>
            </table>
            <table width="75%" align="center" style="table-layout: fixed;  white-space: unset"
                   class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                <tr>
                    <?php

                    foreach ($cols as $col)
                    {
                        if ($col['Field'] != 'Id' && strpos($col['Field'], 'Year') === false)
                        {
                            ?>
                            <th class="admin-table-header"><?php echo $col['Field'] ?></th>
                            <?php
                        }
                    }
                    ?>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($objs as $obj)
                {
                    ?>
                    <tr>
                        <?php

                        foreach ($cols as $col)
                        {
                            ?>
                            <td class="admin-table-data" <?php echo(($col['Field'] == 'Id' || strpos($col['Field'], 'Year') !== false) ? 'hidden' : '') ?> changeable="changeable" sql-col-id="<?php echo $col['Field'] ?>" sql-data-type="<?php echo $col['Type'] ?>"><?php echo $obj->{$col['Field']} ?></td>
                            <?php
                        }
                        ?>
                        <td class="admin-table-data">
                            <button onclick="modifyRecord($(this).parent().prev().children()[0], $(this), $(this).parent().parent()[0], ACTIONS.DELETE)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect table-button delete">
                                <span class="button-text">Delete</span>
                            </button>
                        </td>
                        <td class="admin-table-data">
                            <button onclick="modifyRecord($(this).parent().prev().children()[0], $(this), $(this).parent().parent()[0], ACTIONS.EDIT)"
                                    class="mdl-button mdl-js-button mdl-js-ripple-effect table-button edit">
                                <span class="button-text">Edit</span>
                            </button>
                        </td>
                    </tr>
                    <?php
                }

                ?>
                </tbody>
            </table>
        </div>

        <script>

            function addRecord(row)
            {
                //call the admin ajax script to modify the records in the database
                $.post('/ajax/admin.php',
                {
                    action: 'save',
                    class: '<?php echo $panel ?>',
                    <?php
                    foreach($cols as $col)
                    {
                    ?>
                    <?php echo $col['Field'] ?>: $(row).children('[sql-col-id=<?php echo $col['Field'] ?>]').children().val(),
                    <?php
                    }
                    ?>
                },
                function (data)
                {
                    data = JSON.parse(data);

                    //check success status code
                    if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                    {
                        //change each row to the value of the input field from the HTML form
                        $(row).children('[changeable=changeable]').each(function ()
                        {
                            $(this).children().val('');

                        });
                    }

                    //display response to screen
                    showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);

                });

            }

            function modifyRecord(deleteContext, editContext, row, action)
            {

                //if we are editing or canceling the edit, toggle the button and label types / names
                if (action == ACTIONS.CANCEL || action == ACTIONS.EDIT)
                {
                    //if not currently editing, change the row to editable
                    if ($(editContext).hasClass('edit'))
                    {
                        //update classes, onclick button and text for the edit button
                        $(editContext)
                            .addClass('editing')
                            .removeClass('edit')
                            .removeAttr('onclick')
                            .unbind('click')
                            .click(function ()
                            {
                                modifyRecord(deleteContext, editContext, row, ACTIONS.SAVE)
                            })
                            .find('.button-text')
                            .html('Save');

                        //update classes, onclick button and text for the delete button
                        $(deleteContext)
                            .addClass('editing')
                            .removeClass('edit')
                            .removeAttr('onclick')
                            .unbind('click')
                            .click(function ()
                            {
                                modifyRecord(deleteContext, editContext, row, ACTIONS.CANCEL)
                            })
                            .find('.button-text')
                            .html('Cancel');

                        //iterate through each object with the class changeable
                        $(row).children('[changeable=changeable]').each(function ()
                        {
                            //grab the datatype from the sql-data-type attribute
                            var sqlDataType = $(this).attr('sql-data-type');

                            var inputField;

                            //check if the datatype was an enum, if it was provide a select box
                            if(sqlDataType.toLowerCase().indexOf('enum') === -1)
                                inputField = '<input class="admin-table-field" type="' + HTML_MYSQL_DATATYPES[sqlDataType.substring(0, sqlDataType.indexOf('('))] + '" value="' + $(this).html() + '">';

                            //enum specified, create a select box
                            else
                            {
                                var enumOptions = sqlDataType.replace('enum(', '');
                                enumOptions = enumOptions.replace(')', '');
                                enumOptions = enumOptions.replace(/\'/g, '');
                                enumOptions = enumOptions.split(',');

                                inputField = '<select class="admin-table-field" value="' + $(this).html() + '">';

                                $.each(enumOptions, function(key, value)
                                {
                                    inputField += '<option value="' + value + '">' + value + '</option>';

                                });

                                inputField += '</select>';
                            }

                            //change the field into an input field
                            $(this).html(inputField);
                        });
                    }
                    else
                    {
                        //update classes, onclick button and text for the edit button
                        $(editContext)
                            .addClass('edit')
                            .removeClass('editing')
                            .removeAttr('onclick')
                            .unbind('click')
                            .click(function ()
                            {
                                modifyRecord(deleteContext, editContext, row, ACTIONS.EDIT)
                            })
                            .find('.button-text')
                            .html('Edit');

                        //update classes, onclick button and text for the delete button
                        $(deleteContext)
                            .addClass('edit')
                            .removeClass('editing')
                            .unbind('click')
                            .click(function ()
                            {
                                modifyRecord(deleteContext, editContext, row, ACTIONS.DELETE)
                            })
                            .find('.button-text')
                            .html('Delete');


                        //convert all changeable fields to plain text
                        $(row).children('[changeable=changeable]').each(function ()
                        {
                            var row = $(this);
                            var input = $(row).children();

                            console.log($(input));

                            $(row).html($(input).attr('value'));

                        });
                    }
                }
                else if (action == ACTIONS.DELETE)
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
                                <?php
                                foreach($cols as $col)
                                {
                                ?>
                                    <?php echo $col['Field'] ?>: $(row).children('[sql-col-id=<?php echo $col['Field'] ?>]').html(),
                                <?php
                                }
                                ?>
                            },
                            function (data)
                            {
                                data = JSON.parse(data);

                                //check success status code
                                if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                                {
                                    $(row).remove();
                                }

                                //display response to screen
                                showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
                            });

                            dialog.close();
                        });

                    dialog.showModal();
                }


                else if (action == ACTIONS.SAVE)
                {
                    //call the admin ajax script to modify the records in the database
                    $.post('/ajax/admin.php',
                    {
                        action: 'save',
                        class: '<?php echo $panel ?>',
                        <?php
                        foreach($cols as $col)
                        {
                            ?>
                            <?php echo $col['Field'] ?>: $(row).children('[sql-col-id=<?php echo $col['Field'] ?>]').children().val(),
                            <?php
                        }
                        ?>
                    },
                    function (data)
                    {
                        data = JSON.parse(data);

                        //check success status code
                        if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                        {
                            //update classes, onclick button and text for the edit button
                            $(editContext)
                                .addClass('edit')
                                .removeClass('editing')
                                .removeAttr('onclick')
                                .unbind('click')
                                .click(function ()
                                {
                                    modifyRecord(deleteContext, editContext, row, ACTIONS.EDIT);
                                })
                                .find('.button-text')
                                .html('Edit');

                            //update classes, onclick button and text for the delete button
                            $(deleteContext)
                                .addClass('edit')
                                .removeClass('editing')
                                .unbind('click')
                                .click(function ()
                                {
                                    modifyRecord(deleteContext, editContext, row, ACTIONS.DELETE);
                                })
                                .find('.button-text')
                                .html('Delete');


                            //change each row to the value of the input field from the HTML form
                            $(row).children('[changeable=changeable]').each(function ()
                            {
                                var input = $(this).children();

                                $(this).html($(input).val());

                            });
                        }

                        //display response to screen
                        showToast(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
                    });
                }

            }

        </script>

        <?php require_once('includes/footer.php') ?>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>
