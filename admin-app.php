<?php
require_once("config.php");

if (!getUser()->IsAdmin)
    header('Location: ' . URL_PATH);

require_once(ROOT_DIR . "/classes/Ajax.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");
require_once(ROOT_DIR . "/classes/tables/local/RobotInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ScoutCardInfoKeys.php");
require_once(ROOT_DIR . "/classes/tables/local/ChecklistItems.php");

$panel = $_GET['adminPanel'];

interface AdminPanels
{
    const USERS = Users::class;
    const CONFIG = Config::class;
}

$htmlMysqlDatatypes =
    [
      'varchar' => 'text',
      'int' => 'number'
    ];

switch($panel)
{
    case AdminPanels::CONFIG:
        $cols = Config::getColumns();
        $objs = Config::getObjects('Id', 'ASC');
        break;

    case AdminPanels::USERS:
        $cols = Users::getColumns();
        $objs = Users::getObjects('Id', 'ASC');
        break;

    default:
        $panel = Config::class;
        $cols = Config::getColumns();
        $objs = Config::getObjects();
        break;
}

?>

<!doctype html>
<html lang="en">
<head>
    <title>App Admin Page</title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header" style="min-width: 1200px !important;">
    <?php

    $navBarLinksArray = new NavBarLinkArray();
    $navBarLinksArray[] = new NavBarLink('Config', '/admin-app.php?adminPanel=' . AdminPanels::CONFIG, ($panel == AdminPanels::CONFIG || empty($panel)));
    $navBarLinksArray[] = new NavBarLink('Users', '/admin-app.php?adminPanel=' . AdminPanels::USERS, ($panel == AdminPanels::USERS));

    $navBar = new NavBar($navBarLinksArray);

    $header = new Header('App Admin Panel', null, $navBar);

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
            <?php
            if($panel != AdminPanels::CONFIG)
            {
                ?>
                <table width="75%" align="center" style="table-layout: fixed;  white-space: unset"
                       class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                    <tr style="border-bottom-color: rgba(0, 0, 0, 0)">
                        <?php

                        foreach ($cols as $col)
                        {
                            if ($col['Field'] != 'Id')
                            {
                                ?>

                                <td <?php echo((strpos($col['Field'], 'Year') !== false) ? 'hidden' : '') ?>
                                        changeable="changeable" sql-col-id="<?php echo $col['Field'] ?>">
                                    <input placeholder="<?php echo $col['Field'] . '...' ?>" <?php echo((strpos($col['Field'], 'Year') !== false) ? 'value="' . $year->Id . '"' : '') ?>
                                           class="admin-table-field"
                                           type="<?php echo $htmlMysqlDatatypes[substr($col['Type'], 0, strpos($col['Type'], '('))] ?>">
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
                <?php
            }
            ?>
            <table width="75%" align="center" style="table-layout: fixed;  white-space: unset"
                   class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
                <thead>
                <tr>
                    <?php

                    foreach ($cols as $col)
                    {
                        if ($col['Field'] != 'Id' && strpos($col['Field'], 'Year') === false && strpos($col['Field'], 'Password') === false)
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
                            <td class="admin-table-data" <?php echo(($col['Field'] == 'Id' || strpos($col['Field'], 'Year') !== false ||  strpos($col['Field'], 'Password') !== false) ? 'hidden' : '') ?> changeable="changeable" sql-col-id="<?php echo $col['Field'] ?>" sql-data-type="<?php echo substr($col['Type'], 0, strpos($col['Type'], '(')) ?>"><?php echo $obj->{$col['Field']} ?></td>
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

                            //convert it into HTML data type
                            sqlDataType = HTML_MYSQL_DATATYPES[sqlDataType];

                            //change the field into an input field
                            $(this).html('<input class="admin-table-field" type="' + sqlDataType + '" value="' + $(this).html() + '">');
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

                            $(row).html($(input).attr('value'));

                        });
                    }
                }
                else if (action == ACTIONS.DELETE)
                {
                    <?php
                    if($panel != AdminPanels::CONFIG)
                    {
                    ?>
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
                                <?php echo $col['Field'] ?>:
                                $(row).children('[sql-col-id=<?php echo $col['Field'] ?>]').html(),
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
                    <?php
                    }
                    else
                    {
                    ?>
                        showToast('You can only edit config values.');
                    <?php
                    }
                    ?>
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
