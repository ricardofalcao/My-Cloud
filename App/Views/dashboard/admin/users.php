<?php

use Core\View;

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

                            <? foreach($users as $user) { ?>
                                <tr>
                                    <td class="py-3 pl-4">
                                        <? echo $user['username'] ?>
                                    </td>
                                    <td class="py-3">
                                        <? echo $user['name'] ?>
                                    </td>

                                    <td style="vertical-align: middle; width: 1px;">
                                        <a class="button is-small is-black is-inverted" onclick="openDelete(event, <? echo $user['id'] ?>, '<? echo $user['name'] ?>')">
                                            <span class="icon">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            <? } ?>
                        </table>
                    </div>

                    <div>
                        <span class="icon">
                            <i class="fas fa-circle-plus"></i>
                        </span>
                    </div>


                    <div class="column is-three-fifths">
                        <div class="container is-fluid p-0 mx-3">
                            <form>
                                <h1 class="is-size-4 has-text-weight-bold mb-4">Adicionar utilizador</h1>
                                <div class="field is-horizontal">
                                    <div class="field-body">
                                        <div class="field">
                                            <label for="" class="label">Username</label>
                                            <p class="control has-icons-left is-expanded">
                                                <input type="email" placeholder="e.g. Notorious B.I.G." class="input"
                                                       required>
                                                <span class="icon is-small is-left"><i class="fa fa-user"></i></span>
                                            </p>
                                        </div>

                                        <div class="field">
                                            <label for="" class="label">Nome completo</label>
                                            <p class="control has-icons-left is-expanded">
                                                <input type="password" placeholder="e.g. Adelino Passas" class="input"
                                                       required>
                                                <span class="icon is-small is-left">
                                                    <i class="fa fa-address-book"></i>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="field is-horizontal">
                                    <div class="field-body">

                                        <div class="field">
                                            <label for="" class="label">Password</label>
                                            <div class="control has-icons-left">
                                                <input type="password" placeholder="*******" class="input" required>
                                                <span class="icon is-small is-left">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="field">
                                            <label for="" class="label">Confirmar Password</label>
                                            <div class="control has-icons-left">
                                                <input type="password" placeholder="*******" class="input" required>
                                                <span class="icon is-small is-left">
                                                    <i class="fa fa-lock"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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

<script src="/assets/js/admin_users.js"></script>
<script src="/assets/js/modal.js"></script>
</body>

</html>
