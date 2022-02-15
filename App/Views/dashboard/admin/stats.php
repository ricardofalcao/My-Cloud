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
            <div class="columns">
                <aside class="column is-2 menu is-full is-fullheight left-border">
                    <ul class="menu-list">
                        <p class="menu-label ml-4 mt-4" style="font-size: 15px;">
                            Utilizador
                        </p>
                        <li>
                            <a href="#" class="is-flex is-align-items-center py-4 px-4">
                                <span class="icon mr-2">
                                    <i class="fas fa-user is-size-5"></i>
                                </span>
                                <span class="name is-size-5">Perfil</span>
                            </a>
                        </li>


                        <li>
                            <a href="#" class="is-flex is-align-items-center py-4 px-4">
                                <span class="icon mr-2">
                                    <i class="fas fa-chart-pie is-size-5"></i>
                                </span>
                                <span class="name is-size-5">Estatísticas</span>
                            </a>
                        </li>
                    </ul>
                    <p class="menu-label ml-4 mt-4" style="font-size: 15px;">
                        Administrador
                    </p>
                    <ul class="menu-list">
                        <li>
                            <a href="#" class=" is-flex is-align-items-center py-4 px-4">
                                <span class="icon mr-2">
                                    <i class="fas fa-users is-size-5"></i>
                                </span>
                                <span class="name is-size-5">Utilizadores</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="has-background-grey-lighter is-flex is-align-items-center py-4 px-4">
                                <span class="icon mr-2">
                                    <i class="fas fa-chart-pie is-size-5"></i>
                                </span>
                                <span class="name is-size-5">Estatísticas</span>
                            </a>
                        </li>
                    </ul>
                </aside>

                <div class="column is-two-thirds ml-4 mt-4">
                    <h1 class="is-size-4 has-text-weight-bold mb-5">Utilização de espaço total</h1>

                    <canvas id="Chart_disk" width="300" height="300"></canvas>
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
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    }
                                }
                            }
                        });
                    </script>
                    <h1 class="is-size-4 has-text-weight-bold my-5">Sistema</h1>
                    <div class="columns is-four-fifths">
                        <div class="column">
                            <h1 class="has-text-weight-bold">CPU</h1>

                            <canvas id="Chart_cpu" width="300" height="300"></canvas>
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
                                        //cutoutPercentage: 40,
                                        responsive: false,

                                    },
                                });
                            </script>
                        </div>
                        <div class="column">
                            <h1 class="has-text-weight-bold">Memória</h1>

                            <canvas id="Chart_mem" width="300" height="300"></canvas>
                            <script>
                                var ctx = document.getElementById("Chart_mem");
                                var myChart = new Chart(ctx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Utilização de RAM', ],
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
                                        //cutoutPercentage: 40,
                                        responsive: false,

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
