<?php

use Core\Request;
use Core\View;

$user = Request::get('user');
$total_space = 2.4e9;
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
                'sidebar_current_id' => 'user_stats'
            ]);
            ?>


            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex-grow-1 is-scrollable">
                    <div class="columns m-4 is-tablet">

                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">Espaço total</h1>

                            <canvas id="chart_disk"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="/assets/js/user_stats.js"></script>
<script>
    refreshStats(<? echo $total_space ?>, <? echo $user['quota'] ?>)
</script>
</body>

</html>
