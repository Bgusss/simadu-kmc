<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Opd;

class OpdSeeder extends Seeder
{
    public function run(): void
    {
        $opds = [

            'Sekretariat DPRD',
            'Bappeda',
            'BPKAD',
            'BKPSDM',
            'Disdukcapil',
            'Dinas Pendidikan',
            'Dinas PUPR',
            'Dinas Ketahanan Pangan, Kelautan, dan Perikanan',
            'Dinas Kesehatan',
            'Dinas Sosial',
            'Dinas Pertanian, Peternakan dan Perkebunan',
            'Dinas Arsip dan Perpustakaan',
            'Dinas Pemuda dan Olahraga',
            'Dinas Perindustrian, Perdagangan, Koperasi dan UKM',
            'Dinas Kebudayaan dan Pariwisata',
            'Dinas PMD',
            'DPMPTSP',
            'Dinas Tenaga Kerja dan Transmigrasi',
            'Dinas Perhubungan',
            'Dinas Komunikasi dan Informatika',
            'Satpol PP',
            'BPBD',
            'Kesbangpol',
            'RSUD Agoesdjam',
            'PDAM Ketapang',
            'BPN',
            'Bank Kalbar',
            'PLN',
            'Polres Ketapang',
            'PKK',
            'Dinas Pemberdayaan Perempuan dan Perlindungan Anak',
            'Dinas Lingkungan Hidup',

        ];

        foreach ($opds as $opd) {

            Opd::firstOrCreate([

                'name' => $opd

            ]);
        }
    }
}