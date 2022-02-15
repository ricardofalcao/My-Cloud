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

                            <canvas id="Chart_disk"></canvas>
                            <script>
                                var ctx = document.getElementById("Chart_disk");
                                var myChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Utilização de espaço', 'Livre'],
                                        datasets: [{

                                            data: [36, (100 - 36)],
                                            backgroundColor: [
                                                'rgba(0, 150, 136, 1)',
                                                'rgba(255, 255, 255, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(0,0,0,1)',
                                                'rgba(0, 0, 0, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                    }
                                });
                            </script>
                        </div>
                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">CPU</h1>

                            <canvas id="Chart_cpu"></canvas>
                            <script>
                                var ctx = document.getElementById("Chart_cpu");
                                var myChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Utilização de CPU %',],
                                        datasets: [{
                                            label: '# of Tomatoes',
                                            data: [24, (100 - 24)],
                                            backgroundColor: [
                                                'rgba(0, 150, 136, 1)',
                                                'rgba(255, 255, 255, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(0,0,0,1)',
                                                'rgba(0, 0, 0, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,

                                    },
                                });
                            </script>
                        </div>
                        <div class="column is-one-third">
                            <h1 class="has-text-weight-bold">Memória</h1>

                            <canvas id="Chart_mem"></canvas>
                            <script>
                                var ctx = document.getElementById("Chart_mem");
                                var myChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Utilização de RAM',],
                                        datasets: [{
                                            label: '# of Tomatoes',
                                            data: [43, (100 - 43)],
                                            backgroundColor: [
                                                'rgba(0, 150, 136, 1)',
                                                'rgba(255, 255, 255, 1)'
                                            ],
                                            borderColor: [
                                                'rgba(0,0,0,1)',
                                                'rgba(0, 0, 0, 1)'
                                            ],
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                    },
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</div>
</body>

</html>
