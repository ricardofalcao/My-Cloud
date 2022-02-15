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

const cpuDiv = document.getElementById("chart_cpu");
const chartCpu = new Chart(cpuDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilização',],
        datasets: [{
            label: '# of Tomatoes',
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
                filter: function (context) {
                    return context.label !== '';
                },
                callbacks: {
                    title: function(context) {
                        return context.length > 0 && context[0].label;
                    },
                    label: function(context) {
                        return `${context.parsed} %`;
                    }
                }
            }
        }
    },
});


const memoryDiv = document.getElementById("chart_memory");
const chartMemory = new Chart(memoryDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilizado','Livre'],
        datasets: [{
            label: '# of Tomatoes',
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

async function refreshStats() {
    const result = await fetch(`/dashboard/admin/stats/api`);
    const response = await result.json();

    chartDisk.data.datasets[0].data = [response.disk.used, response.disk.total - response.disk.used];
    chartDisk.update();

    chartCpu.data.datasets[0].data = [(response.cpu * 100).toFixed(1), (100 * (1 -  response.cpu)).toFixed(1)];
    chartCpu.update();

    chartMemory.data.datasets[0].data = [response.memory.used, response.memory.total - response.memory.used];
    chartMemory.update();
}

setInterval(refreshStats, 1000);