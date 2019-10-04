<?php
require_once('../config.php');
require_once(ROOT_DIR . "/classes/Ajax.php");
?>
$(document).ready(function ()
{
    //prevent form from submitting, but keep validations
    $('#user-sign-in-form').submit(function(e)
    {
        e.preventDefault();
        loginUser();
    });
});

/**
 * Attempts to login to server
 */
function loginUser()
{
    //get data from the ajax script
    $.post('<?php echo AJAX_URL ?>account.php',
        {
            action: 'login_user',
            username: $('#user-username').val(),
            password: $('#user-password').val()
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
