import "./bootstrap";
import Chart from "chart.js/auto";
import ChartDataLabels from 'chartjs-plugin-datalabels';

console.log("dashboardTPA loaded");
console.log(document.getElementById("jabatanChart"));

Chart.register(ChartDataLabels);

// Palet warna yang kamu pilih
const colors = [
    "#3b82f6", // Blue
    "#10b981", // Emerald
    "#f59e0b", // Amber
    "#4f46e5", // Indigo
    "#06b6d4", // Cyan
    "#8b5cf6", // Violet
    "#ec4899", // Pink
    "#f97316", // Orange
    "#64748b", // Slate
    "#22c55e", // Green
];

// Helper to parse data from element
function getChartData(elementId) {
    const el = document.getElementById(elementId);
    if (!el) return null;
    try {
        const labels = JSON.parse(el.getAttribute("data-labels") || "[]");
        const values = JSON.parse(el.getAttribute("data-values") || "[]");
        return { labels, values };
    } catch (e) {
        console.error("Failed to parse chart data for " + elementId, e);
        return null;
    }
}

const datalabelsConfig = {
    color: '#fff',
    font: {
        weight: 'bold',
        size: 11
    },
    formatter: (value, ctx) => {
        const datapoints = ctx.chart.data.datasets[0].data;
        const total = datapoints.reduce((total, datapoint) => total + datapoint, 0);
        const percentage = total > 0 ? (value / total * 100).toFixed(1) + "%" : "0%";
        // Only show label if percentage is greater than 0%
        return value > 0 ? percentage : '';
    }
};

// 2. Chart Jabatan/Pangkat TPA (Doughnut)
// 2. Chart Jabatan/Pangkat TPA (DEBUG)

const jabatanCanvas = document.getElementById("jabatanChart");
const jabatanData = getChartData("jabatanChart");

if (jabatanCanvas && jabatanData) {
    new Chart(jabatanCanvas, {
        type: "doughnut",
        data: {
            labels: jabatanData.labels,
            datasets: [{
                data: jabatanData.values,
                backgroundColor: [
                    colors[1],
                    colors[3],
                    colors[5],
                    colors[7],
                    colors[9],
                ],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom"
                },
                datalabels: datalabelsConfig
            }
        }
    });
}


// 3. Chart Pendidikan TPA (Doughnut)
const pendidikanData = getChartData("pendidikanChart");
if (pendidikanData) {
    new Chart(document.getElementById("pendidikanChart"), {
        type: "doughnut",
        data: {
            labels: pendidikanData.labels,
            datasets: [
                {
                    data: pendidikanData.values,
                    backgroundColor: [
                        colors[5],
                        colors[2],
                        colors[0],
                        colors[4],
                        colors[6],
                        colors[7],
                    ],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: "bottom",
                },
                datalabels: datalabelsConfig
            },
        },
    });
}

// 4. Bar Chart Status Pegawai - TPA (Horizontal Bar)
const statusPegawaiData = getChartData("statusPegawaiChart");
if (statusPegawaiData) {
    new Chart(document.getElementById("statusPegawaiChart"), {
        type: "bar",
        data: {
            labels: statusPegawaiData.labels,
            datasets: [
                {
                    label: "Jumlah Pegawai",
                    data: statusPegawaiData.values,
                    backgroundColor: colors[0],
                    borderRadius: 8,
                },
            ],
        },
        options: {
            indexAxis: "y",
            responsive: true,
            plugins: {
                legend: { display: false },
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    align: 'end',
                    anchor: 'end',
                    formatter: (value, ctx) => {
                        const datapoints = ctx.chart.data.datasets[0].data;
                        const total = datapoints.reduce((total, datapoint) => total + datapoint, 0);
                        const percentage = total > 0 ? (value / total * 100).toFixed(1) + "%" : "0%";
                        return percentage;
                    },
                    // Since it's outside the bar in horizontal, maybe we want it inside or with different color if outside. 
                    // Let's set it to dark if we place it outside.
                    color: '#475569'
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
        },
    });
}
