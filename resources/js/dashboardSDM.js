import './bootstrap';
import Chart from 'chart.js/auto';

// Donut chart Dosen - Menggunakan warna primer yang kontras
new Chart(document.getElementById('chartDosen'), {
    type: 'doughnut',
    data: {
        labels: ['Sarjana', 'Magister', 'Doktor'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: [
                '#3b82f6', // Blue
                '#10b981', // Emerald
                '#f59e0b'  // Amber
            ]
        }]
    }
});

// Donut chart TPA - Menggunakan palet pelangi yang soft agar 7 kategori mudah dibedakan
new Chart(document.getElementById('chartTPA'), {
    type: 'doughnut',
    data: {
        labels: ['SEKPIM','LAA','SDM','KEMAHASISWAAN','LABORAN','LOGISTIK','PRODI'],
        datasets: [{
            data: [30,30,30,15,25,10,30],
            backgroundColor: [
                '#4f46e5', // Indigo
                '#06b6d4', // Cyan
                '#8b5cf6', // Violet
                '#ec4899', // Pink
                '#f97316', // Orange
                '#64748b', // Slate
                '#22c55e'  // Green
            ]
        }]
    },
});

// Bar chart Kompetisi - Menggunakan warna biru solid agar terlihat profesional
new Chart(document.getElementById('chartKompetisi'), {
    type: 'bar',
    data: {
        labels: ['RPL','DS','IT','IF'],
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
                display: false // Biasanya bar chart tunggal lebih rapi tanpa legend label
            }
        }
    }
});