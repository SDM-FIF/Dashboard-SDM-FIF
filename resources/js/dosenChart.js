import './bootstrap';
import Chart from 'chart.js/auto';



// 🔸 1. Chart Jumlah Dosen per Prodi
new Chart(document.getElementById('dosenProdi'), {
    type: 'doughnut',
    data: {
        labels: chartData.prodiDosen.labels,
        datasets: [{
            data: chartData.prodiDosen.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171', '#fca5a5', '#fecaca']
        }]
    }
});

// 🔸 2. Chart Jumlah Dosen per Lokasi Kerja
new Chart(document.getElementById('dosenKK'), {
    type: 'doughnut',
    data: {
        labels: chartData.lokasiDosen.labels,
        datasets: [{
            data: chartData.lokasiDosen.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

// 🔸 3. Chart Jumlah Dosen per Jabatan Fungsional (JFA)
new Chart(document.getElementById('jfaDosen'), {
    type: 'bar',
    data: {
        labels: chartData.jabatanDosen.labels,
        datasets: [{
            label: 'Jumlah Dosen',
            data: chartData.jabatanDosen.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171', '#fca5a5', '#fecaca']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Jabatan Fungsional Dosen'
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// 🔸 4. Chart Status Dosen (Aktif, Non-Aktif, Cuti)
new Chart(document.getElementById('statusDosen'), {
    type: 'bar',
    data: {
        labels: chartData.statusDosen.labels,
        datasets: [{
            label: 'Jumlah',
            data: chartData.statusDosen.data,
            backgroundColor: '#b91c1c'
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            title: {
                display: true,
                text: 'Status Kepegawaian Dosen'
            },
            legend: { display: false }
        }
    }
});
