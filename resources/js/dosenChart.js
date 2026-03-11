import './bootstrap';
import Chart from 'chart.js/auto';

// Doughnut chart dosenProdi - Menggunakan warna primer (Blue, Emerald, Amber)
new Chart(document.getElementById('dosenProdi'), {
    type: 'doughnut',
    data: {
        labels: ['IF', 'IT', 'PJJ-IF'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b']
        }]
    }
});

// Doughnut chart dosenKK - Menggunakan warna dari palet TPA agar bervariasi tapi tetap senada
new Chart(document.getElementById('dosenKK'), {
    type: 'doughnut',
    data: {
        labels: ['DSIS', 'CITI', 'SEAL'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#4f46e5', '#06b6d4', '#8b5cf6']
        }]
    }
});

// Doughnut chart pendDosen - 4 kategori, menggunakan campuran palet
new Chart(document.getElementById('pendDosen'), {
    type: 'doughnut',
    data: {
        labels: ['S1', 'S2', 'S3', 'ONGOING'],
        datasets: [{
            data: [110, 30, 10, 9],
            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899']
        }]
    }
});

// Bar chart jfaDosen - Menggunakan 5 warna dari palet pelangi yang soft
new Chart(document.getElementById('jfaDosen'), {
    type: 'bar',
    data: {
        labels: ['L', 'NJFA', 'AA', 'LK', 'GB'],
        datasets: [{
            label: 'Jumlah Dosen',
            data: [30, 40, 20, 45, 5],
            backgroundColor: [
                '#4f46e5', // Indigo
                '#06b6d4', // Cyan
                '#8b5cf6', // Violet
                '#ec4899', // Pink
                '#f97316'  // Orange
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'JFA Dosen'
            },
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Bar chart statusDosen - Menggunakan warna biru solid agar konsisten dengan chartKompetisi
new Chart(document.getElementById('statusDosen'), {
    type: 'bar',
    data: {
        labels: ['PEGAWAI TETAP','PROFESIONAL FULL TIME','PROFESIONAL PART TIME','PERBANTUAN LLDIKTI'],
        datasets: [{
            label: 'Jumlah',
            data: [40,20,60,80],
            backgroundColor: '#6366f1' // Indigo
        }]
    },
    options: {
        indexAxis: 'y',
        plugins: {
            legend: {
                display: false // Ditambahkan agar legend disembunyikan (konsisten dengan bar chart sebelumnya)
            }
        }
    }
});