import "./bootstrap";
import Chart from "chart.js/auto";
import ChartDataLabels from 'chartjs-plugin-datalabels';

// === 1. Tingkat Prestasi Juara ===
const juaraDoughnutData = window.kompetisiData?.juaraDoughnut || {};
const juaraLabels = Object.keys(juaraDoughnutData).length > 0 ? Object.keys(juaraDoughnutData) : ["Juara 1", "Juara 2", "Juara 3"];
const juaraValues = Object.keys(juaraDoughnutData).length > 0 ? Object.values(juaraDoughnutData) : [0, 0, 0];
const jumlahJuaraEl = document.getElementById("jumlahJuara");
if (jumlahJuaraEl) {
    new Chart(jumlahJuaraEl, {
        type: "doughnut",
        plugins: [ChartDataLabels],
        data: {
            labels: juaraLabels,
            datasets: [
                {
                    data: juaraValues,
                    backgroundColor: [
                        "#3b82f6", // Blue
                        "#10b981", // Emerald
                        "#f59e0b", // Amber
                        "#4f46e5", // Indigo
                        "#06b6d4"  // Cyan
                    ],
                },
            ],
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    },
                    formatter: (value) => {
                        return value;
                    }
                }
            }
        }
    });
}

// === 2. Kategori Bidang Kompetisi ===
const jenisDoughnutData = window.kompetisiData?.jenisDoughnut || {};
const jenisLabels = Object.keys(jenisDoughnutData).length > 0 ? Object.keys(jenisDoughnutData).map(l => l.toUpperCase()) : ["SAINS", "TEKNOLOGI", "SENI", "OLAHRAGA", "LAINNYA"];
const jenisValues = Object.keys(jenisDoughnutData).length > 0 ? Object.values(jenisDoughnutData) : [0, 0, 0, 0, 0];
const jumlahMahasiswaEl = document.getElementById("jumlahMahasiswa");
if (jumlahMahasiswaEl) {
    new Chart(jumlahMahasiswaEl, {
        type: "doughnut",
        plugins: [ChartDataLabels],
        data: {
            labels: jenisLabels,
            datasets: [
                {
                    data: jenisValues,
                    backgroundColor: [
                        "#3b82f6", // Blue
                        "#10b981", // Emerald
                        "#f59e0b", // Amber
                        "#4f46e5", // Indigo
                        "#06b6d4", // Cyan
                        "#8b5cf6"  // Violet
                    ],
                },
            ],
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    },
                    formatter: (value) => {
                        return value;
                    }
                }
            }
        }
    });
}

// === 3. Status Keaktifan Mahasiswa ===
const statusMahasiswaData = window.kompetisiData?.mahasiswaStatus || {};
const statusLabels = Object.keys(statusMahasiswaData).length > 0 ? Object.keys(statusMahasiswaData).map(l => l.toUpperCase()) : ["AKTIF", "CUTI", "NONAKTIF"];
const statusValues = Object.keys(statusMahasiswaData).length > 0 ? Object.values(statusMahasiswaData) : [0, 0, 0];
const statusMahasiswaEl = document.getElementById("statusMahasiswa");
if (statusMahasiswaEl) {
    new Chart(statusMahasiswaEl, {
        type: "doughnut",
        plugins: [ChartDataLabels],
        data: {
            labels: statusLabels,
            datasets: [
                {
                    data: statusValues,
                    backgroundColor: [
                        "#3b82f6", // Blue
                        "#10b981", // Emerald
                        "#f59e0b", // Amber
                        "#4f46e5", // Indigo
                        "#06b6d4", // Cyan
                        "#8b5cf6"  // Violet
                    ],
                },
            ],
        },
        options: {
            plugins: {
                datalabels: {
                    color: '#fff',
                    font: { weight: 'bold' },
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    },
                    formatter: (value) => {
                        return value;
                    }
                }
            }
        }
    });
}

// === 4. Capaian Juara per Tahun ===
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

// === 5. Tingkat Kejuaraan per Tahun ===
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

// === 6. Kompetisi per Prodi ===
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

// === 7. Kompetisi per Kategori ===
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

// === 8. Total Mahasiswa per Prodi (New) ===
const mahasiswaProdiEl = document.getElementById("mahasiswaProdiChart");
if (mahasiswaProdiEl) {
    const rawData = window.kompetisiData?.mahasiswaProdi || {};
    const labels = Object.keys(rawData).length > 0 ? Object.keys(rawData) : [];
    const data = Object.keys(rawData).length > 0 ? Object.values(rawData) : [];
    const colors = ["#3b82f6", "#10b981", "#f59e0b", "#4f46e5", "#06b6d4"];
    
    new Chart(mahasiswaProdiEl, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: data,
                backgroundColor: colors.slice(0, labels.length),
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
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
}

// === 9. Mahasiswa per Angkatan (New) ===
const mahasiswaAngkatanEl = document.getElementById("mahasiswaAngkatanChart");
if (mahasiswaAngkatanEl) {
    const rawData = window.kompetisiData?.mahasiswaAngkatan || {};
    const labels = Object.keys(rawData).length > 0 ? Object.keys(rawData) : [];
    const data = Object.keys(rawData).length > 0 ? Object.values(rawData) : [];
    const colors = ["#8b5cf6", "#ec4899", "#f97316", "#22c55e"];
    
    new Chart(mahasiswaAngkatanEl, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Jumlah Mahasiswa",
                data: data,
                backgroundColor: colors.slice(0, labels.length),
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
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
}
