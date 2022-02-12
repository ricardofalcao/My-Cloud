<!DOCTYPE html>
<html lang="en">

<?php

use Core\View;

View::render('components/head.php');

if (!isset($errors)) {
    $errors = [];
}

function has_error($errors, $key) {
    return array_key_exists($key, $errors);
}

function get_error($errors, $key) {
    return has_error($errors, $key) ? $errors[$key] : null;
}

?>

<body>


<section class="hero is-fullheight" style="background-image: url('/assets/images/login_bg.png'); background-size: cover; background-position: center;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-7-tablet is-6-desktop is-4-widescreen">
                    <form action="/auth/login" method="POST" class="box">
                        <span class="icon-text p-6 is-flex is-justify-content-center is-align-items-center">
                            <span class="fas fa-2x">
                                <i class="fas fa-cloud mr-3"></i>
                            </span>

                            <h1 class="title has-text-black has-text-weight-normal is-size-3">My Cloud</h1>
                        </span>

                        <div class="field">
                            <label for="username" class="label">Username</label>
                            <div class="control has-icons-left">
                                <input id="username" name="username" type="text" placeholder="e.g. Notorious B.I.G." class="input <? echo has_error($errors,'username') ? 'is-danger' : 'is-primary' ?>" required value="<? echo isset($username) ? $username : '' ?>">
                                <span class="icon is-small is-left">
                                <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, 'username') ?: '<wbr/>' ?></p>
                        </div>
                        <div class="field">
                            <label for="password" class="label">Password</label>
                            <div class="control has-icons-left">
                                <input id="password" name="password" type="password" placeholder="*******" class="input <? echo has_error($errors,'password') ? 'is-danger' : 'is-primary' ?>" required>
                                <span class="icon is-small is-left">
                                <i class="fa fa-lock"></i>
                                </span>
                            </div>
                            <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, 'password') ?: '<wbr/>' ?></p>
                        </div>
                        <div class="field">
                            <label for="remember" class="checkbox">
                                <input id="remember" name="remember" type="checkbox">
                                Remember me
                            </label>
                        </div>
                        <div class="field mt-5">
                            <button class="button is-block is-fullwidth is-primary is-medium">
                                Login
                            </button>
                        </div>
                        <nav class="level mt-5">
                            <div class="level-item has-text-centered">
                                <div>
                                    <a href="#">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="level-item has-text-centered">
                                <div>
                                    <a href="#">Create an Account</a>
                                </div>
                            </div>
                        </nav>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>


</body>

</html>