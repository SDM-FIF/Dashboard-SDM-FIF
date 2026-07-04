import "./bootstrap";
import Chart from "chart.js/auto";
import ChartDataLabels from "chartjs-plugin-datalabels";

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
    color: "#fff",
    font: {
        weight: "bold",
        size: 11,
    },
    formatter: (value, ctx) => {
        const datapoints = ctx.chart.data.datasets[0].data;
        const total = datapoints.reduce(
            (total, datapoint) => total + datapoint,
            0,
        );
        const percentage =
            total > 0 ? ((value / total) * 100).toFixed(1) + "%" : "0%";
        // Only show label if percentage is greater than 0%
        return value > 0 ? percentage : "";
    },
};

// 2. Chart Jabatan TPA (Bar)
const jabatanCanvas = document.getElementById("jabatanChart");
const jabatanData = getChartData("jabatanChart");

// 3. Chart Pendidikan TPA (Doughnut)
const pendidikanCanvas = document.getElementById("pendidikanChart");
const pendidikanData = getChartData("pendidikanChart");
if (jabatanCanvas && jabatanData) {
    new Chart(jabatanCanvas, {
        type: "bar",
        data: {
            labels: jabatanData.labels.map((label) => {
                const labelMap = {
                    "Staff Administrasi": ["Staff", "Admin"],
                    "Staff Kepegawaian": ["Staff", "Pegawaian"],
                    "Staff Registrasi": ["Staff", "Registrasi"],
                    "Staff Penjadwalan": ["Staff", "Penjadwalan"],
                    "Staff Kelulusan": ["Staff", "Kelulusan"],
                    "Staff Sarpras": ["Staff", "Sarpras"],
                    "Staff Layanan": ["Staff", "Layanan"],
                    "Staff Ujian": ["Staff", "Ujian"],
                    "Staff Keuangan": ["Staff", "Keuangan"],
                    "Analisis SDM": ["Analisis", "SDM"],
                    "Admin Prodi": ["Admin", "Prodi"],
                    Laboran: ["Laboran"],
                };

                return labelMap[label] ?? [label];
            }),
            datasets: [
                {
                    label: "Jumlah TPA",
                    data: jabatanData.values,
                    backgroundColor: "#10B981",
                    borderRadius: 8,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                datalabels: {
                    anchor: "end",
                    align: "top",
                    color: "#334155",
                    font: {
                        weight: "bold",
                    },
                },
            },
            scales: {
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0,
                        font: {
                            size: 11,
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
}

if (pendidikanCanvas && pendidikanData) {
    new Chart(pendidikanCanvas, {
        type: "bar",
        data: {
            labels: pendidikanData.labels,
            datasets: [
                {
                    label: "Jumlah TPA",
                    data: pendidikanData.values,
                    backgroundColor: "#8B5CF6",
                    borderRadius: 8,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                datalabels: {
                    anchor: "end",
                    align: "top",
                    color: "#334155",
                    font: {
                        weight: "bold",
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                    },
                },
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
                    color: "#fff",
                    font: { weight: "bold" },
                    align: "end",
                    anchor: "end",
                    formatter: (value, ctx) => {
                        const datapoints = ctx.chart.data.datasets[0].data;
                        const total = datapoints.reduce(
                            (total, datapoint) => total + datapoint,
                            0,
                        );
                        const percentage =
                            total > 0
                                ? ((value / total) * 100).toFixed(1) + "%"
                                : "0%";
                        return percentage;
                    },
                    // Since it's outside the bar in horizontal, maybe we want it inside or with different color if outside.
                    // Let's set it to dark if we place it outside.
                    color: "#475569",
                },
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
