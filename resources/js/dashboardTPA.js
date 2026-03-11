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

// 1. Chart Jumlah Juara (Soft & Minimalist)
new Chart(document.getElementById("jumlahJuara"), {
    type: "doughnut",
    data: {
        labels: ["Juara 1", "Juara 2", "Juara 3"],
        datasets: [
            {
                data: [110, 30, 10],
                backgroundColor: [colors[2], colors[0], colors[1]], // Yellow, Sky Blue, Soft Red
            },
        ],
    },
});

// 2. Chart Jumlah Mahasiswa
new Chart(document.getElementById("jumlahMahasiswa"), {
    type: "doughnut",
    data: {
        labels: ["AKADEMIK", "NON-AKADEMIK"],
        datasets: [
            {
                data: [110, 30],
                backgroundColor: [colors[0], colors[3]], // Sky Blue & Leaf Green
            },
        ],
    },
});

// 3. Chart Status Mahasiswa
new Chart(document.getElementById("statusMahasiswa"), {
    type: "doughnut",
    data: {
        labels: ["AKTIF", "CUTI", "TIDAK AKTIF"],
        datasets: [
            {
                data: [110, 30, 10],
                backgroundColor: [colors[3], colors[2], colors[1]], // Green, Yellow, Red
            },
        ],
    },
});

// 4. Bar Chart JFA Dosen (Vertikal)
new Chart(document.getElementById("jumlahKompetisi"), {
    type: "bar",
    data: {
        labels: ["RPL", "IT", "DS", "IF", "PJJ"],
        datasets: [
            {
                label: "Jumlah Dosen",
                data: [30, 40, 20, 45, 5],
                backgroundColor: colors, // Otomatis menggunakan 5 warna palet
            },
        ],
    },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: "JFA Dosen" },
            legend: { display: false },
        },
        scales: { y: { beginAtZero: true } },
    },
});

// 5. Bar Chart Kompetisi 2 (Horizontal)
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
                backgroundColor: colors, // Mengambil 4 warna pertama dari palet
            },
        ],
    },
    options: {
        indexAxis: "y",
        plugins: { legend: { display: false } },
    },
});
