import './bootstrap';
import Chart from 'chart.js/auto';



// 🔸 1. Jumlah TPA per Status Pegawai
new Chart(document.getElementById('statusTpa'), {
    type: 'bar',
    data: {
        labels: chartData.statusTpa.labels,
        datasets: [{
            label: 'Jumlah',
            data: chartData.statusTpa.data,
            backgroundColor: '#b91c1c'
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            title: {
                display: true,
                text: 'Status Pegawai TPA'
            },
            legend: { display: false }
        }
    }
});

// 🔸 2. Jumlah TPA per Lokasi Kerja
new Chart(document.getElementById('lokasiTpa'), {
    type: 'doughnut',
    data: {
        labels: chartData.lokasiTpa.labels,
        datasets: [{
            data: chartData.lokasiTpa.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171', '#fca5a5']
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Lokasi Kerja TPA'
            }
        }
    }
});

// 🔸 3. Jumlah TPA per Pendidikan Terakhir
new Chart(document.getElementById('pendidikanTpa'), {
    type: 'doughnut',
    data: {
        labels: chartData.pendidikanTpa.labels,
        datasets: [{
            data: chartData.pendidikanTpa.data,
            backgroundColor: ['#991b1b', '#b91c1c', '#ef4444', '#f87171']
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Pendidikan Terakhir TPA'
            }
        }
    }
});

// 🔸 4. Jumlah TPA per Pangkat/Golongan
if (document.getElementById('pangkatTpa')) {
    new Chart(document.getElementById('pangkatTpa'), {
        type: 'doughnut',
        data: {
            labels: chartData.pangkatTpa.labels,
            datasets: [{
                data: chartData.pangkatTpa.data,
                backgroundColor: [
                    '#b91c1c',
                    '#ef4444',
                    '#f87171',
                    '#fca5a5',
                    '#fecaca'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Pangkat / Golongan TPA'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

