<?php

namespace Database\Seeders;

use App\Models\DistributionRecord;
use App\Models\SortingRecord;
use App\Models\User;
use App\Models\WasteCategory;
use App\Models\WasteReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::create([
            'name' => 'Hendra Wijaya',
            'email' => 'admin@kelurahan.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'zona_wilayah' => 'Koordinator Induk Seluruh Wilayah',
            'telepon' => '0811-2233-4455',
            'status' => 'Aktif',
        ]);

        // 2. Akun Petugas Lapangan
        $petugas1 = User::create([
            'name' => 'Agus Setiawan',
            'email' => 'petugas@kebersihan.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'zona_wilayah' => 'Zona A (RW 01, RW 02, RW 04)',
            'telepon' => '0812-9876-5432',
            'status' => 'Aktif',
        ]);

        $petugas2 = User::create([
            'name' => 'Dedi Kurniawan',
            'email' => 'dedi@kebersihan.id',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
            'zona_wilayah' => 'Zona B (RW 03, RW 05)',
            'telepon' => '0812-7788-9900',
            'status' => 'Aktif',
        ]);

        // 3. Akun Warga
        $warga1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@warga.id',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'zona_wilayah' => 'RW 04 Kelurahan Sukamaju',
            'telepon' => '0813-1122-3344',
            'status' => 'Aktif',
        ]);

        $warga2 = User::create([
            'name' => 'Siti Rahmawati',
            'email' => 'siti@warga.id',
            'password' => Hash::make('password123'),
            'role' => 'warga',
            'zona_wilayah' => 'RW 02 Kelurahan Sukamaju',
            'telepon' => '0813-5566-7788',
            'status' => 'Aktif',
        ]);

        // 4. Kategori Sampah
        WasteCategory::create([
            'kode' => 'KAT-01',
            'nama' => 'Organik Basah (Sisa Dapur & Sayur)',
            'kelompok' => 'Organik',
            'instruksi_penanganan' => 'Tampung terpisah dalam wadah bertutup, distribusikan segera ke budidaya maggot BSF.',
            'potensi_penyaluran' => 'Maggot BSF & Kompos Organik',
            'status' => 'Aktif',
            'name' => 'Organik Basah (Sisa Dapur & Sayur)',
            'type' => 'organik',
            'carbon_offset_factor_per_kg' => 0,
        ]);

        WasteCategory::create([
            'kode' => 'KAT-02',
            'nama' => 'Plastik Daur Ulang (Botol PET & Cup)',
            'kelompok' => 'Anorganik Daur Ulang',
            'instruksi_penanganan' => 'Keringkan dan pipihkan untuk efisiensi volume pengangkutan.',
            'potensi_penyaluran' => 'Bank Sampah & Pabrik Daur Ulang',
            'status' => 'Aktif',
            'name' => 'Plastik Daur Ulang (Botol PET & Cup)',
            'type' => 'anorganik',
            'carbon_offset_factor_per_kg' => 0,
        ]);

        // 5. Sample Laporan
        WasteReport::create([
            'kode_laporan' => 'LPR-2026-001',
            'user_id' => $warga1->id,
            'address' => 'Depan Lapangan Voli Gang Mawar, RT 02 / RW 04',
            'description' => 'Ada tumpukan kantong plastik sampah rumah tangga yang dibuang sembarangan pasca acara warga.',
            'nama_pelapor' => $warga1->name,
            'telepon_pelapor' => $warga1->telepon,
            'alamat' => 'Depan Lapangan Voli Gang Mawar, RT 02 / RW 04',
            'rt' => '02',
            'rw' => '04',
            'patokan' => 'Dekat gardu ronda cat hijau',
            'jenis_tumpukan' => 'Tumpukan Plastik Liar',
            'deskripsi' => 'Ada tumpukan kantong plastik sampah rumah tangga yang dibuang sembarangan pasca acara warga.',
            'foto_url' => '/images/sample_report.jpg',
            'urgensi' => 'tinggi',
            'status' => 'reported',
        ]);

        WasteReport::create([
            'kode_laporan' => 'LPR-2026-002',
            'user_id' => $warga2->id,
            'address' => 'Jl. Flamboyan No. 15, RT 05 / RW 02',
            'description' => 'Pohon mangga ditebang warga, ranting dan dedaunan menutupi sebagian bahu jalan.',
            'nama_pelapor' => $warga2->name,
            'telepon_pelapor' => $warga2->telepon,
            'alamat' => 'Jl. Flamboyan No. 15, RT 05 / RW 02',
            'rt' => '05',
            'rw' => '02',
            'patokan' => 'Sebelah warung Bu Sri',
            'jenis_tumpukan' => 'Limbah Ranting & Daun Tebangan',
            'deskripsi' => 'Pohon mangga ditebang warga, ranting dan dedaunan menutupi sebagian bahu jalan.',
            'foto_url' => '/images/sample_report.jpg',
            'urgensi' => 'sedang',
            'status' => 'in_process',
            'petugas_id' => $petugas1->id,
            'catatan_petugas' => 'Armada motor roda 3 meluncur menuju lokasi aduan.',
        ]);

        // 6. Sample Timbangan Pilah
        SortingRecord::create([
            'kode_pilah' => 'PILAH-001',
            'petugas_id' => $petugas1->id,
            'tanggal' => now()->toDateString(),
            'lokasi_tps' => 'TPS3R Lestari Mandiri RW 04',
            'organik_kg' => 45.0,
            'plastik_kg' => 22.5,
            'kertas_karton_kg' => 12.0,
            'logam_kaca_kg' => 2.5,
            'residu_b3_kg' => 4.0,
            'total_kg' => 86.0,
            'catatan' => 'Pemilahan shift pagi wilayah RW 02 dan RW 04 lancar.',
        ]);

        // 7. Sample Penyaluran
        DistributionRecord::create([
            'no_surat_jalan' => 'SJ/PLT/2026/09/001',
            'petugas_id' => $petugas1->id,
            'tanggal' => now()->toDateString(),
            'mitra_tujuan' => 'Bank Sampah Berkah Mandiri RW 02',
            'kategori_sampah' => 'Plastik PET & Botol Bening',
            'berat_kg' => 45.0,
            'catatan' => 'Diserahkan langsung untuk tabungan nasabah bank sampah warga.',
            'status_serah_terima' => 'Terkirim & Diterima',
        ]);
    }
}
