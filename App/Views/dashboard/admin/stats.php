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
                'sidebar_current_id' => 'admin_stats'
            ]);
            ?>

            <div class="column is-flex is-flex-direction-column">
                <div class="is-flex-grow-1 is-scrollable">
                    <div class="columns m-4 is-tablet">
                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">Espaço total</h1>

                            <canvas id="chart_disk"></canvas>
                        </div>
                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">CPU</h1>

                            <canvas id="chart_cpu"></canvas>
                        </div>
                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">Memória</h1>

                            <canvas id="chart_memory"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>

<script src="/assets/js/admin_stats.js"></script>
</body>

</html>
