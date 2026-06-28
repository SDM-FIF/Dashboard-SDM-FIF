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

// === Capaian Juara per Tahun ===
const chartJuaraTahunEl = document.getElementById("chartJuaraTahun");
if (chartJuaraTahunEl) {
    const rawData = window.kompetisiData?.juaraTahun || [];
    // Extract unique years
    const years = [...new Set(rawData.map(d => d.year))].sort();
    const categories = ["Juara 1", "Juara 2", "Juara 3", "Harapan 1", "Harapan 2", "Harapan 3"];
    const colors = ["#3b82f6", "#10b981", "#f59e0b", "#4f46e5", "#06b6d4", "#8b5cf6"];
    
    const datasets = categories.map((cat, index) => {
        return {
            label: cat,
            backgroundColor: colors[index % colors.length],
            data: years.map(y => {
                const found = rawData.find(d => d.year === y && d.juara === cat);
                return found ? found.count : 0;
            })
        };
    });

    new Chart(chartJuaraTahunEl, {
        type: "bar",
        data: {
            labels: years.length > 0 ? years : [new Date().getFullYear()],
            datasets: datasets.length > 0 ? datasets : [{ label: 'Data', data: [] }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

// === Tingkat Kejuaraan per Tahun ===
const chartTingkatTahunEl = document.getElementById("chartTingkatTahun");
if (chartTingkatTahunEl) {
    const rawData = window.kompetisiData?.kompetisiTahun || [];
    // Extract unique years
    const years = [...new Set(rawData.map(d => d.year))].sort();
    const categories = ["Nasional", "Internasional", "Universitas"];
    const colors = ["#ef4444", "#3b82f6", "#10b981"];
    
    const datasets = categories.map((cat, index) => {
        return {
            label: cat,
            backgroundColor: colors[index % colors.length],
            data: years.map(y => {
                const found = rawData.find(d => d.year === y && d.tingkat_kompetisi === cat);
                return found ? found.count : 0;
            })
        };
    });

    new Chart(chartTingkatTahunEl, {
        type: "bar",
        data: {
            labels: years.length > 0 ? years : [new Date().getFullYear()],
            datasets: datasets.length > 0 ? datasets : [{ label: 'Data', data: [] }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

// === Kompetisi per Prodi ===
const jumlahKompetisiEl = document.getElementById("jumlahKompetisi");
if (jumlahKompetisiEl) {
    const rawData = window.kompetisiData?.kompetisiProdi || [];
    const labels = rawData.map(d => d.nama_prodi);
    const data = rawData.map(d => d.count);
    const colors = ["#3b82f6", "#10b981", "#f59e0b", "#4f46e5", "#06b6d4"];
    
    new Chart(jumlahKompetisiEl, {
        type: "bar",
        data: {
            labels: labels.length > 0 ? labels : ["Tidak ada data"],
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: data.length > 0 ? data : [0],
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}

// === Kompetisi per Kategori ===
const jumlahKompetisi2El = document.getElementById("jumlahKompetisi2");
if (jumlahKompetisi2El) {
    const rawData = window.kompetisiData?.kompetisiKategori || [];
    const labels = rawData.map(d => (d.jenis || "").toUpperCase());
    const data = rawData.map(d => d.count);
    const colors = ["#8b5cf6", "#ec4899", "#f97316", "#64748b", "#22c55e"];
    
    new Chart(jumlahKompetisi2El, {
        type: "bar",
        data: {
            labels: labels.length > 0 ? labels : ["Tidak ada data"],
            datasets: [{
                label: "Jumlah",
                data: data.length > 0 ? data : [0],
                backgroundColor: colors,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: "y",
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
}
