<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");

?>
<!doctype html>
<html lang="en">
<head>
    <title>Account</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
    <script>
        if (window.history.replaceState)
        {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $header = new Header('Account');

    echo $header->toHtml();
    ?>
    <main class="mdl-layout__content center-div-horizontal-outer">
        <?php
        if (loggedIn())
        {
            getUser()->toHtml();
        } else
        {
            ?>
            <div class="account-login center-div-horizontal-inner">
                <div>
                    <form method="post" action="" id="user-sign-in-form">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" id="user-username" name="user-username">
                            <label class="mdl-textfield__label">Username</label>
                        </div>
                        <br>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" id="user-password" name="user-password">
                            <label class="mdl-textfield__label">Password</label>
                        </div>
                        <br>
                        <button id="user-sign-in-button" type="submit"
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent material-padding">
                            Login
                        </button>
                        <br>
                        <span hidden id="user-sign-in-loading-div">
                        <div class="mdl-spinner mdl-spinner--single-color mdl-js-spinner is-active"></div>
                        Logging In...
                    </span>
                        <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
                    </form>
                </div>
            </div>
            <?php
        }
        ?>
        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php');
if (loggedIn())
{
    ?>
    <script src="<?php echo JS_URL ?>user-sign-out.js.php"></script>
<?php
}
else
{
?>
    <script src="<?php echo JS_URL ?>user-sign-in.js.php"></script>
    <?php
}
?>
</body>
</html>
