<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Dosen;

class DosenPengujiAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all unique dosen who are assigned as penguji
        $dosenPengujiList = DB::table('jadwal_pengujian_dosen')
            ->join('dosen', 'jadwal_pengujian_dosen.dosen_id', '=', 'dosen.id')
            ->select('dosen.id', 'dosen.nama_lengkap', 'dosen.user_id')
            ->distinct()
            ->get();

        $password = Hash::make('password123');
        $createdAccounts = [];

        foreach ($dosenPengujiList as $dosen) {
            // Create username from name (remove titles, spaces, lowercase)
            $username = strtolower(str_replace([' ', '.', ',', 'Dr', 'dr'], '', $dosen->nama_lengkap));
            
            // Check if user already exists for this dosen
            if ($dosen->user_id) {
                $user = User::find($dosen->user_id);
                if ($user) {
                    // Update existing user
                    $user->update([
                        'username' => $username,
                        'password' => $password,
                    ]);
                    
                    // Sync role - check what urutan this dosen has
                    $urutanList = DB::table('jadwal_pengujian_dosen')
                        ->where('dosen_id', $dosen->id)
                        ->pluck('urutan')
                        ->unique()
                        ->toArray();
                    
                    // If dosen has multiple urutan, assign all corresponding roles
                    $roleNames = [];
                    foreach ($urutanList as $urutan) {
                        $roleNames[] = 'Dosen Penguji ' . $urutan;
                    }
                    
                    // Sync roles
                    $user->syncRoles($roleNames);
                    
                    $createdAccounts[] = [
                        'nama' => $dosen->nama_lengkap,
                        'username' => $username,
                        'roles' => implode(', ', $roleNames),
                        'status' => 'Updated'
                    ];
                    
                    continue;
                }
            }
            
            // Create new user
            $user = User::create([
                'username' => $username,
                'password' => $password,
            ]);
            
            // Update dosen to link with user
            DB::table('dosen')->where('id', $dosen->id)->update(['user_id' => $user->id]);
            
            // Get all urutan for this dosen
            $urutanList = DB::table('jadwal_pengujian_dosen')
                ->where('dosen_id', $dosen->id)
                ->pluck('urutan')
                ->unique()
                ->toArray();
            
            // Assign roles based on urutan
            $roleNames = [];
            foreach ($urutanList as $urutan) {
                $roleNames[] = 'Dosen Penguji ' . $urutan;
            }
            
            $user->assignRole($roleNames);
            
            $createdAccounts[] = [
                'nama' => $dosen->nama_lengkap,
                'username' => $username,
                'roles' => implode(', ', $roleNames),
                'status' => 'Created'
            ];
        }
        
        // Display results
        $this->command->info("\n=== AKUN DOSEN PENGUJI BERHASIL DIBUAT/DIUPDATE ===\n");
        $this->command->table(
            ['Nama Dosen', 'Username', 'Role', 'Status'],
            collect($createdAccounts)->map(function($account) {
                return [
                    $account['nama'],
                    $account['username'],
                    $account['roles'],
                    $account['status']
                ];
            })
        );
        $this->command->info("\nPassword untuk semua akun: password123\n");
    }
}
