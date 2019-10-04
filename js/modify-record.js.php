<?php
require_once('../config.php');
require_once(ROOT_DIR . "/classes/Ajax.php");
?>

/**
 * Saves record to the database
 * @param recordType string record type name to save
 * @param recordId int to use when saving, if -1 it will create a new record
 * @param record mainly used for scout cards info and robot info fields, stores the row to save
 */
function saveRecord(recordType, recordId, record = undefined)
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

        case "<?php echo RobotInfo::class ?>":
            data =
                {
                    Id: $(record).attr('info-id'),
                    YearId: $(record).attr('year-id'),
                    EventId: $(record).attr('event-id'),
                    TeamId: $(record).attr('team-id'),
                    PropertyValue: $(record).val(),
                    PropertyKeyId: $(record).attr('info-key-id')
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

        case "<?php echo ScoutCardInfo::class ?>":
            data =
                {
                    Id: $(record).attr('info-id'),
                    YearId: $(record).attr('year-id'),
                    EventId: $(record).attr('event-id'),
                    MatchId: $(record).attr('match-id'),
                    TeamId: $(record).attr('team-id'),
                    CompletedBy: "",
                    PropertyValue: $(record).val(),
                    PropertyKeyId: $(record).attr('info-key-id')
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

        case "<?php echo ChecklistItemResults::class ?>":
            data =
                {
                    Id: recordId,
                    Status: $('#Status').val(),
                    CompletedBy: $('#CompletedBy').val(),
                    CompletedDate: $('#CompletedDate').val()
                };
            break;
    }

    //call the admin ajax script to modify the records in the database
    $.post('<?php echo AJAX_URL ?>admin.php',
        {
            action: 'save',
            class: recordType,
            data: data
        },
        function (data)
        {
            data = JSON.parse(data);

            //check success status code
            if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                saveSuccessCallback(data['<?php echo Ajax::$RESPONSE_KEY ?>']);

            else
                saveFailCallback(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
        });
}

/**
 * Deletes record from database
 * @param recordType string record type name to save
 * @param recordId int id of record to delete
 * @param extraArgs array extra args to feed to the admin ajax
 */
function deleteRecord(recordType, recordId, extraArgs)
{
    showDialog("Delete Record?", "All records in the database will be deleted. This action cannot be undone.", function ()
    {
        //call the admin ajax script to modify the records in the database
        $.post('<?php echo AJAX_URL ?>admin.php',
            {
                action: 'delete',
                class: recordType,
                recordId: recordId,
                extraArgs: extraArgs
            },
            function (data)
            {
                data = JSON.parse(data);

                //check success status code
                if (data['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                    deleteSuccessCallback(data['<?php echo Ajax::$RESPONSE_KEY ?>']);

                else
                    deleteFailCallback(data['<?php echo Ajax::$RESPONSE_KEY ?>']);
            });

        dialog.close();
    });
}