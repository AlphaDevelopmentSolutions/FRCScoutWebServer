<?php

if(loggedIn())
    echo
        '
            <form action="/logout.php?" style="margin: 1.5em; position: absolute !important; right: 0 !important;" method="post">
                <h6 style="margin: 0;">Hello, ' . $user->FirstName . ' ' . $user->LastName . '</h6>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;">
                  Logout
                </button>
                <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">
            </form>
         ';

else
    echo
        '
            <form style="margin: 21px; position: absolute; right: 0;" action="login.php" method="post">
                <div class="mdl-textfield mdl-js-textfield" style="width: 100px; margin-right: .5em;">
                    <input class="mdl-textfield__input" type="text" name="username" style="background-color: white !important; color: black; ">
                    <label class="mdl-textfield__label" for="username" style="padding-left: .5em; padding-right: .5em; ">Username</label>
                </div>
                <div class="mdl-textfield mdl-js-textfield" style="width: 100px; margin-left: .5em;">
                    <input class="mdl-textfield__input" type="password" name="password" style="background-color: white !important; color: black;">
                    <label class="mdl-textfield__label" for="password" style="padding-left: .5em; padding-right: .5em; ">Password</label>
                </div>    <br>   
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;">
                  Login
                </button>
                <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">
            </form>
        ';

?>