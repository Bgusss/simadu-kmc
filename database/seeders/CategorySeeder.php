<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            'Administrasi Kependudukan',
            'Perizinan dan Investasi',
            'Infrastruktur dan Pekerjaan Umum',
            'Pendidikan',
            'Kesehatan',
            'Sosial dan Kesejahteraan Masyarakat',
            'Kepegawaian / SDM Aparatur',
            'Keuangan dan Pajak Daerah',
            'Pertanian, Perikanan, dan Peternakan',
            'Perdagangan, UMKM, dan Koperasi',
            'Komunikasi dan Informatika',
            'Pariwisata, Kebudayaan, dan Olahraga',
            'Lingkungan Hidup dan Kehutanan',
            'Ketentraman dan Ketertiban Umum',
            'Hukum dan Perundang-undangan',
            'Bencana dan Penanggulangan Darurat',
            'Pengaduan Pelayanan Publik',
            'Lain-lain / Umum',
            'Layanan PDAM',
            'Layanan PLN',
            'Pertanyaan',
            'Bank Kalbar',
            'Perumahan',
            'Fasilitas Umum',
            'Pertanahan',
            'Makan Bergizi Gratis',
            'Administrasi',
            'Sengketa Tanah',

        ];

        foreach ($categories as $category) {

            Category::firstOrCreate([

                'name' => $category

            ]);
        }
    }
}