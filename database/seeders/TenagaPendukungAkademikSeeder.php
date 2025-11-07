<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenagaPendukungAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tenaga_pendukung_akademik')->insert([
            // DSIS
            [
                'user_id' => 1,
                'nama_lengkap' => 'Ahmad Syafii',
                'nip' => '198001012001011001',
                'pangkat_golongan' => 'DSIS',
                'status_pegawai' => 'Pegawai Tetap',
                'lokasi_kerja' => 'SDM',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'user_id' => 2,
                'nama_lengkap' => 'Budi Santoso',
                'nip' => '198506012010021002',
                'pangkat_golongan' => 'DSIS',
                'status_pegawai' => 'Profesional Full Time',
                'lokasi_kerja' => 'LAA',
                'pendidikan_terakhir' => 'S1',
            ],

            // CITI
            [
                'user_id' => 3,
                'nama_lengkap' => 'Citra Anggraini',
                'nip' => '199001152015032003',
                'pangkat_golongan' => 'CITI',
                'status_pegawai' => 'Pegawai Tetap',
                'lokasi_kerja' => 'SEKPIM',
                'pendidikan_terakhir' => 'SMA',
            ],
            [
                'user_id' => 4,
                'nama_lengkap' => 'Dewi Lestari',
                'nip' => '199203232017042004',
                'pangkat_golongan' => 'CITI',
                'status_pegawai' => 'Perbantuan LLDIKTI',
                'lokasi_kerja' => 'KEMAHASISWAAN',
                'pendidikan_terakhir' => 'S1',
            ],

            // SEAL
            [
                'user_id' => 5,
                'nama_lengkap' => 'Eko Prasetyo',
                'nip' => '198802202014051005',
                'pangkat_golongan' => 'SEAL',
                'status_pegawai' => 'Pegawai Tetap',
                'lokasi_kerja' => 'LOGISTIK',
                'pendidikan_terakhir' => 'S1',
            ],
            [
                'user_id' => 6,
                'nama_lengkap' => 'Fadila Rahma',
                'nip' => '199405132019061006',
                'pangkat_golongan' => 'SEAL',
                'status_pegawai' => 'Profesional Part Time',
                'lokasi_kerja' => 'LABORAN',
                'pendidikan_terakhir' => 'SMA',
            ],
            [
                'user_id' => 7,
                'nama_lengkap' => 'Gunawan Putra',
                'nip' => '198712252011071007',
                'pangkat_golongan' => 'SEAL',
                'status_pegawai' => 'Pegawai Tetap',
                'lokasi_kerja' => 'PRODI',
                'pendidikan_terakhir' => 'S1',
            ],
        ]);
    }
}
