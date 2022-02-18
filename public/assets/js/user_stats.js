function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};

const diskDiv = document.getElementById("chart_disk");

const chartDisk = new Chart(diskDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilizado', 'Livre'],
        datasets: [{
            data: [0, 1],
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
        plugins: {
            tooltip: {
                callbacks: {
                    title: function(context) {
                        return context[0].label;
                    },
                    label: function(context) {
                        return humanFileSize(context.parsed);
                    }
                }
            }
        }
    },
});



/*

    Chart Update

*/

async function refreshStats(diskUse, diskTotal) {

    chartDisk.data.datasets[0].data = [diskUse, diskTotal - diskUse];
    chartDisk.update();

}
