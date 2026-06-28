import './bootstrap';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(ChartDataLabels);

const pendidikanData = window.dashboardData?.pendidikan || {};
const pendidikanLabels = Object.keys(pendidikanData).length > 0 ? Object.keys(pendidikanData) : ['S1', 'S2', 'S3'];
const pendidikanValues = Object.keys(pendidikanData).length > 0 ? Object.values(pendidikanData) : [0, 0, 0];

// Donut chart Pendidikan Terakhir Dosen
const chartDosenEl = document.getElementById('chartDosen');
if (chartDosenEl) {
    new Chart(chartDosenEl, {
        type: 'doughnut',
        data: {
            labels: pendidikanLabels,
            datasets: [{
                data: pendidikanValues,
                backgroundColor: [
                    '#3b82f6', // Blue
                    '#10b981', // Emerald
                    '#f59e0b'  // Amber
                ]
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

const jadData = window.dashboardData?.jad || {};
const jadLabels = Object.keys(jadData).length > 0 ? Object.keys(jadData) : ['NJFA', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor'];
const jadValues = Object.keys(jadData).length > 0 ? Object.values(jadData) : [0, 0, 0, 0, 0];

// Donut chart JAD Dosen
const chartTPAEl = document.getElementById('chartTPA');
if (chartTPAEl) {
    new Chart(chartTPAEl, {
        type: 'doughnut',
        data: {
            labels: jadLabels,
            datasets: [{
                data: jadValues,
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
        options: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => { sum += Number(data); });
                        if(sum === 0) return '0 (0%)';
                        let percentage = (value * 100 / sum).toFixed(0) + "%";
                        return value + " (" + percentage + ")";
                    },
                    color: '#fff',
                    font: { weight: 'bold', size: 10 }
                }
            }
        }
    });
}

// Bar chart Dosen Prodi
const chartDosenProdiEl = document.getElementById('chartDosenProdi');
if (chartDosenProdiEl) {
    new Chart(chartDosenProdiEl, {
        type: 'bar',
        data: {
            labels: [
                'S1 IF (TUB)', 'S1 IT (TUB)', 'S1 RPL (TUB)', 'S1 PJJ (TUB)',
                'S2 IF (TUB)', 'S2 Ilmu Forensik (TUB)', 'S1 Sains Data (TUB)',
                'S3 INFORMATIKA (TUB)', 'S1 IT TUKJ', 'S1 IF TUKJ'
            ],
            datasets: [{
                label: 'Jumlah',
                data: [86, 28, 24, 12, 7, 5, 14, 5, 20, 2],
                backgroundColor: '#3b82f6', // Blue
                borderRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    font: {
                        weight: 'bold'
                    },
                    color: '#64748b'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: {
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
}