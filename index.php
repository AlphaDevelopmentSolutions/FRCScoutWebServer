<?php
require_once("config.php");
require_once(ROOT_DIR . '/classes/tables/core/Accounts.php');

if(isPostBack())
{
    $coreAccount = Accounts::login($_POST['username'], $_POST['password']);

    if(!empty($coreAccount))
        $_SESSION['coreAccount'] = serialize($coreAccount);
}

//unset($_SESSION['coreAccount']);

if(coreLoggedIn())
    header('Location: '. URL_PATH . '/event-list.php?yearId=' . date('Y'));
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
            <img src="<?php echo URL_PATH ?>/assets/images/app-icon.png" width="200">
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
        </div>
    </main>
</div>
<?php require_once('includes/bottom-scripts.php') ?>
</body>
</html>

