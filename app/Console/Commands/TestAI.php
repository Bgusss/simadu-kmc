<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AIClassificationService;

class TestAI extends Command
{
    protected $signature = 'ai:test {--no-delay : Jalankan tanpa jeda antar aduan}';

    protected $description = 'Test klasifikasi AI dengan 10 aduan asli masyarakat Ketapang';

    // 10 aduan asli dari data lapangan KMC Ketapang
    private array $aduanAsli = [
        [
            'label'    => 'Air PDAM (dialek Ketapang)',
            'expected' => 'Air Bersih → PDAM Ketapang',
            'aduan'    => 'min aik nin kontan dak pakai jalan dah 2 hari nin, tolong gak dibenarkan. id pelanggan 1080961',
        ],
        [
            'label'    => 'Lampu Jalan Mati',
            'expected' => 'Lampu Jalan → Dinas Perhubungan',
            'aduan'    => 'lampu sepanjang jalan jembatan pwn 5 mati semua, gelap banget pak, rawan kecelakaan',
        ],
        [
            'label'    => 'Jalan Rusak CPO',
            'expected' => 'Jalan → Dinas PUTR',
            'aduan'    => 'sering terjadi tumpahan minyak CPO di jalan sui awan yg menyebabkan kecelakaan sepeda motor. Mohon dapat menjadi perhatian pemda karena sangat membahayakan pengendara motor.',
        ],
        [
            'label'    => 'Orang Terlantar',
            'expected' => 'Orang Terlantar → Dinas Sosial',
            'aduan'    => 'ada lansia terlantar di jl agus salim perlu pertolongan, sudah beberapa hari di situ tidak ada yang ngurusin',
        ],
        [
            'label'    => 'ODGJ Ganggu Warga',
            'expected' => 'ODGJ → Dinas Sosial',
            'aduan'    => 'izin melaporkan ada lansia dg gaduh gelisah di jalan kalinilam. Ada aksi kekerasan dari cucu. Beliau sering kabur dan sulit dikendalikan, mohon bantuannya',
        ],
        [
            'label'    => 'Bantuan Sosial Bedah Rumah',
            'expected' => 'Bantuan Sosial → Dinas Sosial',
            'aduan'    => 'Nama: Ahmad syahbandi. Alamat: JL.H.muhammad kumuk. Permasalahan: Belum mendapatkan bantuan bedah rumah padahal sudah daftar lama.',
        ],
        [
            'label'    => 'Ketenagakerjaan Perusahaan',
            'expected' => 'Ketenagakerjaan → Dinas Tenaga Kerja',
            'aduan'    => 'Kami pemuda kecamatan Matan Hilir Selatan sudah seringkali melamar pekerjaan di perusahaan PT BAP dan KBS tapi lamaran tidak ada yang diterima, padahal sudah mediasi. Mohon solusinya',
        ],
        [
            'label'    => 'Listrik PLN Belum Masuk',
            'expected' => 'Listrik → PLN',
            'aduan'    => 'memohon bantuan supaya PLN kami bisa nyala, kendala nya kabel belum ada dari simpang kelampai 7 km, tiang sudah ada mohon di bantu',
        ],
        [
            'label'    => 'Nelayan Butuh Alat Tangkap',
            'expected' => 'Nelayan → Dinas Ketahanan Pangan',
            'aduan'    => 'tolong bantu kami untuk alat tangkap, sudah beberapa tahun ini program ke pemerintah tidak pernah lagi mengucurkan bantuan kepada nelayan pesisir dusun sungai tengar kecamatan Kendawangan',
        ],
        [
            'label'    => 'Drainase Pintu Air Sawah',
            'expected' => 'Drainase → Dinas PUTR',
            'aduan'    => 'tidak berfungsinya pintu air persawahan desa Banjarsari karena tidak ada pemeliharaan dari dinas pengairan menyebabkan masuknya air pasang laut ke persawahan kami',
        ],
    ];

