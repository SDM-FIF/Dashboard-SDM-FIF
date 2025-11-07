import './bootstrap';
import Chart from 'chart.js/auto';

// 🥇 Jumlah Juara
new Chart(document.getElementById('jumlahJuara'), {
    type: 'doughnut',
    data: {
        labels: chartData.jumlahJuara.labels,
        datasets: [{
            data: chartData.jumlahJuara.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

// 🎓 Jumlah Mahasiswa
new Chart(document.getElementById('jumlahMahasiswa'), {
    type: 'doughnut',
    data: {
        labels: chartData.jumlahMahasiswa.labels,
        datasets: [{
            data: chartData.jumlahMahasiswa.data,
            backgroundColor: ['#b91c1c', '#ef4444']
        }]
    }
});

// 👩‍🎓 Status Mahasiswa
new Chart(document.getElementById('statusMahasiswa'), {
    type: 'doughnut',
    data: {
        labels: chartData.statusMahasiswa.labels,
        datasets: [{
            data: chartData.statusMahasiswa.data,
            backgroundColor: ['#991b1b','#b91c1c', '#ef4444']
        }]
    }
});

// 🏆 Jumlah Kompetisi
new Chart(document.getElementById('jumlahKompetisi'), {
    type: 'bar',
    data: {
        labels: chartData.jumlahKompetisi.labels,
        datasets: [{
            label: 'Jumlah Kompetisi per Tingkat',
            data: chartData.jumlahKompetisi.data,
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171', '#fca5a5', '#fecaca']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Distribusi Kompetisi Berdasarkan Tingkat' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

// 🧑‍🏫 Jumlah Kompetisi 2
new Chart(document.getElementById('jumlahKompetisi2'), {
    type: 'bar',
    data: {
        labels: chartData.jumlahKompetisi2.labels,
        datasets: [{
            label: 'Jumlah',
            data: chartData.jumlahKompetisi2.data,
            backgroundColor: '#b91c1c'
        }]
    },
    options: { indexAxis: 'y' }
});
