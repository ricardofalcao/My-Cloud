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

            <form class="form" action="/auth/register" method="POST">
                <label for="username">Username</label>
                <div class="form-input-text">
                    <i class="fas fa-user"></i>
                    <input name="username" type="text" placeholder="Username" />
                </div>

                <label for="name">Name</label>
                <div class="form-input-text">
                    <i class="fas fa-user"></i>
                    <input name="name" type="text" placeholder="Nome" />
                </div>

                <label for="password">Password</label>
                <div class="form-input-text">
                    <i class="fas fa-key"></i>
                    <input name="password" type="password" placeholder="Password" />
                </div>

                <label for="confirm-password">Confirm Password</label>
                <div class="form-input-text">
                    <i class="fas fa-key"></i>
                    <input name="confirm-password" type="password" placeholder="Confirm Password" />
                </div>

                <?
                    if (isset($error)) {
                ?>
                        <p><? echo $error; ?></p>
                <?
                    }
                ?>

                <button class="form-submit" type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>