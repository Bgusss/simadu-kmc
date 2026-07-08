<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\Opd;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategoryMap = [
            'Lampu Jalan'         => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
            'Lampu Lalu Lintas'   => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
            'Jembatan'            => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUPR'],
            'Jalan'               => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUPR'],
            'Drainase'            => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas PUPR'],
            'Listrik'             => ['category' => 'Layanan PLN',                           'opd' => 'PLN'],
            'Bantuan Sosial'      => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
            'Orang Terlantar'     => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
            'KDRT'                => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
            'Kekerasan Anak'      => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Sosial'],
            'Kekerasan Perempuan' => ['category' => 'Sosial dan Kesejahteraan Masyarakat',   'opd' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak'],
            'Sampah'              => ['category' => 'Lingkungan Hidup dan Kehutanan',        'opd' => 'Dinas Lingkungan Hidup'],
            'Pohon'               => ['category' => 'Lingkungan Hidup dan Kehutanan',        'opd' => 'BPBD'],
            'Banjir'              => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
            'Kebakaran'           => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
            'Tanah Longsor'       => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
            'Kebakaran Hutan'     => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'BPBD'],
            'KTP'                 => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
            'KK'                  => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
            'Akta Kelahiran'      => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
            'Akta Kematian'       => ['category' => 'Administrasi Kependudukan',             'opd' => 'Disdukcapil'],
            'Fasilitas Pendidikan'=> ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],
            'Guru'                => ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],
            'Sekolah'             => ['category' => 'Pendidikan',                            'opd' => 'Dinas Pendidikan'],
            'Fasilitas Kesehatan' => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],
            'Puskesmas'           => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],
            'Rumah Sakit'         => ['category' => 'Kesehatan',                             'opd' => 'RSUD Agoesdjam'],
            'BPJS'                => ['category' => 'Kesehatan',                             'opd' => 'Dinas Kesehatan'],
            'Internet'            => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
            'Blank Spot'          => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
            'Aplikasi Pemerintah' => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
            'Website Pemerintah'  => ['category' => 'Komunikasi dan Informatika',            'opd' => 'Dinas Komunikasi dan Informatika'],
            'Perizinan Usaha'     => ['category' => 'Perizinan dan Investasi',               'opd' => 'DPMPTSP'],
            'Pajak'               => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],
            'Retribusi'           => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],
            'Irigasi'             => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],
            'Perikanan'           => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan'],
            'Nelayan'             => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Ketahanan Pangan, Kelautan, dan Perikanan'],
            'Peternakan'          => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],
            'Perkebunan'          => ['category' => 'Pertanian, Perikanan, dan Peternakan',  'opd' => 'Dinas Pertanian, Peternakan dan Perkebunan'],
            'UMKM'                => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],
            'Koperasi'            => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],
            'Pasar'               => ['category' => 'Perdagangan, UMKM, dan Koperasi',      'opd' => 'Dinas Perindustrian, Perdagangan, Koperasi dan UKM'],
            'Transportasi Umum'   => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
            'Parkir'              => ['category' => 'Infrastruktur dan Pekerjaan Umum',      'opd' => 'Dinas Perhubungan'],
            'Ketertiban Umum'     => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
            'Keamanan'            => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
            'Hewan Liar'          => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
            'Tempat Ibadah'       => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
            'Ruang Publik'        => ['category' => 'Ketentraman dan Ketertiban Umum',       'opd' => 'Satpol PP'],
            'Taman Kota'          => ['category' => 'Fasilitas Umum',                        'opd' => 'Dinas PUPR'],
            'Pertanahan'          => ['category' => 'Pertanahan',                            'opd' => 'BPN'],
            'Sengketa Tanah'      => ['category' => 'Sengketa Tanah',                       'opd' => 'BPN'],
            'PHK'                 => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'Dinas Tenaga Kerja dan Transmigrasi'],
            'Pelatihan Kerja'     => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'Dinas Tenaga Kerja dan Transmigrasi'],
            'Pendapatan / Gaji'   => ['category' => 'Keuangan dan Pajak Daerah',             'opd' => 'BPKAD'],
            'Kepegawaian'         => ['category' => 'Kepegawaian / SDM Aparatur',            'opd' => 'BKPSDM'],
            'Pariwisata'          => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Kebudayaan dan Pariwisata'],
            'Kebudayaan'          => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Kebudayaan dan Pariwisata'],
            'Olahraga'            => ['category' => 'Pariwisata, Kebudayaan, dan Olahraga',  'opd' => 'Dinas Pemuda dan Olahraga'],
            'Nomor Darurat'       => ['category' => 'Bencana dan Penanggulangan Darurat',    'opd' => 'RSUD Agoesdjam'],
            'ATM'                 => ['category' => 'Bank Kalbar',                           'opd' => 'Bank Kalbar'],
            'Makan Bergizi Gratis'=> ['category' => 'Makan Bergizi Gratis',                  'opd' => 'Dinas Kesehatan'],
            'Monitoring Berita'   => ['category' => 'Lain-lain / Umum',                      'opd' => null],
            'Keluhan Masyarakat'  => ['category' => 'Pengaduan Pelayanan Publik',            'opd' => null],
            'Aduan Masyarakat'    => ['category' => 'Pengaduan Pelayanan Publik',            'opd' => null],
        ];

        foreach ($subCategoryMap as $subName => $info) {
            $category = Category::where('name', $info['category'])->first();
            $opd = $info['opd'] ? Opd::where('name', $info['opd'])->first() : null;

            SubCategory::updateOrCreate(
                ['name' => $subName],
                [
                    'category_id' => $category ? $category->id : null,
                    'opd_id' => $opd ? $opd->id : null,
                ]
            );
        }

        $allSubNames = [
            'Jalan', 'Lampu Jalan', 'Jembatan', 'Drainase', 'Sampah', 'Banjir', 'Air Bersih', 'Listrik',
            'KTP', 'KK', 'Akta Kelahiran', 'Akta Kematian', 'Fasilitas Pendidikan', 'Guru', 'Sekolah',
            'Fasilitas Kesehatan', 'Puskesmas', 'Rumah Sakit', 'BPJS', 'Bantuan Sosial', 'Orang Terlantar',
            'UMKM', 'Koperasi', 'Pasar', 'Irigasi', 'Perikanan', 'Peternakan', 'Perkebunan', 'Internet',
            'Blank Spot', 'Aplikasi Pemerintah', 'Website Pemerintah', 'Perizinan Usaha', 'Pajak', 'Retribusi',
            'Transportasi Umum', 'Parkir', 'Kebakaran', 'Tanah Longsor', 'Kebakaran Hutan', 'Ketertiban Umum',
            'Keamanan', 'Tempat Ibadah', 'Ruang Publik', 'Taman Kota', 'Pertanahan', 'Sengketa Tanah', 'PHK',
            'Pelatihan Kerja', 'Pendapatan / Gaji', 'Pariwisata', 'Kebudayaan', 'Olahraga', 'Hewan Liar',
            'Kepegawaian', 'Nomor Darurat', 'KDRT', 'Kekerasan Anak', 'Kekerasan Perempuan', 'ATM',
            'Keluhan Masyarakat', 'Aduan Masyarakat', 'Monitoring Berita', 'Pohon', 'Nelayan', 'Makan Bergizi Gratis'
        ];

        foreach ($allSubNames as $name) {
            if (!isset($subCategoryMap[$name])) {
                SubCategory::firstOrCreate(['name' => $name]);
            }
        }

        // Map subcategories that are missing in the map but exist in DB
        $additional = [
            'Air Bersih' => ['category' => 'Infrastruktur dan Pekerjaan Umum', 'opd' => 'Dinas PUPR'],
        ];

        foreach ($additional as $subName => $info) {
            $category = Category::where('name', $info['category'])->first();
            $opd = $info['opd'] ? Opd::where('name', $info['opd'])->first() : null;
            $sub = SubCategory::where('name', $subName)->first();
            if ($sub) {
                $sub->update([
                    'category_id' => $category ? $category->id : null,
                    'opd_id' => $opd ? $opd->id : null,
                ]);
            }
        }

        // Ensure every category has at least one subcategory
        $categories = Category::all();
        foreach ($categories as $cat) {
            if ($cat->subCategories()->count() === 0) {
                $opdId = null;
                if ($cat->name === 'Layanan PDAM') {
                    $opd = Opd::where('name', 'PDAM Ketapang')->first();
                    if ($opd) $opdId = $opd->id;
                } elseif ($cat->name === 'Hukum dan Perundang-undangan') {
                    $opd = Opd::where('name', 'Kesbangpol')->first();
                    if ($opd) $opdId = $opd->id;
                }
                SubCategory::create([
                    'name' => $cat->name,
                    'category_id' => $cat->id,
                    'opd_id' => $opdId,
                ]);
            }
        }
    }
}