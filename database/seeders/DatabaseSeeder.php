<?php

namespace Database\Seeders;

use DeepCopy\f013\C;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ============================================
            // 1. BASE DATA (Roles & Master Data)
            // ============================================
            RoleSeeder::class,
            FakultasSeeder::class,
            ProdiSeeder::class,
            KelompokKeahlianSeeder::class,
            TahunAjarSeeder::class,

            // ============================================
            // 2. USERS & RELATED DATA
            // ============================================
            UserSeeder::class,
            DosenSeeder::class,
            TenagaPendukungAkademikSeeder::class,
            CalonDosenSeeder::class,
            RiwayatPendidikanCalonDosenSeeder::class,
            MahasiswaSeeder::class,
            JadwalPengujianSeeder::class,

            // ============================================
            // 3. PERMISSIONS (Menu Access Control)
            // ============================================
            DashboardPermissionSeeder::class,
            
            // Manajemen Dosen
            ManajemenDosenPermissionSeeder::class,
            ManajemenDosenImportPermissionSeeder::class,
            ManajemenDosenLaporanPermissionSeeder::class,
            
            // Rekrutasi Dosen
            ManajemenRekrutasiDosenPermissionSeeder::class,
            ManajemenRekrutasiDosenImportPermissionSeeder::class,
            ManajemenPenilaianCalonDosenPermissionSeeder::class,
            ManajemenJadwalPengujianPermissionSeeder::class,
            ManajemenHasilPengujianPermissionSeeder::class,
            ManajemenBeritaAcaraPermissionSeeder::class,
            
            // Manajemen TPA
            ManajemenTPAPermissionSeeder::class,
            ManajemenTPAImportPermissionSeeder::class,
            
            // Manajemen Mahasiswa
            ManajemenKelolaMahasiswaPermissionSeeder::class,
            ManajemenImportMahasiswaPermissionSeeder::class,
            
            // Master Data
            ManajemenMasterDataFakultasPermissionSeeder::class,
            ManajemenMasterDataProdiPermissionSeeder::class,
            ManajemenMasterDataKompetisiPermissionSeeder::class,
            
            // Pengaturan
            PengaturanPermissionsSeeder::class,
        ]);
    }
}