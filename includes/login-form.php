<?php

if(loggedIn())
{
    ?>
    <form action="/logout.php?" style="margin: 1.5em; position: absolute !important; bottom: 0 !important;" method="post">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 199px;">
            Logout
        </button>
        <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
    </form>
<?php
}

else
{
?>
    <form  action="login.php" method="post" style="padding: 1.5em; position: absolute !important; bottom: 0 !important; width: 197px;">
        <div class="mdl-textfield mdl-js-textfield">
            <input class="mdl-textfield__input" type="text" name="username" style="background-color: white !important; color: black; ">
            <label class="mdl-textfield__label" for="username" style="padding-left: .5em; padding-right: .5em; ">Username</label>
        </div>
        <br>
        <div class="mdl-textfield mdl-js-textfield">
            <input class="mdl-textfield__input" type="password" name="password" style="background-color: white !important; color: black;">
            <label class="mdl-textfield__label" for="password" style="padding-left: .5em; padding-right: .5em; ">Password</label>
        </div>    <br>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;">
            Login
        </button>
        <input type="hidden" name="url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
    </form>
<?php
}
?>