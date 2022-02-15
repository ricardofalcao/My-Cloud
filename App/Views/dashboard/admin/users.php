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


            <div class="column">
                <div class="is-flex is-align-items-center ml-4 mt-4 mb-4">
                    <div class="column">
                        <table class="table is-fullwidth">
                            <tr class="has-text-grey-light">
                                <td></td>
                                <td>Username</td>
                                <td>Nome Completo</td>
                                <td>Ações</td>
                            </tr>

                            <tr>
                                <td class="image_text">
                                    <figure class="image is-48x48">
                                        <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
                                    </figure>
                                </td>
                                <td>
                                    ricardofalcao
                                </td>
                                <td>
                                    Ricardo Falcão
                                </td>

                                <td>
                                    <button class="button is-small is-black is-inverted">
                                    <span class="icon">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    </button>

                                </td>
                            </tr>
                        </table>

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
        </div>
    </main>
</div>
</body>

</html>
