<?php
define('ROBOT_MEDIA_DIR', __DIR__ . '/assets/robot-media/originals/');
define('ROBOT_MEDIA_THUMBS_DIR', __DIR__ . '/assets/robot-media/thumbs/');
define('ROBOT_MEDIA_URL', '/assets/robot-media/originals/');
define('ROBOT_MEDIA_THUMBS_URL', '/assets/robot-media/thumbs/');
define('YEAR_MEDIA_URL', '/assets/year-media/');
define('IMAGES_URL', '/assets/images/');
define('CSS_URL', '/css/');
define('JS_URL', '/js/');
?>
<!doctype html>
<html lang="en">
<head>
    <title>500 - This is embarrassing</title>
    <?php require_once('../includes/meta.php') ?>
</head>
<body class="mdl-demo mdl-color--grey-100 mdl-color-text--grey-700 mdl-base">
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <main class="mdl-layout__content homescreen">
        <div class="home-login">
            <img src="https://emojipedia-us.s3.dualstack.us-west-1.amazonaws.com/thumbs/120/google/223/grimacing-face_1f62c.png" width="200">
            <h1 style="color: white">500 - Internal Error</h1>
            <h3 style="text-align: center; color: white;">This is embarrassing.</h3>
        </div>
    </main>
</div>
<?php require_once('../includes/bottom-scripts.php') ?>
</body>
</html>

