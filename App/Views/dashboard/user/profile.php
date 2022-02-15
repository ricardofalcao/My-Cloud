<?php

use Core\Request;
use Core\View;

if (!isset($errors)) {
    $errors = [];
}

function has_error($errors, $key)
{
    return array_key_exists($key, $errors);
}

function get_error($errors, $key)
{
    return has_error($errors, $key) ? $errors[$key] : null;
}

$user = Request::get('user');

?>

<!DOCTYPE html>
<html lang="en">

<?php

View::render('components/head.php');

?>

<body>
<div>
    <?php
    View::render('components/base.php');
    ?>

    <?php
    View::render('components/drive/navbar.php', [
        'showSearch' => false,
    ]);
    ?>

    <main class="hero is-fullheight-with-navbar">
        <div class="columns is-gapless is-flex-grow-1">
            <?php
            View::render('components/drive/sidebar_dashboard.php', [
                'sidebar_current_id' => 'user_profile'
            ]);
            ?>


            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex-grow-1 is-scrollable">
                    <div class="column is-one-third my-4 mx-5">
                        <form action="/dashboard/user/profile/password" method="POST">
                            <h1 class="is-size-4 has-text-weight-bold mb-4">Alterar password</h1>
                            <div class="field">
                                <label for="password" class="label">Nova password</label>
                                <div class="control has-icons-left">
                                    <input id="password" name="password" type="password" placeholder="*******" class="input <? echo has_error($errors, 'password') ? 'is-danger' : '' ?>" required>
                                    <span class="icon is-small is-left">
                            <i class="fa fa-lock"></i>
                          </span>
                                </div>
                                <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, 'confirmPassword') ?: '<wbr/>' ?></p>
                            </div>
                            <div class="field">
                                <label for="confirmPassword" class="label">Confirmar password</label>
                                <div class="control has-icons-left">
                                    <input id="confirmPassword" name="confirmPassword" type="password" placeholder="*******" class="input <? echo has_error($errors, 'confirmPassword') ? 'is-danger' : '' ?>" required>
                                    <span class="icon is-small is-left">
                            <i class="fa fa-lock"></i>
                          </span>
                                </div>
                                <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, 'confirmPassword') ?: '<wbr/>' ?></p>
                            </div>
                            <div class="field">
                                <button class="button is-block is-primary mb-6">
                                    Submeter
                                </button>
                            </div>
                        </form>

                        <form onsubmit="updateName(event)">
                            <h1 class="is-size-4 has-text-weight-bold mb-4">Alterar nome</h1>
                            <div class="field">
                                <label for="name" class="label">Novo nome</label>
                                <div class="control has-icons-left">
                                    <input name="name" id="name" type="text" placeholder="João Exemplo" class="input <? echo has_error($errors, 'name') ? 'is-danger' : '' ?>"
                                           value="<? echo $name ?? $user['name'] ?>" required>
                                    <span class="icon is-small is-left">
                            <i class="fa fa-user"></i>
                          </span>
                                </div>
                                <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, 'name') ?: '<wbr/>' ?></p>
                            </div>
                            <div class="field">
                                <button type="submit" class="button is-block is-primary mb-6">
                                    Submeter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="/assets/js/profile.js"></script>
</body>

</html>
