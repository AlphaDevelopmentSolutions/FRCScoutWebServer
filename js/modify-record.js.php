<?php
require_once('../config.php');
require_once(ROOT_DIR . "/classes/Ajax.php");
?>

/**
 * Saves record to the database
 * @param recordType string record type name to save
 * @param recordId int to use when saving, if -1 it will create a new record
 */
function saveRecord(recordType, recordId)
{
    var data;

    //assign the data based on what panel we are viewing
    switch (recordType)
    {
        case "<?php echo Config::class ?>":
            data =
                {
                    AppName: $('#APP_NAME').val(),
                    ApiKey: $('#API_KEY').val(),
                    PrimaryColor: $('#PRIMARY_COLOR').val(),
                    PrimaryColorDark: $('#PRIMARY_COLOR_DARK').val()
                };
            break;

        case "<?php echo Users::class ?>":
            data =
                {
                    Id: recordId,
                    FirstName: $('#FirstName').val(),
                    LastName: $('#LastName').val(),
                    UserName: $('#UserName').val(),
                    Password: $('#Password').val(),
                    IsAdmin: $('#IsAdmin').prop("checked") ? "1" : "0"
                };
            break;

        case "<?php echo RobotInfoKeys::class ?>":
            data =
                {
                    Id: recordId,
                    YearId: $('#YearId').val(),
                    KeyState: $('#KeyState').val(),
                    KeyName: $('#KeyName').val(),
                    SortOrder: $('#SortOrder').val()
                };
            break;

        case "<?php echo ScoutCardInfoKeys::class ?>":
            data =
                {
                    Id: recordId,
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
            break;

        case "<?php echo ChecklistItems::class ?>":
            data =
                {
                    Id: recordId,
                    YearId: $('#YearId').val(),
                    Title: $('#Title').val(),
                    Description: $('#Description').val()
                };
            break;
    }

    //call the admin ajax script to modify the records in the database
    $.post('/ajax/admin.php',
        {
            action: 'save',
            class: recordType,
            data: data
        },
        function (data)
        {
            data = JSON.parse(data);

            //check success status code
            if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>' && recordId === undefined)
                saveSuccessCallBack(data['<?php echo Ajax::$RESPONSE_KEY ?>']);

            else
                saveFailCallBack(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
        });
}

/**
 * Deletes record from database
 * @param recordType string record type name to save
 * @param recordId int id of record to delete
 */
function deleteRecord(recordType, recordId)
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
                    class: recordType,
                    recordId: recordId
                },
                function (data)
                {
                    data = JSON.parse(data);

                    //check success status code
                    if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>' && recordId === undefined)
                        deleteSuccessCallBack(data['<?php echo Ajax::$RESPONSE_KEY ?>']);

                    else
                        deleteFailCallBack(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
                });

            dialog.close();
        });

    dialog.showModal();
}