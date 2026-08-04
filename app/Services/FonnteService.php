<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Kirim pesan WhatsApp via Fonnte API.
     *
     * @param  string  $phone   Nomor WA tujuan (format: 6281234567890)
     * @param  string  $message Isi pesan
     * @return array   Response dari Fonnte
     */
    public static function send(string $phone, string $message): array
    {
        $token = config('services.fonnte.token');

        if (empty($token)) {
            Log::warning('FonnteService: FONNTE_TOKEN belum dikonfigurasi');
            return ['success' => false, 'message' => 'Token Fonnte belum dikonfigurasi'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

            $result = $response->json();

            Log::info('FonnteService: Pesan terkirim', [
                'phone'    => $phone,
                'response' => $result,
            ]);

            return $result ?? ['success' => false, 'message' => 'No response'];
        } catch (\Exception $e) {
            Log::error('FonnteService: Gagal kirim pesan', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Kirim pesan dengan lampiran gambar via Fonnte.
     *
     * @param  string  $phone    Nomor WA tujuan
     * @param  string  $message  Isi pesan
     * @param  string  $imageUrl URL gambar
     * @return array
     */
    public static function sendWithImage(string $phone, string $message, string $imageUrl): array
    {
        $token = config('services.fonnte.token');

        if (empty($token)) {
            return ['success' => false, 'message' => 'Token Fonnte belum dikonfigurasi'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
                'url'     => $imageUrl,
            ]);

            return $response->json() ?? ['success' => false];
        } catch (\Exception $e) {
            Log::error('FonnteService: Gagal kirim pesan + gambar', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Format nomor HP ke format internasional Indonesia (628xxx).
     *
     * @param  string  $phone
     * @return string
     */
    public static function formatPhone(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Hapus suffix WhatsApp (@s.whatsapp.net, @c.us, dll)
        $phone = preg_replace('/@.+$/', '', $phone);

        // Konversi 08xxx ke 628xxx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Pastikan diawali 62
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
