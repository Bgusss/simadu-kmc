<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageFileController extends Controller
{
    /**
     * Serve file dari storage/app/public.
     * Menggantikan symlink public/storage yang tidak bekerja di php artisan serve.
     */
    public function show(string $path): BinaryFileResponse
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->file($fullPath);
    }

    /**
     * Debug endpoint sementara — hapus setelah gambar bekerja.
     */
    public function debug()
    {
        $storagePath = storage_path('app/public');
        $result = [
            'storage_path' => $storagePath,
            'exists' => is_dir($storagePath),
            'public_storage_exists' => file_exists(public_path('storage')),
            'public_storage_is_link' => is_link(public_path('storage')),
        ];

        // List files
        $files = [];
        if (is_dir($storagePath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($storagePath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                $files[] = str_replace($storagePath . '/', '', $file->getPathname());
            }
        }
        $result['files'] = $files;
        $result['file_count'] = count($files);

        return response()->json($result, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