    public function handle(): void
    {
        $ai = app(AIClassificationService::class);

        $this->newLine();
        $this->line('  <fg=cyan;options=bold>╔══════════════════════════════════════════════════════════════╗</>');
        $this->line('  <fg=cyan;options=bold>║      TEST AI KLASIFIKASI — KMC KETAPANG (DATA LAPANGAN)      ║</>');
        $this->line('  <fg=cyan;options=bold>╚══════════════════════════════════════════════════════════════╝</>');
        $this->line("  Model  : <fg=yellow>" . config('gemini.model', 'gemma-4-31b-it') . "</> (Google AI Studio)");
        $this->line("  Total  : <fg=yellow>" . count($this->aduanAsli) . " aduan asli masyarakat Ketapang</>");
        $this->newLine();

        $correct   = 0;
        $totalTime = 0;
        $noDelay   = $this->option('no-delay');

        foreach ($this->aduanAsli as $i => $item) {
            $no    = $i + 1;
            $start = microtime(true);

            $result = $ai->classify($item['aduan']);

            $elapsed    = round(microtime(true) - $start, 2);
            $totalTime += $elapsed;

            $sub      = $result['suggested_sub_category'] ?? '-';
            $opds     = $result['suggested_opds'] ?? [];
            $opd      = $opds[0] ?? '-';
            $priority = $result['priority'] ?? '-';
            $conf     = $result['confidence'] ?? 0;
            $reason   = $result['reasoning'] ?? '';

            $mode = $conf > 0
                ? "<fg=magenta>🤖 AI ({$conf}%)</>"
                : "<fg=blue>📋 Keyword Fallback</>";

            $priorityColor = match($priority) {
                'Tinggi' => 'red',
                'Sedang' => 'yellow',
                default  => 'green',
            };

            // Cek benar/salah berdasarkan expected
            $expectedParts = explode(' → ', $item['expected']);
            $expectedSub   = trim($expectedParts[0] ?? '');
            $subOk         = stripos($sub, $expectedSub) !== false;
            $status        = $subOk ? '<fg=green>✅</>' : '<fg=red>❌</>';
            if ($subOk) $correct++;

            $this->line("  ─────────────────────────────────────────────────────────────");
            $this->line("  {$status} <options=bold>#{$no} {$item['label']}</>");
            $this->newLine();
            $this->line("  <fg=gray>Aduan    :</> {$item['aduan']}");
            $this->line("  <fg=gray>Expected :</> <fg=cyan>{$item['expected']}</>");
            $this->newLine();
            $this->line("  <fg=gray>Sub Kat  :</> <options=bold>{$sub}</>" . ($subOk ? ' <fg=green>✓</>' : ' <fg=red>✗</>'));
            $this->line("  <fg=gray>OPD      :</> {$opd}");
            $this->line("  <fg=gray>Prioritas:</> <fg={$priorityColor}>{$priority}</>");
            $this->line("  <fg=gray>Alasan   :</> {$reason}");
            $this->line("  <fg=gray>Mode     :</> {$mode}  <fg=gray>⏱ {$elapsed}s</>");
            $this->newLine();

            // Jeda antar request agar tidak kena rate limit
            if (!$noDelay && $i < count($this->aduanAsli) - 1) {
                sleep(1);
            }
        }

        // ── RINGKASAN ──
        $accuracy = round($correct / count($this->aduanAsli) * 100);
        $avgTime  = round($totalTime / count($this->aduanAsli), 2);

        $this->line('  <fg=cyan>╔══════════════════════════════════════════════════════════════╗</>');
        $this->line('  <fg=cyan>║                      RINGKASAN HASIL                        ║</>');
        $this->line('  <fg=cyan>╚══════════════════════════════════════════════════════════════╝</>');
        $this->newLine();

        $accColor = $accuracy >= 90 ? 'green' : ($accuracy >= 70 ? 'yellow' : 'red');
        $this->line("  ✅ Akurasi    : <fg={$accColor};options=bold>{$correct}/10 ({$accuracy}%)</>");
        $this->line("  ⏱  Rata Waktu : <fg=yellow>{$avgTime}s</> per aduan");
        $this->newLine();

        if ((int)$accuracy === 100) {
            $this->line('  <fg=green;options=bold>  🎉 SEMPURNA! Semua aduan terklasifikasi dengan benar.</> ');
        } elseif ($accuracy >= 80) {
            $this->line('  <fg=yellow>  ✔ Baik. Sebagian besar aduan terklasifikasi dengan benar.</> ');
        } else {
            $this->line('  <fg=red>  ⚠ Perlu perbaikan keyword/prompt lebih lanjut.</> ');
        }
        $this->newLine();
    }
}