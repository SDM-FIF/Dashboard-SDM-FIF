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

let tpaLabels = [];
let tpaValues = [];

// Determine whether we are rendering TPA unit kerja (landing page) or JAD Dosen (dashboard)
if (window.dashboardData?.tpa) {
    const tpaData = window.dashboardData.tpa;
    tpaLabels = Object.keys(tpaData).length > 0 ? Object.keys(tpaData) : ['Unit 1', 'Unit 2', 'Unit 3'];
    tpaValues = Object.keys(tpaData).length > 0 ? Object.values(tpaData) : [0, 0, 0];
} else {
    const jadData = window.dashboardData?.jad || {};
    tpaLabels = Object.keys(jadData).length > 0 ? Object.keys(jadData) : ['NJFA', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor'];
    tpaValues = Object.keys(jadData).length > 0 ? Object.values(jadData) : [0, 0, 0, 0, 0];
}

// Donut chart JAD Dosen / TPA
const chartTPAEl = document.getElementById('chartTPA');
if (chartTPAEl) {
    new Chart(chartTPAEl, {
        type: 'doughnut',
        data: {
            labels: tpaLabels,
            datasets: [{
                data: tpaValues,
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
const prodiData = window.dashboardData?.prodi || {};

const prodiLabels = Object.keys(prodiData).map(label =>
    label
        .replace("Informatika", "IF")
        .replace("Rekayasa Perangkat Lunak", "RPL")
        .replace("Data Sains", "DS")
        .replace("Teknologi Informasi", "IT")
);
const prodiValues = Object.values(prodiData);

const chartKompetisiEl = document.getElementById("chartKompetisi") || document.getElementById("chartDosenProdi");

if (chartKompetisiEl) {

    new Chart(chartKompetisiEl, {
        type: "bar",

        data: {
            labels: prodiLabels,

            datasets: [{
                label: "Jumlah Dosen",

                data: prodiValues,

                backgroundColor: prodiValues.map(value =>
                    value > 0
                        ? "#3b82f6"
                        : "#CBD5E1"
                ),

                borderRadius: 8,
                borderSkipped: false
            }]
        },

        options: {

            responsive: true,
            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                },

                datalabels: {

                    color: "#334155",

                    anchor: "end",
                    align: "top",

                    font: {
                        weight: "bold",
                        size: 12
                    },

                    formatter: value => value
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    },

                    grid: {
                        color: "#E5E7EB"
                    }

                },

                x: {

                    ticks: {

                        maxRotation: 0,

                        minRotation: 0,

                        autoSkip: false,

                        callback: function(value) {

                            const label = this.getLabelForValue(value);

                            return label
                                .replace(" - ", "\n");
                        }

                    },

                    grid: {
                        display: false
                    }

                }

            }

        }

    });
}