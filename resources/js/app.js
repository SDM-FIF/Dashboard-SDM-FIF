import './bootstrap';
import Chart from 'chart.js/auto';

// Donut chart Dosen
new Chart(document.getElementById('chartDosen'), {
    type: 'doughnut',
    data: {
        labels: ['Sarjana', 'Magister', 'Doktor'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

// Donut chart TPA
new Chart(document.getElementById('chartTPA'), {
    type: 'doughnut',
    data: {
        labels: ['SEKPIM','LAA','SDM','KEMAHASISWAAN','LABORAN','LOGISTIK','PRODI'],
        datasets: [{
            data: [30,30,30,15,25,10,30],
            backgroundColor: ['#991b1b','#b91c1c','#dc2626','#ef4444','#f87171','#fecaca','#fca5a5']
        }]
    },
});

// Bar chart Kompetisi
new Chart(document.getElementById('chartKompetisi'), {
    type: 'bar',
    data: {
        labels: ['RPL','DS','IT','IF'],
        datasets: [{
            label: 'Jumlah',
            data: [40,20,60,80],
            backgroundColor: '#b91c1c'
        }]
    },
    options: {
        indexAxis: 'y'
    }
});
