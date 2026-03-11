import "./bootstrap";
import Chart from "chart.js/auto";

new Chart(document.getElementById("jumlahJuara"), {
    type: "doughnut",
    data: {
        labels: ["Juara 1", "Juara 2", "Juara 3"],
        datasets: [
            {
                data: [110, 30, 10],
                backgroundColor: [
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
                ],
            },
        ],
    },
});

new Chart(document.getElementById("jumlahMahasiswa"), {
    type: "doughnut",
    data: {
        labels: ["AKADEMIK", "NON-AKADEMIK"],
        datasets: [
            {
                data: [110, 30],
                backgroundColor: [
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
                ],
            },
        ],
    },
});

new Chart(document.getElementById("statusMahasiswa"), {
    type: "doughnut",
    data: {
        labels: ["AKTIF", "CUTI", "TIDAK AKTIF"],
        datasets: [
            {
                data: [110, 30, 10],
                backgroundColor: [
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
                ],
            },
        ],
    },
});

new Chart(document.getElementById("jumlahKompetisi"), {
    type: "bar",
    data: {
        labels: ["RPL", "IT", "DS", "IF", "PJJ"],
        datasets: [
            {
                label: "Jumlah Dosen",
                data: [30, 40, 20, 45, 5],
                backgroundColor: [
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
                ],
            },
        ],
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: "JFA Dosen",
            },
            legend: {
                display: false,
            },
        },
        scales: {
            y: {
                beginAtZero: true,
            },
        },
    },
});

new Chart(document.getElementById("jumlahKompetisi2"), {
    type: "bar",
    data: {
        labels: [
            "PEGAWAI TETAP",
            "PROFESIONAL FULL TIME",
            "PROFESIONAL PART TIME",
            "PERBANTUAN LLDIKTI",
        ],
        datasets: [
            {
                label: "Jumlah",
                data: [40, 20, 60, 80],
                backgroundColor: [
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
                ],
            },
        ],
    },
    options: {
        indexAxis: "y",
    },
});
