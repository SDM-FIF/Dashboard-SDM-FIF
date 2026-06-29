import "./bootstrap";
import Chart from "chart.js/auto";

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
        const labels = JSON.parse(el.getAttribute('data-labels') || '[]');
        const values = JSON.parse(el.getAttribute('data-values') || '[]');
        return { labels, values };
    } catch (e) {
        console.error('Failed to parse chart data for ' + elementId, e);
        return null;
    }
}

// 1. Chart Lokasi Kerja TPA (Doughnut)
const lokasiData = getChartData("lokasiKerjaChart");
if (lokasiData) {
    new Chart(document.getElementById("lokasiKerjaChart"), {
        type: "doughnut",
        data: {
            labels: lokasiData.labels,
            datasets: [
                {
                    data: lokasiData.values,
                    backgroundColor: colors,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
}

// 2. Chart Jabatan/Pangkat TPA (Doughnut)
const jabatanData = getChartData("jabatanChart");
if (jabatanData) {
    new Chart(document.getElementById("jabatanChart"), {
        type: "doughnut",
        data: {
            labels: jabatanData.labels,
            datasets: [
                {
                    data: jabatanData.values,
                    backgroundColor: [colors[1], colors[3], colors[5], colors[7], colors[9]],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
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
                    backgroundColor: [colors[5], colors[2], colors[0], colors[4], colors[6], colors[7]],
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
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
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    }
                }
            }
        },
    });
}
