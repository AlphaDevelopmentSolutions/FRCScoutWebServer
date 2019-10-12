<?php
$bypassCoreCheck = true;
require_once("config.php");
require_once(ROOT_DIR . '/classes/tables/core/Accounts.php');
require_once(ROOT_DIR . '/classes/Ajax.php');

if(coreLoggedIn())
    redirect(EVENTS_URL . 'list?yearId=' . date('Y'));
?>
<!doctype html>
<html lang="en">
<head>
    <title>FRC Scout</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
    <script defer src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <main class="mdl-layout__content homescreen">
        <div class="home-login">
            <img src="<?php echo IMAGES_URL ?>app-icon.png" width="200">
            <div>
                <form method="post" action="" id="login-form">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" id="username" name="username">
                        <label class="mdl-textfield__label" >Username</label>
                    </div>
                    <br>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" id="password" name="password">
                        <label class="mdl-textfield__label" >Password</label>
                    </div>
                    <br>
                    <button id="core-sign-in-button" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent material-padding">
                        Login
                    </button>
                    <br>
                    <div class="g-recaptcha material-top-bottom-padding" data-sitekey="6Lfx2bsUAAAAAOUWzZjeIs1X7ASb43js5-LKB3rp"></div>
                    <span hidden id="core-sign-in-loading-div">
                        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                        Logging In...
                    </span>
                </form>
            </div>
            <p class="create-account">
                New to the platform? <a href="<?php ROOT_URL ?>/signup.php">Create your teams account!</a>
            </p>

        </div>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'modals.php'); ?>
<script>
    $(document).ready(function ()
    {
        //prevent form from submitting, but keep validations
        $('#login-form').submit(function(e)
        {
            e.preventDefault();

            if(grecaptcha.getResponse().length == 0)
                showToast("Please verify the CAPTCHA.");
            else
                login();
        });
    });

    /**
     * Attempts to login to server
     */
    function login()
    {
        $('#core-sign-in-loading-div').removeAttr('hidden');
        $('#core-sign-in-button').attr('disabled', 'disabled');

        //get data from the ajax script
        $.post('<?php echo AJAX_URL ?>account.php',
            {
                action: 'login_core',
                username: $('#username').val(),
                password: $('#password').val(),
                captchaKey: grecaptcha.getResponse()
            },
            function(data)
            {
                grecaptcha.reset();
                $('#core-sign-in-loading-div').attr('hidden', 'hidden');
                $('#core-sign-in-button').removeAttr('disabled');

                var parsedData = JSON.parse(data);

                if(parsedData['<?php echo Ajax::$STATUS_KEY ?>'] == '<?php echo Ajax::$SUCCESS_STATUS_CODE ?>')
                    location.href = '<?php echo EVENTS_URL . 'list?yearId=' . date('Y') ?>';

                else
                    showToast(parsedData['<?php echo Ajax::$RESPONSE_KEY ?>']);
            });
    }
</script>
<?php

if(!empty($_GET))
{
    if($_GET['installSuccess'] == 1)
    {
        ?>

        <script>
            $(document).ready(function()
            {
                showDialog('Account Created', 'Your account has been created. Please log in using the details previously entered.', function () {dialog.close()});
            });
        </script>
<?php
    }
}

?>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
</body>
</html>

