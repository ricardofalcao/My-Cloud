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
                'sidebar_current_id' => 'user_profile'
            ]);
            ?>


            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex-grow-1 is-scrollable">
                    <div class="column is-one-fifth my-4 mx-5">
                        <h1 class="is-size-4 has-text-weight-bold mb-4">Alterar password</h1>
                        <div class="field">
                            <label for="" class="label mt-4">Nova password</label>
                            <div class="control has-icons-left">
                                <input type="password" placeholder="*******" class="input" required>
                                <span class="icon is-small is-left">
                            <i class="fa fa-lock"></i>
                          </span>
                            </div>
                        </div>
                        <div class="field">
                            <label for="" class="label mt-4">Confirmar password</label>
                            <div class="control has-icons-left mb-6">
                                <input type="password" placeholder="*******" class="input" required>
                                <span class="icon is-small is-left">
                            <i class="fa fa-lock"></i>
                          </span>
                            </div>
                        </div>
                        <div class="field">
                            <button class="button is-block is-fullwidth is-primary is-medium mb-6">
                                Submeter
                            </button>
                        </div>

                        <hr>

                        <h1 class="is-size-4 has-text-weight-bold mb-4">Alterar nome</h1>
                        <div class="field">
                            <label for="" class="label mt-4">Novo nome</label>
                            <div class="control has-icons-left mb-6">
                                <input type="email" placeholder="e.g. Notorious B.I.G." class="input" required>
                                <span class="icon is-small is-left">
                            <i class="fa fa-user"></i>
                          </span>
                            </div>
                        </div>
                        <div class="field">
                            <button class="button is-block is-fullwidth is-primary is-medium">
                                Submeter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>

</html>
