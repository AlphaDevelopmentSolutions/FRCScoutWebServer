<?php
header("Content-type: text/javascript; charset: UTF-8");
require_once('../config.php');
require_once(ROOT_DIR . "/classes/Ajax.php");
?>
$(document).ready(function ()
{
    //prevent form from submitting, but keep validations
    $('#core-sign-out-form').submit(function(e)
    {
        e.preventDefault();
        logoutCore();
    });
});

/**
 * Logs out of the core account
 */
function logoutCore()
{
    //get data from the ajax script
    $.post('<?php echo AJAX_URL ?>account.php',
        {
            action: 'logout_core'
        },
        function(data)
        {
            var parsedData = JSON.parse(data);

            if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                location.reload();

            else
                showToast(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);
        });
}
