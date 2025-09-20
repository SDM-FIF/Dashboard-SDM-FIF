import './bootstrap';
import Chart from 'chart.js/auto';


new Chart(document.getElementById('dosenProdi'), {
    type: 'doughnut',
    data: {
        labels: ['IF', 'IT', 'PJJ-IF'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

new Chart(document.getElementById('dosenKK'), {
    type: 'doughnut',
    data: {
        labels: ['DSIS', 'CITI', 'SEAL'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

new Chart(document.getElementById('pendDosen'), {
    type: 'doughnut',
    data: {
        labels: ['S1', 'S2', 'S3', 'ONGOING'],
        datasets: [{
            data: [110, 30, 10, 9],
            backgroundColor: ['#991b1b','#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

new Chart(document.getElementById('jfaDosen'), {
    type: 'bar',
    data: {
        labels: ['L', 'NJFA', 'AA', 'LK', 'GB'],
        datasets: [{
            label: 'Jumlah Dosen',
            data: [30, 40, 20, 45, 5],
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

new Chart(document.getElementById('statusDosen'), {
    type: 'bar',
    data: {
        labels: ['PEGAWAI TETAP','PROFESIONAL FULL TIME','PROFESIONAL PART TIME','PERBANTUAN LLDIKTI'],
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