const diskDiv = document.getElementById("chart_disk");

const chartDisk = new Chart(diskDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilização de espaço', 'Livre'],
        datasets: [{
            data: [0, 0],
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

const cpuDiv = document.getElementById("chart_cpu");
const chartCpu = new Chart(cpuDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilização de CPU %',],
        datasets: [{
            label: '# of Tomatoes',
            data: [0, 0],
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


const memoryDiv = document.getElementById("chart_memory");
const chartMemory = new Chart(memoryDiv, {
    type: 'doughnut',
    data: {
        labels: ['Utilização de RAM',],
        datasets: [{
            label: '# of Tomatoes',
            data: [0, 0],
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

/*

    User chart

*/

const diskDivUser = document.getElementById("chart_disk_user");
const chartDiskUser = new Chart(diskDivUser, {
    type: 'doughnut',
    data: {
        labels: ['Utilização de espaço', 'Livre'],
        datasets: [{
            data: [0, 0],
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


/*

    Chart Update

*/

async function refreshStats() {
    const result = await fetch(`/dashboard/admin/stats/api`);
    const response = await result.json();

    chartDisk.data.datasets[0].data = [response.disk.used, response.disk.total - response.disk.used];
    chartDisk.update();

    chartCpu.data.datasets[0].data = [response.cpu * 100, 100 * (1 -  response.cpu)];
    chartCpu.update();

    chartMemory.data.datasets[0].data = [response.memory.used, response.memory.total - response.memory.used];
    chartMemory.update();


    chartDiskUser.data.datasets[0].data = [response.disk.used, response.disk.total - response.disk.used];
    chartDiskUser.update();
}

setInterval(refreshStats, 1000);