<?php

if(!empty($_GET))
{
    if ($_GET['logout'] == 1)
    {
        session_start();
        unset($_SESSION);
        session_destroy();
    }
}

require_once("config.php");
require_once(ROOT_DIR . '/classes/tables/core/Accounts.php');

if(isPostBack())
{
    $coreAccount = Accounts::login($_POST['username'], $_POST['password']);

    if(!empty($coreAccount))
        $_SESSION['coreAccount'] = serialize($coreAccount);
}

if(coreLoggedIn())
    header('Location: '. ROOT_URL . '/' . getCoreAccount()->TeamId . '/event-list.php?yearId=' . date('Y'));
?>
<!doctype html>
<html lang="en">
<head>
    <title>FRC Scout</title>
    <?php require_once('includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <main class="mdl-layout__content homescreen">
        <div class="home-login">
            <img src="<?php echo IMAGES_URL ?>app-icon.png" width="200">
            <div>
                <form method="post" action="/">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="username">
                        <label class="mdl-textfield__label" >Username</label>
                    </div>
                    <br>
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" name="password">
                        <label class="mdl-textfield__label" >Password</label>
                    </div>
                    <br>
                    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                        Login
                    </button>
                </form>
            </div>
            <p class="create-account">
                New to the platform? <a href="<?php ROOT_URL ?>/create-account.php">Create your teams account!</a>
            </p>

        </div>
    </main>
</div>
<?php

if(!empty($_GET))
{
    if($_GET['installSuccess'] == 1)
    {
        ?>
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

            $(document).ready(function()
            {
               showToast('Install Successful. You may now login with your credentials.'); 
            });
        </script>
<?php
    }
}

?>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>

