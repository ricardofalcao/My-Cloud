<!DOCTYPE html>
<html lang="en">

<?php

use Core\View;

View::render('components/head.php');

?>

<body>
    <div id="login">
        <div class="login-card">
            <h1 class="login-title">
                <i class="fas fa-cloud"></i>
                <span class="login-title-text">My Cloud</span>
            </h1>

            <form class="form" action="/auth/login" method="POST">
                <label for="username">Username</label>
                <div class="form-input-text">
                    <i class="fas fa-user"></i>
                    <input name="username" type="text" placeholder="Digite o username" />
                </div>

                <label for="password">Password</label>
                <div class="form-input-text">
                    <i class="fas fa-key"></i>
                    <input name="password" type="password" placeholder="Password" />
                </div>

                <button class="form-submit" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>