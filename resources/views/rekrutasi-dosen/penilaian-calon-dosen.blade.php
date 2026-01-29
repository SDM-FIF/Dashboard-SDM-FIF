<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Penilaian Calon Dosen - Dashboard SDM FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Import Bootstrap only for form content, not global */
        .form-content * {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        
        /* Bootstrap classes scoped to form content */
        .form-content .container-fluid { width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto; }
        .form-content .d-flex { display: flex !important; }
        .form-content .justify-content-between { justify-content: space-between !important; }
        .form-content .justify-content-end { justify-content: flex-end !important; }
        .form-content .align-items-center { align-items: center !important; }
        .form-content .mb-1 { margin-bottom: 0.25rem !important; }
        .form-content .mb-2 { margin-bottom: 0.5rem !important; }
        .form-content .mb-3 { margin-bottom: 1rem !important; }
        .form-content .mb-4 { margin-bottom: 1.5rem !important; }
        .form-content .mb-0 { margin-bottom: 0 !important; }
        .form-content .mt-2 { margin-top: 0.5rem !important; }
        .form-content .mt-3 { margin-top: 1rem !important; }
        .form-content .me-2 { margin-right: 0.5rem !important; }
        .form-content .gap-2 { gap: 0.5rem !important; }
        .form-content .text-muted { color: #6c757d !important; }
        .form-content .text-center { text-align: center !important; }
        .form-content .fw-bold { font-weight: 700 !important; }
        .form-content .card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem; }
        .form-content .card-header { padding: 0.75rem 1.25rem; margin-bottom: 0; background-color: rgba(0,0,0,.03); border-bottom: 1px solid rgba(0,0,0,.125); }
        .form-content .card-body { flex: 1 1 auto; padding: 1.25rem; }
        .form-content .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075) !important; }
        .form-content .bg-primary { background-color: #0d6efd !important; }
        .form-content .text-white { color: #fff !important; }
        .form-content .row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
        .form-content .col-md-3 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; }
        .form-content .col-md-9 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; }
        @media (min-width: 768px) {
            .form-content .col-md-3 { flex: 0 0 25%; max-width: 25%; }
            .form-content .col-md-9 { flex: 0 0 75%; max-width: 75%; }
        }
        .form-content .table-responsive { display: block; width: 100%; overflow-x: auto; }
        .form-content .table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }
        .form-content .table-bordered { border: 1px solid #dee2e6; }
        .form-content .table-bordered th, .form-content .table-bordered td { border: 1px solid #dee2e6; padding: 0.75rem; vertical-align: top; }
        .form-content .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
        .form-content .table-light { background-color: #f8f9fa; }
        .form-content .table-primary { background-color: #cfe2ff; }
        .form-content .table-success { background-color: #d1e7dd; }
        .form-content .table-warning { background-color: #fff3cd; }
        .form-content .table-info { background-color: #cff4fc; }
        .form-content .form-control { display: block; width: 100%; padding: 0.375rem 0.75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: 0.25rem; }
        .form-content .form-control:focus { color: #212529; background-color: #fff; border-color: #86b7fe; outline: 0; box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25); }
        .form-content .form-label { margin-bottom: 0.5rem; display: inline-block; }
        .form-content .btn { display: inline-block; font-weight: 400; line-height: 1.5; color: #212529; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; user-select: none; background-color: transparent; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 1rem; border-radius: 0.25rem; }
        .form-content .btn-outline-secondary { color: #6c757d; border-color: #6c757d; }
        .form-content .btn-outline-secondary:hover { color: #fff; background-color: #6c757d; border-color: #6c757d; }
        .form-content .btn-secondary { color: #fff; background-color: #6c757d; border-color: #6c757d; }
        .form-content .btn-primary { color: #fff; background-color: #0d6efd; border-color: #0d6efd; }
        .form-content .btn-primary:hover { background-color: #0b5ed7; border-color: #0a58ca; }
        .form-content .alert { position: relative; padding: 1rem 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.25rem; }
        .form-content .alert-info { color: #055160; background-color: #cff4fc; border-color: #b6effb; }
        .form-content h4, .form-content h5 { margin-top: 0; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; }
        .form-content h4 { font-size: 1.5rem; }
        .form-content h5 { font-size: 1.25rem; }
        .form-content ul { padding-left: 2rem; margin-top: 0; margin-bottom: 1rem; }
        .form-content strong { font-weight: bolder; }
    </style>
</head>

<body class="flex flex-col md:flex-row bg-gray-50 font-nunito">
    {{-- Sidebar --}}
    <x-navbar />
    
    {{-- Main Content --}}
    <main class="flex-1 p-4 md:p-6 min-h-screen">
        {{-- Top Bar --}}
        <x-topbar />

        <div class="form-content">
            <div class="container-fluid">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-1">Form Penilaian Microteaching & Interview</h4>
                        <p class="text-muted mb-0">Calon Dosen Profesional Full / Part Time Universitas Telkom</p>
                    </div>
                    <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                </div>

        <!-- Form Penilaian Microteaching Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">FORM PENILAIAN MICROTEACHING</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Nama Kandidat</strong>
                    </div>
                    <div class="col-md-9">
                        : {{ $calonDosen->nama }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Prodi</strong>
                    </div>
                    <div class="col-md-9">
                        : {{ $calonDosen->prodi->nama_prodi ?? '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>Jalur Lamaran</strong>
                    </div>
                    <div class="col-md-9">
                        : {{ $calonDosen->jalur_lamaran ?? '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <strong>JFA</strong>
                    </div>
                    <div class="col-md-9">
                        : {{ $calonDosen->jabatan_fungsional_akademik ?? '-' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <strong>H-index</strong>
                    </div>
                    <div class="col-md-9">
                        : {{ $calonDosen->h_index ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Penilaian Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <form id="formPenilaian" action="#" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="10%">No</th>
                                    <th width="50%">Kriteria Penilaian</th>
                                    <th width="15%">Nilai</th>
                                    <th width="25%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Section A: Kualifikasi (40%) -->
                                <tr class="table-primary">
                                    <td colspan="4"><strong>A. KUALIFIKASI (40%)</strong></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Jalur Lamaran / Pendidikan = {{ $calonDosen->jalur_lamaran ?? '-' }}</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_jalur_lamaran" id="nilai_jalur_lamaran" 
                                               value="{{ 
                                                   $calonDosen->jalur_lamaran == 'S3 Prof Full time' ? 5 :
                                                   ($calonDosen->jalur_lamaran == 'S3 Praktisi Part time' ? 4 :
                                                   ($calonDosen->jalur_lamaran == 'S3 OnGoing' ? 3 :
                                                   ($calonDosen->jalur_lamaran == 'S2 Praktisi Part time' ? 2 :
                                                   ($calonDosen->jalur_lamaran == 'S2 Prof Full time' ? 1 : 0))))
                                               }}" readonly style="background-color: #e9ecef;">
                                    </td>
                                    <td rowspan="3" style="vertical-align: middle;">
                                        <span id="rata_a_text" class="fw-bold">Rata-rata A = <span id="rata_a_value">0.00</span></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jabatan Fungsional Akademik (JFA) = {{ $calonDosen->jabatan_fungsional_akademik ?? 'NJFA' }}</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_jfa" id="nilai_jfa" 
                                               value="{{ 
                                                   $calonDosen->jabatan_fungsional_akademik == 'Guru Besar' ? 5 :
                                                   ($calonDosen->jabatan_fungsional_akademik == 'Lektor Kepala' ? 4 :
                                                   ($calonDosen->jabatan_fungsional_akademik == 'Lektor' ? 3 :
                                                   ($calonDosen->jabatan_fungsional_akademik == 'Asisten Ahli' ? 2 : 1)))
                                               }}" readonly style="background-color: #e9ecef;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>H-Index = {{ $calonDosen->h_index ?? '0.00' }}</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_h_index" id="nilai_h_index" 
                                               value="{{ 
                                                   ($calonDosen->h_index ?? 0) > 10 ? 5 :
                                                   (($calonDosen->h_index ?? 0) >= 5 ? 4 :
                                                   (($calonDosen->h_index ?? 0) >= 2 ? 3 :
                                                   (($calonDosen->h_index ?? 0) >= 1 ? 2 : 1)))
                                               }}" readonly style="background-color: #e9ecef;">
                                    </td>
                                </tr>

                                <!-- Section B: Micro Teaching (20%) -->
                                <tr class="table-success">
                                    <td colspan="4"><strong>B. MICRO TEACHING (20%)</strong></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Penguasaan materi & audiens</td>
                                    <td>7%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_b1" id="nilai_b1" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Sistematika (kemudahan dipahami)</td>
                                    <td>7%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_b2" id="nilai_b2" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Kejelasan suara & tulisan</td>
                                    <td>6%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_b3" id="nilai_b3" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>

                                <!-- Section C: Wawancara (40%) -->
                                <tr class="table-warning">
                                    <td colspan="4"><strong>C. WAWANCARA (40%)</strong></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Motivasi</td>
                                    <td>5%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c1" id="nilai_c1" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Kemampuan mengajar</td>
                                    <td>5%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c2" id="nilai_c2" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Kemampuan mengembangkan kurikulum Pengajaran</td>
                                    <td>5%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c3" id="nilai_c3" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Kemampuan penelitian & publikasi</td>
                                    <td>10%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c4" id="nilai_c4" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Kemampuan Abdimas</td>
                                    <td>5%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c5" id="nilai_c5" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Kemampuan Bekerjasama dengan tim</td>
                                    <td>5%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c6" id="nilai_c6" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Keahlian lainnya</td>
                                    <td>3%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c7" id="nilai_c7" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Komitmen waktu dan kesediaan melakukan hal diluar tugas pokok</td>
                                    <td>2%</td>
                                    <td>
                                        <input type="number" class="form-control" name="nilai_c8" id="nilai_c8" 
                                               min="1" max="5" step="0.1" placeholder="1-5" required>
                                    </td>
                                </tr>

                                <!-- Total -->
                                <tr class="table-info">
                                    <td colspan="2" class="text-center"><strong>TOTAL NILAI (Rata-rata Berbobot)</strong></td>
                                    <td class="text-center"><strong>100%</strong></td>
                                    <td>
                                        <input type="text" class="form-control fw-bold" id="total_nilai" 
                                               readonly placeholder="Auto Calculate">
                                    </td>
                                </tr>
                                
                                <!-- Kesiapan -->
                                <tr>
                                    <td colspan="2" class="text-center"><strong>Kesiapan bergabung segera?</strong></td>
                                    <td colspan="2">
                                        <div class="d-flex gap-3">
                                            <label class="form-label mb-0">
                                                <input type="radio" name="kesiapan" value="YA" required> YA
                                            </label>
                                            <label class="form-label mb-0">
                                                <input type="radio" name="kesiapan" value="TIDAK/PIKIR-PIKIR" required> TIDAK/PIKIR-PIKIR
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Kesediaan -->
                                <tr>
                                    <td colspan="2" class="text-center"><strong>Bersedia dengan standard gaji?</strong></td>
                                    <td colspan="2">
                                        <div class="d-flex gap-3">
                                            <label class="form-label mb-0">
                                                <input type="radio" name="kesediaan" value="YA" required> YA
                                            </label>
                                            <label class="form-label mb-0">
                                                <input type="radio" name="kesediaan" value="TIDAK/PIKIR-PIKIR" required> TIDAK/PIKIR-PIKIR
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Keterangan Nilai -->
                    <div class="alert alert-info mt-3">
                        <strong>Keterangan Skala Penilaian:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>5</strong> = Sangat Baik</li>
                            <li><strong>4</strong> = Baik</li>
                            <li><strong>3</strong> = Cukup</li>
                            <li><strong>2</strong> = Kurang</li>
                            <li><strong>1</strong> = Sangat Kurang</li>
                        </ul>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label for="catatan" class="form-label"><strong>Catatan/Komentar:</strong></label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                  placeholder="Tambahkan catatan atau komentar (opsional)"></textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('rekrutasi-dosen.jadwal-pengujian') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Simpan Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>        </div>    </div>
    </main>

    <script>
        $(document).ready(function() {
            // Calculate rata_a on page load
            function calculateRataA() {
                const nilaiJalurLamaran = parseFloat($('#nilai_jalur_lamaran').val() || 0);
                const nilaiJFA = parseFloat($('#nilai_jfa').val() || 0);
                const nilaiHIndex = parseFloat($('#nilai_h_index').val() || 0);
                
                const rataA = (nilaiJalurLamaran + nilaiJFA + nilaiHIndex) / 3;
                $('#rata_a_value').text(rataA.toFixed(2));
                
                return rataA;
            }
            
            // Calculate rata_a immediately on page load
            calculateRataA();
            
            // Auto calculate total nilai berbobot
            function calculateTotal() {
                let total = 0;
                
                // Section A: Kualifikasi (40%) - using the average
                const rataA = calculateRataA();
                total += rataA * 0.40;
                
                // Section B: Micro Teaching (20%)
                total += parseFloat($('#nilai_b1').val() || 0) * 0.07;
                total += parseFloat($('#nilai_b2').val() || 0) * 0.07;
                total += parseFloat($('#nilai_b3').val() || 0) * 0.06;
                
                // Section C: Wawancara (40%)
                total += parseFloat($('#nilai_c1').val() || 0) * 0.05;
                total += parseFloat($('#nilai_c2').val() || 0) * 0.05;
                total += parseFloat($('#nilai_c3').val() || 0) * 0.05;
                total += parseFloat($('#nilai_c4').val() || 0) * 0.10;
                total += parseFloat($('#nilai_c5').val() || 0) * 0.05;
                total += parseFloat($('#nilai_c6').val() || 0) * 0.05;
                total += parseFloat($('#nilai_c7').val() || 0) * 0.03;
                total += parseFloat($('#nilai_c8').val() || 0) * 0.02;
                
                $('#total_nilai').val(total.toFixed(2));
            }
            
            // Trigger calculation on input change (only for sections B and C)
            $('input[name^="nilai_b"], input[name^="nilai_c"]').on('input', calculateTotal);
            
            // Form submission
            $('#formPenilaian').on('submit', function(e) {
                e.preventDefault();
                
                // Prepare data to send
                const formData = {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    jadwal_pengujian_id: {{ $jadwal->id }},
                    calon_dosen_id: {{ $calonDosen->id }},
                    
                    // Section A - Kualifikasi (auto-calculated)
                    nilai_jalur_lamaran: parseFloat($('#nilai_jalur_lamaran').val()),
                    nilai_jfa: parseFloat($('#nilai_jfa').val()),
                    nilai_h_index: parseFloat($('#nilai_h_index').val()),
                    rata_a: calculateRataA(),
                    
                    // Section B - Micro Teaching
                    nilai_pma: parseFloat($('#nilai_b1').val() || 0),
                    nilai_sistematika: parseFloat($('#nilai_b2').val() || 0),
                    nilai_kst: parseFloat($('#nilai_b3').val() || 0),
                    
                    // Section C - Wawancara
                    nilai_motivasi: parseFloat($('#nilai_c1').val() || 0),
                    nilai_kmp_mengajar: parseFloat($('#nilai_c2').val() || 0),
                    nilai_kmp_abdimas: parseFloat($('#nilai_c3').val() || 0),
                    nilai_kmp_pp: parseFloat($('#nilai_c4').val() || 0),
                    nilai_kmt_wkm: parseFloat($('#nilai_c5').val() || 0),
                    nilai_kmp_mkp: parseFloat($('#nilai_c6').val() || 0),
                    nilai_kmp_bdt: parseFloat($('#nilai_c7').val() || 0),
                    nilai_keahlian_lainnya: parseFloat($('#nilai_c8').val() || 0),
                    
                    // Kesiapan & Kesediaan
                    kesiapan: $('input[name="kesiapan"]:checked').val(),
                    kesediaan: $('input[name="kesediaan"]:checked').val(),
                    
                    // Additional fields
                    catatan_penilai: $('#catatan').val(),
                    total_nilai: parseFloat($('#total_nilai').val())
                };
                
                // Send AJAX request
                $.ajax({
                    url: '{{ route("rekrutasi-dosen.penilaian.store") }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Penilaian berhasil disimpan.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = "{{ route('rekrutasi-dosen.jadwal-pengujian') }}";
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan penilaian: ' + (xhr.responseJSON?.message || 'Unknown error'),
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
