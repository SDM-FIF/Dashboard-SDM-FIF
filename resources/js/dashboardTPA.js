import './bootstrap';
import Chart from 'chart.js/auto';

//MASIH DUMMY DISESUAIKAN SETELAH ADA CONTROLLER

new Chart(document.getElementById('jumlahJuara'), {
    type: 'doughnut',
    data: {
        labels: ['Juara 1', 'Juara 2', 'Juara 3'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

new Chart(document.getElementById('jumlahMahasiswa'), {
    type: 'doughnut',
    data: {
        labels: ['AKADEMIK', 'NON-AKADEMIK'],
        datasets: [{
            data: [110, 30],
            backgroundColor: ['#b91c1c', '#ef4444']
        }]
    }
});

new Chart(document.getElementById('statusMahasiswa'), {
    type: 'doughnut',
    data: {
        labels: ['AKTIF', 'CUTI','TIDAK AKTIF'],
        datasets: [{
            data: [110, 30, 10],
            backgroundColor: ['#991b1b','#b91c1c', '#ef4444', '#f87171']
        }]
    }
});

new Chart(document.getElementById('jumlahKompetisi'), {
    type: 'bar',
    data: {
        labels: ['RPL', 'IT', 'DS', 'IF', 'PJJ'],
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

new Chart(document.getElementById('jumlahKompetisi2'), {
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