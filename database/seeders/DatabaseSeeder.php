<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\User;
use App\Models\Notification;
use App\Models\MedicineOutflow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin User
        User::create([
            'name' => 'Admin MedLogix',
            'username' => 'admin',
            'email' => 'admin@medlogix.com',
            'phone' => '08123456789',
            'sipa' => 'SIPA-ADMIN',
            'apotek_address' => 'Kantor Pusat MedLogix',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'payment_status' => 'paid',
        ]);

        // 2. Seed Pharmacist Users
        $pharmacists = [
            [
                'name' => 'Balya',
                'username' => 'balya',
                'email' => 'balya@medlogix.com',
                'phone' => '08123456789',
                'sipa' => 'SIPA-001',
                'apotek_address' => 'Apotek MedLogix Utama, Jl. Raya Kebayoran No. 12, Jakarta',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Farah',
                'username' => 'farah',
                'email' => 'farah@medlogix.com',
                'phone' => '08129876543',
                'sipa' => 'SIPA-002',
                'apotek_address' => 'Apotek MedLogix Barat, Jl. Kebon Jeruk No. 89, Jakarta',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Gunawan',
                'username' => 'gunawan',
                'email' => 'gunawan@medlogix.com',
                'phone' => '08131122334',
                'sipa' => 'SIPA-003',
                'apotek_address' => 'Apotek MedLogix Timur, Jl. Jatinegara No. 44, Jakarta',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($pharmacists as $p) {
            $user = User::create($p);

            // Seed default notification settings for each pharmacist
            Notification::create([
                'user_id' => $user->id,
                'batas_minimal_stok' => 10,
                'waktu_restock_hari' => 7,
                'is_active' => true,
            ]);
        }

        // 2. Seed Medicines with Exact Data Requirements and Alert Levels
        $medicines = [
            [
                'nama_obat' => 'Paracetamol',
                'brand' => 'Sanmol, Panadol',
                'informasi_general' => 'Alert: Hati-hati pada pasien penyakit hati',
                'alert_level' => 'danger',
                'no_batch' => 'PCM240501A',
                'exp_date' => '2027-05-01',
                'stok' => 120,
                'harga' => 8000,
                'tanggal_masuk' => '2026-05-01',
            ],
            [
                'nama_obat' => 'Amoxicillin',
                'brand' => 'Amoxsan, Hufanoxil',
                'informasi_general' => 'Alert: Harus dihabiskan',
                'alert_level' => 'warning',
                'no_batch' => 'AMX240412B',
                'exp_date' => '2026-04-01',
                'stok' => 80,
                'harga' => 25000,
                'tanggal_masuk' => '2026-04-01',
            ],
            [
                'nama_obat' => 'Ibuprofen',
                'brand' => 'Proris, Brufen',
                'informasi_general' => 'Alert: Konsumsi setelah makan',
                'alert_level' => 'info',
                'no_batch' => 'IBU240320C',
                'exp_date' => '2027-03-01',
                'stok' => 95,
                'harga' => 12000,
                'tanggal_masuk' => '2026-03-01',
            ],
            [
                'nama_obat' => 'Chlorpheniramine maleate',
                'brand' => 'CTM',
                'informasi_general' => 'Alert: Hindari mengemudi',
                'alert_level' => 'info',
                'no_batch' => 'CTM240210D',
                'exp_date' => '2027-02-01',
                'stok' => 150,
                'harga' => 5000,
                'tanggal_masuk' => '2026-02-01',
            ],
            [
                'nama_obat' => 'Omeprazole',
                'brand' => 'Losec, Omez',
                'informasi_general' => 'Alert: Risiko defisiensi B12',
                'alert_level' => 'warning',
                'no_batch' => 'OMP240115E',
                'exp_date' => '2027-01-01',
                'stok' => 70,
                'harga' => 30000,
                'tanggal_masuk' => '2026-01-01',
            ],
        ];

        $seededMedicines = [];
        foreach ($medicines as $m) {
            $seededMedicines[] = Medicine::create($m);
        }

        // 3. Seed Mock MedicineOutflows to support dynamic sums
        // Paracetamol (ID 1) -> 33 total
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[0]->id,
            'jumlah_keluar' => 10,
            'tanggal_keluar' => '2026-05-05',
        ]);
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[0]->id,
            'jumlah_keluar' => 15,
            'tanggal_keluar' => '2026-05-15',
        ]);
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[0]->id,
            'jumlah_keluar' => 8,
            'tanggal_keluar' => '2026-05-25',
        ]);

        // Amoxicillin (ID 2) -> 32 total
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[1]->id,
            'jumlah_keluar' => 12,
            'tanggal_keluar' => '2026-05-10',
        ]);
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[1]->id,
            'jumlah_keluar' => 20,
            'tanggal_keluar' => '2026-05-20',
        ]);

        // Ibuprofen (ID 3) -> 15 total
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[2]->id,
            'jumlah_keluar' => 15,
            'tanggal_keluar' => '2026-05-08',
        ]);

        // Chlorpheniramine maleate (ID 4) -> 55 total
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[3]->id,
            'jumlah_keluar' => 30,
            'tanggal_keluar' => '2026-05-02',
        ]);
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[3]->id,
            'jumlah_keluar' => 25,
            'tanggal_keluar' => '2026-05-18',
        ]);

        // Omeprazole (ID 5) -> 28 total
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[4]->id,
            'jumlah_keluar' => 10,
            'tanggal_keluar' => '2026-05-12',
        ]);
        MedicineOutflow::create([
            'medicine_id' => $seededMedicines[4]->id,
            'jumlah_keluar' => 18,
            'tanggal_keluar' => '2026-05-22',
        ]);

        // 4. Seed Default Settings
        $this->call(SettingSeeder::class);
    }
}
