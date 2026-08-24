<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen - FIF</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="flex bg-[#F8FAFC] text-slate-800 font-nunito min-h-screen overflow-x-hidden">
    <x-navbar />

    <main class="flex-1 flex flex-col min-h-screen p-6 md:p-8 overflow-y-auto">
        <x-topbar />

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-[#C41E3A] tracking-tight">Dashboard Dosen FIF</h1>
                <p class="text-slate-500 mt-1 text-sm md:text-base">Statistik dosen berdasarkan Program Studi, Kelompok Keahlian, Pendidikan, JFA, dan Status Pegawai.</p>
            </div>
        </div>


        <!-- Sebaran Pendidikan Terakhir Dosen Per Prodi -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">

            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>

                <div>
                    <h3 class="font-bold text-slate-800 text-base">
                        Sebaran Pendidikan Terakhir Dosen Per Prodi
                    </h3>
                    <p class="text-xs text-slate-400">
                        Jumlah dosen yang mengajar pada setiap program studi.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <th class="px-4 py-3 text-left rounded-l-lg">
                                Program Studi
                            </th>

                            <th class="px-4 py-3 text-center">
                                S1
                            </th>

                            <th class="px-4 py-3 text-center">
                                S2
                            </th>

                            <th class="px-4 py-3 text-center">
                                S3
                            </th>

                            <th class="px-4 py-3 text-center rounded-r-lg">
                                Total Dosen
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                    <tbody>
                        @foreach($pendidikanPerProdi as $p)
                        <tr>
                            <td class="px-4 py-3">{{ $p['nama_prodi'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $p['s1'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $p['s2'] }}</td>
                            <td class="px-4 py-3 text-center">{{ $p['s3'] }}</td>
                            <td class="px-4 py-3 text-center font-semibold">{{ $p['total'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                </table>
            </div>
        </div>



        <!-- Doughnut Charts (Row 1) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
            <!-- Dosen per Prodi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fa-solid fa-graduation-cap text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Jumlah Dosen Per Prodi</h3>
                            <p class="text-xs text-slate-400">Distribusi dosen di setiap program studi.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="dosenProdi"></canvas>
                </div>
            </div>

            <!-- Dosen per KK -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-cubes text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Dosen Per Kelompok Keahlian</h3>
                            <p class="text-xs text-slate-400">Kelompok keahlian / fokus riset dosen.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="dosenKK"></canvas>
                </div>
            </div>

            <!-- Pendidikan Dosen -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                            <i class="fa-solid fa-award text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Profil Pendidikan Dosen</h3>
                            <p class="text-xs text-slate-400">Profil pendidikan terakhir dosen berdasarkan jenjang S1, S2, dan S3.</p>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center relative min-h-[260px]">
                    <canvas id="pendDosen"></canvas>
                </div>
            </div>
        </div>

        <!-- Bar Charts (Row 2) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- JFA Dosen -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-chart-column text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Profil JFA Dosen</h3>
                            <p class="text-xs text-slate-400"> Profil Jabatan Fungsional Akademik dosen.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="jfaDosen"></canvas>
                </div>
            </div>

            <!-- Profil Status Dosen -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">

                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                            <i class="fa-solid fa-user-check text-lg"></i>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-800 text-base">
                                Status Dosen
                            </h3>

                            <p class="text-xs text-slate-400">
                                Status aktif, studi lanjut, dan cuti dosen.
                            </p>
                        </div>

                    </div>

                </div>

                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="statusProfilDosen"></canvas>
                </div>

            </div>

            <!-- Status Pegawai -->
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <i class="fa-solid fa-briefcase text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Status Pegawai - Dosen</h3>
                            <p class="text-xs text-slate-400">Status kepegawaian dosen di lingkungan FIF.</p>
                        </div>
                    </div>
                </div>
                <div class="relative min-h-[300px] flex items-center justify-center">
                    <canvas id="statusDosen"></canvas>
                </div>
            </div>
        </div>
        </div>

        <!-- Studi Lanjut -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-user-graduate text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Dosen Studi Lanjut</h3>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <th class="px-4 py-3 text-left rounded-l-lg">Nama Dosen</th>
                            <th class="px-4 py-3 text-left">Prodi</th>
                            <th class="px-4 py-3 text-left">Jabatan</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Lokasi Kampus</th>
                            <th class="px-4 py-3 text-left">Tahun Mulai</th>
                            <th class="px-4 py-3 text-left rounded-r-lg">Batas Studi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($studiLanjut as $d)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $d->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $d->prodi->nama_prodi ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $d->jabatan }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $d->status_studi_lanjut === 'Tugas Belajar' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $d->status_studi_lanjut }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $d->lokasi_kampus_studi }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $d->tahun_mulai_studi }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $d->batas_studi }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">Tidak ada dosen yang sedang studi lanjut.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nisbah Dosen:Mahasiswa -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm mb-8">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4 mb-6">
                <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fa-solid fa-scale-balanced text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Nisbah Dosen : Mahasiswa</h3>
                    <p class="text-xs text-slate-400">Perbandingan jumlah mahasiswa dan dosen berdasarkan nisbah masing-masing program studi.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                            <th class="px-4 py-3 text-left rounded-l-lg">Program Studi</th>
                            <th class="px-4 py-3 text-center">Jumlah Dosen</th>
                            <th class="px-4 py-3 text-center">Jumlah Mahasiswa</th>
                            <th class="px-4 py-3 text-center rounded-r-lg">Rasio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($nisbah as $n)
                        <tr class="hover:bg-slate-50 transition-colors {{ $n['over_limit'] ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $n['nama_prodi'] }}</td>
                            <td class="px-4 py-3 text-center text-slate-600">{{ $n['dosen'] }}</td>
                            <td class="px-4 py-3 text-center text-slate-600">{{ $n['mahasiswa'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $n['over_limit'] ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    1 : {{ $n['rasio'] }}
                                    {{ $n['over_limit'] ? '⚠️' : '✓' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-slate-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        window.dosenData = {
            prodi: @json($dosenProdi),
            kk: @json($dosenKK),
            pendidikan: @json($pendDosen),
            jfa: @json($jfaDosen),
            status: @json($statusDosen),
            statusProfil: @json($statusProfilDosen)
        };

        document.addEventListener('DOMContentLoaded', () => {
            const dateSpan = document.getElementById('current-date-span');
            if (dateSpan) {
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                dateSpan.textContent = new Date().toLocaleDateString('id-ID', options);
            }
        });
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/dosenChart.js'])
</body>

</html>