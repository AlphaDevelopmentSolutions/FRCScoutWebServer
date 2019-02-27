<?php

if(loggedIn())
    echo '<p style="position: absolute; right: 3em;">Hello, ' . $user->FirstName . '<br> <a href="/logout.php?url=' . str_replace('&', '%26', $_SERVER['REQUEST_URI']) . '">Logout</a></p>';

else
    echo
        '
            <form style="position: absolute; right: 3em;" action="login.php" method="post">
                <input type="text" name="username" placeholder="username">          
                <input type="password" name="password" placeholder="password">         
                <input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">         
                <button type="submit">Login</button>
            </form>
        ';

?>