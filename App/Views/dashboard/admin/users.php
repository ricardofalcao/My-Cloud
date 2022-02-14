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
            View::render('components/drive/sidebar.php', [
                'sidebar_current_id' => '',
                'count' => [],
            ]);
            ?>


            <div class="column">
                <div class="is-flex is-align-items-center ml-4 mt-4 mb-4">
                    <!-- Conteudo -->
                </div>
            </div>
        </div>
    </main>
</div>
</body>

</html>
