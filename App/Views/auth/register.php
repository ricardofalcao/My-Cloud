<!DOCTYPE html>
<html lang="en">

<?php

use Core\View;

View::render('components/head.php');

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

$inputs = [
    [
        "id" => "username",
        "label" => "Username",
        "placeholder" => "joaoexemplo",
        "type" => "text",
        "icon" => "fa fa-user"
    ],
    [
        "id" => "name",
        "label" => "Nome completo",
        "placeholder" => "João Exemplo",
        "type" => "text",
        "icon" => "fa fa-signature"
    ],
    [
        "id" => "password",
        "label" => "Password",
        "placeholder" => "*******",
        "type" => "password",
        "icon" => "fa fa-key"
    ],
    [
        "id" => "confirmPassword",
        "label" => "Confirmar Password",
        "placeholder" => "*******",
        "type" => "password",
        "icon" => "fa fa-key"
    ]
]

?>

<body>


<section class="hero is-fullheight"
         style="background-image: url('/assets/images/login_bg.png'); background-size: cover; background-position: center;">
    <div class="hero-body">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-7-tablet is-6-desktop is-4-widescreen">
                    <form action="/auth/register" method="POST" class="box">
                        <span class="icon-text p-6 is-flex is-justify-content-center is-align-items-center">
                            <span class="fas fa-2x">
                                <i class="fas fa-cloud mr-3"></i>
                            </span>

                            <h1 class="title has-text-black has-text-weight-normal is-size-3">My Cloud</h1>
                        </span>

                        <?
                        foreach ($inputs as $input) {
                            ?>
                            <div class="field">
                                <label for="<? echo $input['id'] ?>" class="label"><? echo $input['label'] ?></label>
                                <div class="control has-icons-left">
                                    <input
                                            id="<? echo $input['id'] ?>"
                                            name="<? echo $input['id'] ?>"
                                            type="<? echo $input['type'] ?>"
                                            placeholder="<? echo $input['placeholder'] ?>"
                                            class="input <? echo has_error($errors, $input['id']) ? 'is-danger' : '' ?>"
                                            value="<? echo isset(${$input['id']}) ? ${$input['id']} : '' ?>">
                                    <span class="icon is-small is-left">
                                        <i class="fa <? echo $input['icon'] ?>"></i>
                                    </span>
                                </div>
                                <p class="mt-2 has-text-danger is-size-7"><? echo get_error($errors, $input['id']) ?: '<wbr/>' ?></p>
                            </div>
                            <?
                        }
                        ?>

                        <div class="field mt-5">
                            <button class="button is-block is-fullwidth is-primary is-medium">
                                Register
                            </button>
                        </div>
                        <nav class="level mt-5">
                            <div class="level-item has-text-centered">
                                <div>
                                    <a href="/auth/login">Got an account?</a>
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