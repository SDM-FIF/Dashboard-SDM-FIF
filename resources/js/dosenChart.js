import './bootstrap';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(ChartDataLabels);

const colors = [
    '#3b82f6', // Blue
    '#10b981', // Emerald
    '#f59e0b', // Amber
    '#4f46e5', // Indigo
    '#06b6d4', // Cyan
    '#8b5cf6', // Violet
    '#ec4899', // Pink
    '#f97316', // Orange
    '#64748b', // Slate
    '#22c55e'  // Green
];

// 1. Doughnut chart dosenProdi
const prodiData = window.dosenData?.prodi || {};
const prodiLabels = Object.keys(prodiData).length > 0 ? Object.keys(prodiData).map(label => 
    label.replace("Informatika", "IF")
         .replace("Rekayasa Perangkat Lunak", "RPL")
         .replace("Data Sains", "DS")
         .replace("Teknologi Informasi", "IT")
) : ['IF', 'IT', 'PJJ-IF'];
const prodiValues = Object.keys(prodiData).length > 0 ? Object.values(prodiData) : [0, 0, 0];

const dosenProdiEl = document.getElementById('dosenProdi');
if (dosenProdiEl) {
    new Chart(dosenProdiEl, {
        type: 'doughnut',
        data: {
            labels: prodiLabels,
            datasets: [{
                data: prodiValues,
                backgroundColor: colors.slice(0, prodiLabels.length)
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += Number(data); });
                        if(sum === 0) return '0%';
                        let percentage = (value * 100 / sum).toFixed(0) + "%";
                        return percentage;
                    },
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        }
    });
}

// 2. Doughnut chart dosenKK
const kkData = window.dosenData?.kk || {};
const kkLabels = Object.keys(kkData).length > 0 ? Object.keys(kkData) : ['DSIS', 'CITI', 'SEAL'];
const kkValues = Object.keys(kkData).length > 0 ? Object.values(kkData) : [0, 0, 0];

const dosenKKEl = document.getElementById('dosenKK');
if (dosenKKEl) {
    new Chart(dosenKKEl, {
        type: 'doughnut',
        data: {
            labels: kkLabels,
            datasets: [{
                data: kkValues,
                backgroundColor: colors.slice(3, 3 + kkLabels.length)
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += Number(data); });
                        if(sum === 0) return '0%';
                        let percentage = (value * 100 / sum).toFixed(0) + "%";
                        return percentage;
                    },
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        }
    });
}

// 3. Doughnut chart pendDosen
const pendData = window.dosenData?.pendidikan || {};
const pendLabels = Object.keys(pendData).length > 0 ? Object.keys(pendData) : ['S1', 'S2', 'S3', 'ONGOING'];
const pendValues = Object.keys(pendData).length > 0 ? Object.values(pendData) : [0, 0, 0, 0];

const pendDosenEl = document.getElementById('pendDosen');
if (pendDosenEl) {
    new Chart(pendDosenEl, {
        type: 'doughnut',
        data: {
            labels: pendLabels,
            datasets: [{
                data: pendValues,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899']
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += Number(data); });
                        if(sum === 0) return '0%';
                        let percentage = (value * 100 / sum).toFixed(0) + "%";
                        return percentage;
                    },
                    color: '#fff',
                    font: { weight: 'bold' }
                }
            }
        }
    });
}

// 4. Bar chart jfaDosen
const jfaData = window.dosenData?.jfa || {};
const jfaLabels = Object.keys(jfaData).length > 0 ? Object.keys(jfaData) : ['L', 'NJFA', 'AA', 'LK', 'GB'];
const jfaValues = Object.keys(jfaData).length > 0 ? Object.values(jfaData) : [0, 0, 0, 0, 0];

const jfaDosenEl = document.getElementById('jfaDosen');
if (jfaDosenEl) {
    new Chart(jfaDosenEl, {
        type: 'bar',
        data: {
            labels: jfaLabels,
            datasets: [{
                label: 'Jumlah Dosen',
                data: jfaValues,
                backgroundColor: colors.slice(0, jfaLabels.length)
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    font: { weight: 'bold' },
                    color: '#64748b'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// 5. Horizontal Bar chart statusDosen
const statusData = window.dosenData?.status || {};
const statusLabels = Object.keys(statusData).length > 0 ? Object.keys(statusData) : ['PEGAWAI TETAP','PROFESIONAL FULL TIME','PROFESIONAL PART TIME','PERBANTUAN LLDIKTI'];
const statusValues = Object.keys(statusData).length > 0 ? Object.values(statusData) : [0, 0, 0, 0];

const statusDosenEl = document.getElementById('statusDosen');
if (statusDosenEl) {
    new Chart(statusDosenEl, {
        type: 'bar',
        data: {
            labels: statusLabels,
            datasets: [{
                label: 'Jumlah',
                data: statusValues,
                backgroundColor: '#6366f1' // Indigo
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    font: { weight: 'bold' },
                    color: '#64748b'
                }
            },
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });
}