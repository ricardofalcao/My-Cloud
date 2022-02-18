<?php

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
];


$total = count($inputs);

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
                'sidebar_current_id' => 'admin_users'
            ]);
            ?>


            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex-grow-1 is-scrollable">
                    <div class="table-container">
                        <table class="table is-fullwidth">
                            <tr class="has-text-grey-light">
                                <td class="py-3 pl-4">Username</td>
                                <td class="py-3">Nome Completo</td>
                                <td class="py-3 pr-6">Ações</td>
                            </tr>

                            <?php foreach ($users as $user) { ?>
                                <tr>
                                    <td class="py-3 pl-4">
                                        <?php echo $user['username'] ?>
                                    </td>
                                    <td class="py-3">
                                        <?php echo $user['name'] ?>
                                    </td>

                                    <td style="vertical-align: middle; width: 1px;">
                                        <a class="button is-small is-black is-inverted"
                                           onclick="openDelete(event, <?php echo $user['id'] ?>, '<?php echo $user['name'] ?>')">
                                            <span class="icon">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>

                    <div>
                        <span class="icon">
                            <i class="fas fa-circle-plus"></i>
                        </span>
                    </div>


                    <div class="column is-three-fifths">
                        <div class="container is-fluid p-0 mx-3">
                            <form action="dashboard/admin/users" method="POST">
                                <h1 class="is-size-4 has-text-weight-bold mb-4">Adicionar utilizador</h1>
                                <?php
                                for ($i = 0; $i < $total; $i += 2) {
                                    ?>
                                    <div class="field is-horizontal">
                                        <div class="field-body">
                                            <div class="field">
                                                <label for="<?php echo $inputs[$i]['id'] ?>"
                                                       class="label"><?php echo $inputs[$i]['label'] ?></label>
                                                <div class="control has-icons-left">
                                                    <input
                                                            id="<?php echo $inputs[$i]['id'] ?>"
                                                            name="<?php echo $inputs[$i]['id'] ?>"
                                                            type="<?php echo $inputs[$i]['type'] ?>"
                                                            placeholder="<?php echo $inputs[$i]['placeholder'] ?>"
                                                            class="input <?php echo has_error($errors, $inputs[$i]['id']) ? 'is-danger' : '' ?>"
                                                            value="<?php echo isset(${$inputs[$i]['id']}) ? ${$inputs[$i]['id']} : '' ?>">

                                                    <span class="icon is-small is-left">
                                                        <i class="fa <?php echo $inputs[$i]['icon'] ?>"></i>
                                                    </span>
                                                </div>
                                                <p class="mt-2 has-text-danger is-size-7"><?php echo get_error($errors, $inputs[$i]['id']) ?: '<wbr/>' ?></p>
                                            </div>

                                            <?php if ($i + 1 < $total) { ?>
                                                <div class="field">
                                                    <label for="<?php echo $inputs[$i + 1]['id'] ?>"
                                                           class="label"><?php echo $inputs[$i + 1]['label'] ?></label>
                                                    <div class="control has-icons-left">
                                                        <input
                                                                id="<?php echo $inputs[$i + 1]['id'] ?>"
                                                                name="<?php echo $inputs[$i + 1]['id'] ?>"
                                                                type="<?php echo $inputs[$i + 1]['type'] ?>"
                                                                placeholder="<?php echo $inputs[$i + 1]['placeholder'] ?>"
                                                                class="input <?php echo has_error($errors, $inputs[$i + 1]['id']) ? 'is-danger' : '' ?>"
                                                                value="<?php echo isset(${$inputs[$i + 1]['id']}) ? ${$inputs[$i + 1]['id']} : '' ?>">
                                                        <span class="icon is-small is-left">
                                                            <i class="fa <?php echo $inputs[$i + 1]['icon'] ?>"></i>
                                                        </span>
                                                    </div>
                                                    <p class="mt-2 has-text-danger is-size-7"><?php echo get_error($errors, $inputs[$i + 1]['id']) ?: '<wbr/>' ?></p>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                <?php } ?>

                                <div class="field">
                                    <button class="button is-primary">
                                        Submeter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="delete-modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Eliminar '<span data-value="username"></span>'?</p>
            <button class="delete" aria-label="close"></button>
        </header>

        <section class="modal-card-body">
            <p data-value="input">Esta ação é permanente!</p>
        </section>

        <footer class="modal-card-foot is-justify-content-right">
            <button class="button" onclick="closeNearestModal(this)">Cancelar</button>
            <button class="button is-danger" onclick="deleteUser(event)">Eliminar</button>
        </footer>
    </div>
</div>

<script src="assets/js/admin_users.js"></script>
<script src="assets/js/modal.js"></script>
</body>

</html>
