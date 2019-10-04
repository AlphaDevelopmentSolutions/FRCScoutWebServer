<?php
require_once("../../config.php");
require_once(ROOT_DIR . "/classes/tables/core/Teams.php");
require_once(ROOT_DIR . "/classes/tables/core/Events.php");
require_once(ROOT_DIR . "/classes/tables/core/Years.php");

if(!loggedIn())
    header('Location: ' . URL_PATH);

?>
<!doctype html>
<html lang="en">
<head>
    <title>Account</title>
    <?php require_once(INCLUDES_DIR . 'meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <?php
    $header = new Header('Account');

    echo $header->toHtml();

    ?>

    <main class="mdl-layout__content">
        <a href="<?php echo AJAX_URL ?>account.php">Logout</a>
        <?php require_once(INCLUDES_DIR . 'footer.php') ?>
    </main>
</div>
<?php require_once(INCLUDES_DIR . 'bottom-scripts.php') ?>
</body>
</html>
